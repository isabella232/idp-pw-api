<?php
namespace frontend\controllers;

use frontend\components\BaseRestController;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Site controller
 */
class SiteController extends BaseRestController
{

    public $layout = false;

    /**
     * Access Control Filter
     * REMEMBER: NEEDS TO BE UPDATED FOR EVERY ACTION
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['system-status'],
                        'roles' => ['?'],
                    ],
                ]
            ],
            'authenticator' => [
                'except' => ['system-status'] // bypass authentication for /auth/login
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'frontend\components\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            throw new UnauthorizedHttpException();
        }
        throw new MethodNotAllowedHttpException();
    }

    public function actionSystemStatus()
    {
        /**
         * Check for DB connection
         */
        try {
            \Yii::$app->db->open();
            return [];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException(
                'Unable to connect to db, error code ' . $e->getCode(),
                $e->getCode()
            );
        }

    }

}
