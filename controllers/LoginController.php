<?php
/**
  * Login process
  *
  * @package     Controller of Login (using table user)
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-06-16 23:03:06
  * @version     1.0
*/

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\LoginForm;

class LoginController extends Controller
{
    const LOGOUT               = 'logout';
    const ACTION_RESET_REQUEST = 'Resetrequest';

    public function beforeAction($action)
    {
        BaseController::bitacora(Yii::t('app', 'showing the view'), MSG_INFO);
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ACTION_INDEX, self::LOGOUT, self::ACTION_RESET_REQUEST],
                'rules' => [
                    [
                        ALLOW => true,
                        ACTIONS => [ACTION_INDEX,self::ACTION_RESET_REQUEST],
                        ROLES => ['?'],
                    ],
                    [
                        ALLOW => true,
                        ACTIONS => [self::LOGOUT],
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                ACTIONS => [
                    ACTION_INDEX => ['get','post'],
                    self::ACTION_RESET_REQUEST => ['get'],
                    self::LOGOUT => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response the login form or a redirect response
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render(ACTION_INDEX, [ MODEL => $model,]);
    }

    /**
     * @return \yii\web\Response a redirect response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


//  --------------------------------------------------------------------------------------------------
    /**
     * @return string|\yii\web\Response the confirmation failure message or a
     * redirect response
     */
    public function actionConfirmEmail($token)
    {
        if (Yii::$app->session->hasFlash('user-confirmed-email')) {
            return $this->render('confirmed-email');
        }

        $user = User::find()
            ->emailConfirmationToken($token)
            ->one();

        if ($user !== null && $user->confirmEmail()) {
            Yii::$app->session->setFlash('user-confirmed-email');
            return $this->refresh();
        }

        return $this->render('email-confirmation-failed');
    }

    /**
     * @return string|\yii\web\Response the form to request a password reset or
     * a redirect response
     */
    public function actionResetrequest()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            return $this->render('requested-password-reset');
        }

        return $this->render('request-password-reset', [MODEL=> $model,]);
    }

    /**
     * @return string|\yii\web\Response the form to reset the password or a
     * redirect response
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            return $this->render('password-was-reset');
        }

        return $this->render('reset-password', [MODEL=> $model]);
    }
}
