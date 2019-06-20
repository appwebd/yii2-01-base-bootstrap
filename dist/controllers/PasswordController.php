<?php
/**
 * Login process
 *
 * @package     Controller of Login (using table user)
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 23:03:06
 * @version     1.0
 */

namespace app\controllers;

use app\models\forms\PasswordResetForm;
use app\models\forms\PasswordResetRequestForm;
use app\models\queries\Bitacora;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class PasswordController extends BaseController
{
    const ACTION_RESET = 'reset';
    const ACTION_NEW = 'new';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                STR_CLASS => \yii\filters\AccessControl::className(),
                'only' => [
                    ACTION_INDEX,                // Request password reset
                    self::ACTION_RESET,          // password reset
                    self::ACTION_NEW             // User authenticated with login active can change password
                ],
                'rules' => [
                    [
                        ALLOW => true,
                        ACTIONS => [
                            ACTION_INDEX,
                            self::ACTION_RESET
                        ],
                        ROLES => ['?'],
                    ],
                    [
                        ALLOW => true,
                        ACTIONS => [self::ACTION_NEW],
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                STR_CLASS => \yii\filters\VerbFilter::className(),
                ACTIONS => [
                    ACTION_INDEX => ['get', 'post'],
                    self::ACTION_RESET => ['get', 'post'],
                    self::ACTION_NEW => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * Request password reset
     * @return string|Response the login form or a redirect response
     */
    public function actionIndex()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) &&
            Yii::$app->request->isPost &&
            $model->sendEmail($model->email)) {
            return $this->render('requested-password-reset');
        }

        return $this->render(ACTION_INDEX, ['model' => $model]);
    }

    /**
     * Reset your password account
     *
     * @param string $token . Token is a cryptographed string, which must contain password_reset_token and the
     *  date / time for its validity. The token is set '' like parameter only for don't show ways to corrupt this
     * web application.
     * @return Response
     */
    public function actionReset($token = '')
    {
        $tokendecode= BaseController::stringDecode($token);
        $model = new PasswordResetRequestForm();
        try {
            if ($model->tokenIsValid($tokendecode)) {
                $this->wrongToken($token);
            }
        } catch (Exception $exception) {
            $bitacora = New Bitacora();
            $bitacora->register($exception, 'actionReset', MSG_SECURITY_ISSUE);
        }

        $userId = $model->getUserid($tokendecode);
        return $this->redirect(['password/resetpassword', 'userId' => $userId]);
    }

    /**
     * @param $token string encoded with token for change password
     * @return Response
     */
    public function wrongToken($token)
    {
        $event = Yii::t('app', 'Error, token password reset wrong {token}', ['token' => $token]);
        $bitacora = New Bitacora();
        $bitacora->register($event, 'actionReset', MSG_SECURITY_ISSUE);
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * @param $userId integer primary key of table user
     * @return mixed
     * @throws Exception
     */
    public function actionResetpassword($userId)
    {
        $model = new PasswordResetForm();
        if ($model->load(Yii::$app->request->post()) &&
            Yii::$app->request->isPost &&
            $model->passwordUpdate($model)) {
            return $this->render('password-reset-was-changed');
        }

        $model->user_id = BaseController::stringEncode($userId);
        return $this->render('password-reset', ['model' => $model]);
    }
}
