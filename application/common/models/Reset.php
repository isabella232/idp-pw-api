<?php
namespace common\models;

use yii\helpers\ArrayHelper;

use common\helpers\Utils;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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
            'hasSupervisor' => $this->user->hasSupervisor(),
            'hasSpouse' => $this->user->hasSpouse(),
            'methods' => $this->user->getMaskedMethods(),
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
                $method = Method::findOne(['user_id' => $user->id, 'id' => $methodId]);
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
     * Send reset notification to appropriate method
     * @throws \Exception
     */
    public function send()
    {
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

    public function sendPrimary()
    {
        /**
         * @todo send email to $user->email with reset code
         */
    }

    public function sendSupervisor()
    {
        /**
         * @todo if $this->user->hasSupervisor(), send reset
         *       code to $this->user->getSupervisorEmail()
         */
    }

    public function sendSpouse()
    {
        /**
         * @todo if $this->user->hasSpouse(), send reset
         *       code to $this->user->getSpouseEmail()
         */
    }

    /**
     * Determine if type is phone or email and send accordingly
     * @throws \Exception
     */
    public function sendMethod()
    {
        if ( ! ($this->method instanceof Method)) {
            throw new \Exception('Method not initialized on Reset', 1456608512);
        }

        if ($this->method->type == Method::TYPE_EMAIL) {
            $this->sendEmail();
        } elseif ($this->method->type == Method::TYPE_PHONE) {
            $this->sendPhone();
        } else {
            throw new \Exception('Method using unknown type', 1456608781);
        }
    }

    public function sendEmail()
    {
        /**
         * @todo send email to $this->method->value with reset code
         */
    }

    public function sendPhone()
    {
        /**
         * @todo use phone service component to send notification to $this->method->value
         */
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
}