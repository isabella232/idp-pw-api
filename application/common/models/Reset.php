<?php
namespace common\models;

use common\helpers\Utils;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\TooManyRequestsHttpException;

/**
 * Class Reset
 * @package common\models
 * @method Reset self::findOne([])
 */
class Reset extends ResetBase
{

    const TYPE_PRIMARY = 'primary'; // Used for primary email address
    const TYPE_METHOD = 'method';
    const TYPE_SUPERVISOR = 'supervisor';
    const TYPE_SPOUSE = 'spouse';

    const TOPIC_RESET_EMAIL_SENT = 'Reset Email Sent';
    const TOPIC_RESET_PHONE_SENT = 'Reset Phone Sent';

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(
            [
                [
                    ['uid'], 'default', 'value' => Utils::generateRandomString(),
                ],

                [
                    ['created'], 'default', 'value' => Utils::getDatetime(),
                ],

                [
                    ['expires'], 'default', 'value' => Utils::getDatetime(self::getExpireTimestamp()),
                ],

                [
                    ['type'], 'default', 'value' => self::TYPE_PRIMARY,
                ],

                [
                    ['attempts'], 'default', 'value' => 0,
                ],

                [
                    ['type'], 'in', 'range' => [
                        self::TYPE_PRIMARY, self::TYPE_METHOD, self::TYPE_SUPERVISOR, self::TYPE_SPOUSE
                    ],
                    'message' => 'Reset type must be either ' . self::TYPE_PRIMARY . ' or ' . self::TYPE_METHOD .
                        ' or ' . self::TYPE_SUPERVISOR . ' or ' . self::TYPE_SPOUSE . ' .',
                ],
            ],
            parent::rules()
        );
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'uid',
            'hasSupervisor' => function($model) {
                return $model->user->hasSupervisor();
            },
            'hasSpouse' => function($model) {
                return $model->user->hasSpouse();
            },
            'methods' => function($model) {
                return $model->user->getMaskedMethods();
            },
        ];
    }

    /**
     * @param User $user
     * @param string [default=self::TYPE_PRIMARY] $type
     * @param integer|null [default=null] $method_id
     * @return Reset
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public static function findOrCreate($user, $type = self::TYPE_PRIMARY, $methodId = null)
    {
        /*
         * Find existing or create new Reset
         */
        $reset = $user->reset;
        if ($reset === null) {
            $reset = new Reset();
            $reset->user_id = $user->id;
            /*
             * Only set type/method if creating so that on subsequent restarts of process
             * it does not reset method to primary
             */
            $reset->type = $type;
            /*
             * If $method_id is provided, make sure user owns it
             */
            if ($type == self::TYPE_METHOD && $methodId !== null) {
                $method = Method::findOne(['user_id' => $user->id, 'id' => $methodId, 'verified' => 1]);
                if ( ! $method) {
                    throw new NotFoundHttpException('Requested method not found', 1456608142);
                }
                $reset->method_id = $methodId;
            }
            /*
             * Save new Reset
             */
            if ( ! $reset->save()) {
                throw new \Exception('Unable to create new reset', 1456608028);
            }
        }

        return $reset;
    }

    /**
     * Make sure reset is not disabled, track attempt, and then send.
     * Send reset notification to appropriate method
     * @throws \Exception
     */
    public function send()
    {
        /*
         * Track attempt and throw error if disabled or limit is reached
         */
        $this->trackAttempt('send');

        /*
         * Based on type/method send reset verification and update
         * model with reset code
         */
        switch ($this->type) {
            case self::TYPE_PRIMARY:
                $this->sendPrimary();
                break;
            case self::TYPE_SUPERVISOR:
                $this->sendSupervisor();
                break;
            case self::TYPE_SPOUSE:
                $this->sendSpouse();
                break;
            case self::TYPE_METHOD:
                $this->sendMethod();
                break;
            default:
                throw new \Exception('Reset is configured with unknown type.', 1456784825);
        }
    }

    private function sendPrimary()
    {
        $subject = \Yii::t(
            'app',
            '{{idpName}} password reset request',
            [
                'idpName' => \Yii::$app->params['idpName'],
            ]
        );

        $this->sendEmail($this->user->email, $subject, 'self');
    }

    private function sendSupervisor()
    {
        if ($this->user->hasSupervisor()) {
            $supervisor = $this->user->getSupervisorEmail();
            $this->sendOnBehalf($supervisor);
        } else {
            throw new \Exception('User does not have supervisor on record', 1461173406);
        }
    }

    private function sendSpouse()
    {
        if ($this->user->hasSpouse()) {
            $spouse = $this->user->getSpouseEmail();
            $this->sendOnBehalf($spouse);
        } else {
            throw new \Exception('User does not have spouse on record', 1461173477);
        }
    }

    private function sendOnBehalf($toAddress)
    {
        $subject = \Yii::t(
            'app',
            '{idpName} password reset request for {name}',
            [
                'idpName' => \Yii::$app->params['idpName'],
                'name' => $this->user->first_name,
            ]
        );

        $this->sendEmail($toAddress, $subject, 'on-behalf', $this->user->email);
    }

    /**
     * Determine if type is phone or email and send accordingly
     * @throws \Exception
     */
    private function sendMethod()
    {
        if ( ! ($this->method instanceof Method)) {
            throw new \Exception('Method not initialized on Reset', 1456608512);
        }

        if ($this->method->type == Method::TYPE_EMAIL) {
            /*
             * Send email to 'self' with verified email address
             */
            $subject = \Yii::t(
                'app',
                '{{idpName}} password reset request',
                [
                    'idpName' => \Yii::$app->params['idpName'],
                ]
            );
            $this->sendEmail($this->method->value, $subject, 'self');
        } elseif ($this->method->type == Method::TYPE_PHONE) {
            $this->sendPhone();
        } else {
            throw new \Exception('Method using unknown type', 1456608781);
        }
    }

    /**
     * @param string $toAddress
     * @param string $subject
     * @param string $view
     * @param string|null $ccAddress
     * @throws \Exception
     */
    private function sendEmail($toAddress, $subject, $view, $ccAddress = null)
    {
        /*
         * Generate code if needed, update attempt counter, save record, and send email
         */
        if ($this->code === null) {
            $this->code = Utils::getRandomDigits(\Yii::$app->params['reset']['codeLength']);
        }
        if ($this->save()) {
            // Send email verification
            Verification::sendEmail(
                $toAddress,
                $subject,
                '@common/mail/reset/' . $view,
                $this->code,
                $this->user,
                $ccAddress,
                $this->user->id,
                self::TOPIC_RESET_EMAIL_SENT,
                'Password reset email for ' . $this->user->getDisplayName() .
                'sent to ' . $toAddress
            );
        } else {
            throw new \Exception('Unable to update reset in database, email not sent', 1461098651);
        }
    }

    /**
     * Send phone verification and store resulting code
     * @throws \Exception
     */
    private function sendPhone()
    {
        // Initialize log
        $log = [
            'action' => 'reset',
            'method' => 'phone',
            'method_id' => $this->method_id,
            'previous_attempts' => $this->attempts,
        ];

        // Get phone number without comma
        $number = $this->method->getRawPhoneNumber();

        // Generate random code for potential use
        $code = Utils::getRandomDigits(\Yii::$app->phone->codeLength);

        // Send phone verification
        $result = Verification::sendPhone(
            $number,
            $code,
            $this->user->getId(),
            self::TOPIC_RESET_PHONE_SENT,
            'Password reset for ' . $this->user->getDisplayName() .
            'sent to phone ' . $this->method->getMaskedValue()
        );

        // Update db with code and increased attempts count
        $this->code = $result;
        if ( ! $this->save()) {
            $log['status'] = 'error';
            $log['error'] = 'Unable to update Reset in database';
            $log['model_error'] = Json::encode($this->getFirstErrors());
            \Yii::error($log, 'application');
            throw new \Exception($log['error'], 1460388532);
        }
        $log['status'] = 'success';
        \Yii::warning($log, 'application');
    }

    /**
     * Check if user provided code is valid
     * @param string $userProvided code submitted by user
     * @return boolean
     * @throws \Exception
     * @throws ServerErrorHttpException
     * @throws TooManyRequestsHttpException
     * @throws \Sil\IdpPw\Common\PhoneVerification\NotMatchException
     */
    public function isUserProvidedCodeCorrect($userProvided)
    {
        /*
         * Track attempt and throw error if disabled or limit is reached
         */
        $this->trackAttempt('verify');

        if ($this->isTypePhone()) {
            return Verification::isPhoneCodeValid($this->code, $userProvided);
        } elseif ($this->isTypeEmail()) {
            return Verification::isEmailCodeValid($this->code, $userProvided);
        } else {
            throw new \Exception('Unable to verify code because method type is invalid', 1462543005);
        }
    }

    /**
     * Check if reset is using an email type of verification
     * @return bool
     */
    public function isTypeEmail()
    {
        if ($this->type === self::TYPE_PRIMARY
            || $this->type === self::TYPE_SUPERVISOR
            || $this->type === self::TYPE_SPOUSE
            || ($this->type === self::TYPE_METHOD && $this->method->type === Method::TYPE_EMAIL)) {
            return true;
        }

        return false;
    }

    /**
     * Check if reset is using a phone type of verification
     * @return bool
     */
    public function isTypePhone()
    {
        if ($this->type === self::TYPE_METHOD && $this->method->type === Method::TYPE_PHONE) {
            return true;
        }

        return false;
    }

    /**
     * Calculate expiration timestamp based on given timestamp and configured reset lifetime
     * @return integer
     * @throws ServerErrorHttpException
     */
    public static function getExpireTimestamp()
    {
        $params = \Yii::$app->params;
        if ( ! isset($params['reset']) || ! isset($params['reset']['lifetimeSeconds']) ||
            ! is_integer($params['reset']['lifetimeSeconds'])) {
            throw new ServerErrorHttpException('Application configuration for reset lifetime is not set', 1458676224);
        }

        $time = time();

        return $time + $params['reset']['lifetimeSeconds'];
    }

    /**
     * Check if this reset is currently disabled
     * @return bool
     */
    public function isDisabled()
    {
        if ($this->disable_until !== null) {
            $disableUntilTime = strtotime($this->disable_until);
            // Intentionally loose comparison to catch zero
            if ($disableUntilTime == false) {
                return true;
            }
            return $disableUntilTime > time();
        }

        return false;
    }

    /**
     * Mark reset as disabled by setting disable_until date
     * @throws ServerErrorHttpException
     */
    public function disable()
    {
        $log = [
            'action' => 'disable reset',
            'reset_id' => $this->id,
            'attempts' => $this->attempts,
        ];
        $this->disable_until = Utils::getDatetime(time() + \Yii::$app->params['reset']['disableDuration']);
        if ( ! $this->save()) {
            $log['status'] = 'error';
            $log['error'] = 'Unable to save reset with disable_until. Error: ' . Json::encode($this->getFirstErrors());
            \Yii::error($log);
            throw new ServerErrorHttpException();
        }

        $log['status'] = 'success';
        \Yii::warning($log);
    }

    public function setType($type, $methodId = null)
    {
        /*
         * If type is method but methodId is missing, throw error
         */
        if ($type == self::TYPE_METHOD && $methodId === null) {
            throw new BadRequestHttpException('Method ID required for reset type method', 1462988984);
        }

        /*
         * If type is not method, update or throw error
         */
        if ($type === self::TYPE_SPOUSE || $type === self::TYPE_SUPERVISOR || $type == self::TYPE_PRIMARY) {
            $this->type = $type;
            $this->method_id = null;
        } elseif ($type == self::TYPE_METHOD && $methodId !== null) {
            /*
             * Make sure user owns requested method before update
             */
            $method = Method::findOne(['id' => $methodId, 'user_id' => $this->user_id]);
            if ($method === null) {
                throw new NotFoundHttpException('Method not found', 1462989221);
            }
            $this->type = self::TYPE_METHOD;
            $this->method_id = $methodId;
        } else {
            throw new BadRequestHttpException('Unknown reset type requested', 1462989489);
        }

        /*
         * Save changes
         */
        if ( ! $this->save()) {
            \Yii::error([
                'action' => 'Set type of reset',
                'reset_id' => $this->id,
                'type' => $type,
                'status' => 'error',
                'error' => 'Unable to update reset. Error: ' . Json::encode($this->getFirstErrors()),
            ]);
            throw new ServerErrorHttpException('Unable to update reset type', 1462988908);
        }
    }

    /**
     * Increments attempts counter and disables account when limit is reached
     * @param string $action Used in logging, either 'send' or 'verify'
     * @throws ServerErrorHttpException
     * @throws TooManyRequestsHttpException
     */
    public function trackAttempt($action)
    {
        /*
         * Increment attempts count first thing
         */
        $this->attempts++;
        if( ! $this->save()) {
            \Yii::error([
                'action' => $action . ' reset',
                'reset_id' => $this->id,
                'attempts' => $this->attempts,
                'error' => 'Unable to increment attempts count. Error: ' . Json::encode($this->getFirstErrors()),
            ]);
        }

        /*
         * Check if reset is disabled and throw exception if it is
         */
        if ($this->isDisabled()) {
            \Yii::error([
                'action' => $action . ' reset',
                'reset_id' => $this->id,
                'attempts' => $this->attempts,
                'status' => 'error',
                'error' => 'Reset is currently disabled until ' . $this->disable_until,
            ]);
            throw new TooManyRequestsHttpException();
        }

        /*
         * If attempts has reached limit, disable reset
         */
        if ($this->attempts >= \Yii::$app->params['reset']['maxAttempts']) {
            $this->disable();
            throw new TooManyRequestsHttpException();
        }
    }
}