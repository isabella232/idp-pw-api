<?php
namespace common\models;

use common\helpers\Utils;
use common\helpers\ZxcvbnPasswordValidator;
use Sil\IdpPw\Common\PasswordStore\PasswordReuseException;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class Password extends Model
{
    /** @var string */
    public $password;

    /** @var  string */
    public $employeeId;

    /** @var \Sil\IdpPw\Common\PasswordStore\PasswordStoreInterface */
    public $passwordStore;

    /** @var array */
    public $config;

    /** @var common\models\User **/
    public $user;

    public function init()
    {
        $this->passwordStore = \Yii::$app->passwordStore;
        $this->config = \Yii::$app->params['password'];
    }

    public function rules()
    {
        return [
            [
                'password', 'match', 'pattern' => $this->config['minLength']['phpRegex'],
                'skipOnError' => false,
                'message' => \Yii::t(
                    'app',
                    'Your password does not meet the minimum length of {minLength} (code 100)',
                    ['minLength' => $this->config['minLength']['value']]
                ),
                'when' => function() { return $this->config['minLength']['enabled']; }
            ],
            [
                'password', 'match', 'pattern' => $this->config['maxLength']['phpRegex'],
                'skipOnError' => false,
                'message' => \Yii::t(
                    'app',
                    'Your password exceeds the maximum length of {maxLength} (code 110)',
                    ['maxLength' => $this->config['maxLength']['value']]
                ),
                'when' => function() { return $this->config['maxLength']['enabled']; }
            ],
            [
                'password', 'match', 'pattern' => $this->config['minNum']['phpRegex'],
                'skipOnError' => false,
                'message' => \Yii::t(
                    'app',
                    'Your password must contain at least {minNum} numbers (code 120)',
                    ['minNum' => $this->config['minNum']['value']]
                ),
                'when' => function() { return $this->config['minNum']['enabled']; }
            ],
            [
                'password', 'match', 'pattern' => $this->config['minUpper']['phpRegex'],
                'skipOnError' => false,
                'message' => \Yii::t(
                    'app',
                    'Your password must contain at least {minUpper} upper case letters (code 130)',
                    ['minUpper' => $this->config['minUpper']['value']]
                ),
                'when' => function() { return $this->config['minUpper']['enabled']; }
            ],
            [
                'password', 'match', 'pattern' => $this->config['minSpecial']['phpRegex'],
                'skipOnError' => false,
                'message' => \Yii::t(
                    'app',
                    'Your password must contain at least {minSpecial} special characters (code 140)',
                    ['minSpecial' => $this->config['minSpecial']['value']]
                ),
                'when' => function() { return $this->config['minSpecial']['enabled']; }
            ],
            [
                'password', ZxcvbnPasswordValidator::className(), 'minScore' => $this->config['zxcvbn']['minScore'],
                'skipOnError' => true,
                'message' => \Yii::t(
                    'app',
                    'Your password does not meet the minimum strength of {minScore} (code 150)',
                    ['minScore' => $this->config['zxcvbn']['minScore']]
                ),
                'when' => function() { return $this->config['zxcvbn']['enabled']; }
            ],
            [
                'password', 'validateNotUserAttributes',
                'params'=>['first_name', 'last_name', 'idp_username', 'email'],
                'skipOnError' => false,
            ],
        ];
    }

    public function validateNotUserAttributes($attribute, $params=null)
    {
        /* Ensure the password instance has a user attribute */
        if (!isset($this->user)) {

            /* Log error */
            $log = [
                'status' => 'error',
                'error' => 'No User instance has been assigned to the new password. ' .
                    "Cannot validate it against the user's attributes.",
            ];
            \Yii::error($log);

            /* Throw exception based on exception type */
            throw new ServerErrorHttpException(
                \Yii::t(
                    'app',
                    'Unable to update password. Please contact support.'
                ),
                1511195430
            );
        }
        $userAttributeLabels = $this->user->attributeLabels();
        $labelList = [];
        foreach ($params as $disallowedAttribute) {
            $labelList[] = $userAttributeLabels[$disallowedAttribute];
        }

        foreach ($params as $disallowedAttribute) {
            if (mb_strpos(mb_strtolower($this->{$attribute}),
                mb_strtolower($this->user->$disallowedAttribute)) !== false) {
                $this->addError($attribute, sprintf(
                    'Your password may not contain any of these: %s (code 180)',
                    join(', ', $labelList)
                ));
            }
        }
    }

    /**
     * Shortcut method to initialize a Password object
     * @param string $employeeId
     * @param string $newPassword
     * @return Password
     */
    public static function create($employeeId, $newPassword)
    {
        $password = new Password();
        $password->password = $newPassword;
        $password->employeeId = $employeeId;

        return $password;
    }

    /**
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function save()
    {

        if ( ! $this->validate()) {
            $errors = join(', ', $this->getErrors('password'));
            \Yii::warning([
                'action' => 'save password',
                'status' => 'error',
                'employee_id' => $this->employeeId,
                'error' => $this->getErrors('password'),
            ]);
            throw new BadRequestHttpException('New password validation failed: ' . $errors);
        }
        
        $log = [
            'action' => 'save password',
            'employee_id' => $this->employeeId,
        ];

        /*
         * If validation fails, return just the first error
         */
        if ( ! $this->validate()) {
            $errors = $this->getFirstErrors();
            $log['status'] = 'error';
            $log['error'] = $errors;
            \Yii::error($log);
            throw new BadRequestHttpException($errors[0], 1463164336);
        }

        /*
         * Update password
         */
        try {
            $this->passwordStore->set($this->employeeId, $this->password);
            $log['status'] = 'success';
            \Yii::warning($log);
        } catch (\Exception $e) {
            /*
             * Log error
             */
            $log['status'] = 'error';
            $log['error'] = $e->getMessage();
            $previous = $e->getPrevious();
            if ($previous instanceof \Exception) {
                $log['previous'] = [
                    'code' => $previous->getCode(),
                    'message' => $previous->getMessage(),
                ];
            }

            /*
             * Throw exception based on exception type
             */
            if ($e instanceof  PasswordReuseException) {
                \Yii::warning($log);
                throw new BadRequestHttpException(
                    \Yii::t(
                        'app',
                        'Unable to update password. ' .
                            'If this password has been used before please use something different.'
                    ),
                    1469194882
                );
            } else {
                \Yii::error($log);
                throw new ServerErrorHttpException(
                    \Yii::t(
                        'app',
                        'Unable to update password, please wait a minute and try again. If this problem ' .
                            'persists, please contact support.'
                    ), 
                    1463165209
                );
            }

        }
    }


}
