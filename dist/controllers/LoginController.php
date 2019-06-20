<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class LoginController
 *
 * @package     login
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        11/1/18 10:07 PM
 * @version     1.0
 */
class LoginController extends Controller
{
    const LOGOUT = 'logout';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [ACTION_INDEX, self::LOGOUT],
                'rules' => [
                    [
                        ALLOW => true,
                        ACTIONS => [ACTION_INDEX],
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
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_INDEX => ['get', 'post'],
                    self::LOGOUT => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string|Response the login form or a redirect response
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

        return $this->render(ACTION_INDEX, [MODEL => $model,]);
    }

    /**
     * @return Response a redirect response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * @param $token string encoded of email confirmation token
     * @return string|Response the confirmation failure message or a
     * redirect response
     */
    public function actionConfirmemail($token)
    {

        $token = BaseController::stringDecode($token);
        $model = User::find()->emailConfirmationToken($token)->one();

        if ($model !== null && LoginForm::removeTokenEmail($model->user_id)) {
            Yii::$app->getUser()->login($model);
            return $this->goHome();
        }

        return $this->render('email-confirmation-failed');
    }
}
