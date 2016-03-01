<?php
namespace common\helpers;

use yii\base\Security;
use yii\web\ServerErrorHttpException;

class Utils
{

    const DT_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param integer|null $timestamp
     * @return string
     */
    public static function getDatetime($timestamp=null)
    {
        $timestamp = $timestamp ?: time();

        return date(self::DT_FORMAT,$timestamp);
    }

    /**
     * @param integer|null $timestamp
     * @return string
     */
    public static function getIso8601($timestamp=null)
    {
        $timestamp = $timestamp ?: time();
        return date('c', strtotime($timestamp));
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length=32)
    {
        $security = new Security();
        return $security->generateRandomString($length);
    }

    /**
     * Utility function to extract attribute values from SAML attributes and
     * return as a simple array
     * @param $attributes array the SAML attributes returned
     * @param $map array configuration map of attribute names with field and element values
     * @return array
     */
    public static function extractSamlAttributes($attributes, $map)
    {
        $attrs = [];

        foreach($map as $attr => $details) {
            if(isset($details['element'])) {
                if(isset($attributes[$details['field']][$details['element']])) {
                    $attrs[$attr] = $attributes[$details['field']][$details['element']];
                }
            } else {
                if(isset($attributes[$details['field']])) {
                    $attrs[$attr] = $attributes[$details['field']];
                }
            }
        }

        return $attrs;
    }

    /**
     * Check if given array of $attributes includes all keys from $map
     * @param array $attributes
     * @param array $map
     * @throws \Exception
     */
    public static function assertHasRequiredSamlAttributes($attributes, $map)
    {
        foreach ($map as $key => $value) {
            if ( ! array_key_exists($key, $attributes)) {
                throw new \Exception(sprintf('SAML attributes missing attribute: %s', $key), 1454436522);
            }
        }
    }

    /**
     * @param array $array
     * @param string $key
     * @return bool
     */
    public static function isArrayEntryTruthy($array, $key)
    {
        return (is_array($array) && isset($array[$key]) && $array[$key]);
    }

    /**
     * Check if user is logged in and if so return the identity model
     * @return null|\common\models\User
     */
    public static function getCurrentUser()
    {
        if(\Yii::$app->user && !\Yii::$app->user->isGuest) {
            return \Yii::$app->user->identity;
        }
        return null;
    }

    /**
     * @param string $phone
     * @return string
     */
    public static function maskPhone($phone)
    {
        /**
         * @todo mask phone number to something like "+77 #########234"
         */
        return $phone;
    }

    /**
     * @param string $email
     * @return string
     */
    public static function maskEmail($email)
    {
        /**
         * @todo mask email to something like "ab******@s**.org"
         */
        return $email;
    }

    /**
     * @return array
     * @throws ServerErrorHttpException
     */
    public static function getFrontendConfig()
    {
        $params = \Yii::$app->params;

        $config = [];

        $config['gaTrackingId'] = $params['gaTrackingId'];
        $config['support'] = $params['support'];
        $config['recaptchaKey'] = $params['recaptcha']['siteKey'];
        $config['password'] = [];

        $passwordRuleFields = [
            'minLength', 'maxLength', 'minNum', 'minUpper', 'minSpecial'
        ];

        foreach($passwordRuleFields as $rule) {
            if (empty($params['password'][$rule])) {
                throw new ServerErrorHttpException('Missing configuration for '.$rule);
            }
            $config['password'][$rule]['value'] = $params['password'][$rule]['value'];
            $config['password'][$rule]['regex'] = $params['password'][$rule]['jsRegex'];
        }

        $config['password']['blacklist'] = $params['password']['blacklist'];
        $config['password']['zxcvbn'] = $params['password']['zxcvbn'];

        return $config;
    }

}