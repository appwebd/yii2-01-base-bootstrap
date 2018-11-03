<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\forms\ContactForm;

/**
 * Class SiteController
 *
 * @package     Ui
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        11/1/18 10:31 PM
 * @version     1.0
 */
class SiteController extends Controller
{
    const STR_ABOUT         = 'about';
    const STR_CONTACT       = 'contact';
    const STR_COOKIE_POLICY = 'cookiePolicy';
    const USER_ID_VISIT     = 1;

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
                STR_CLASS => AccessControl::className(),
                'only' => [self::STR_ABOUT,
                           self::STR_CONTACT,
                           self::STR_COOKIE_POLICY,
                           self::STR_CONTACT
                        ],
                'rules' => [
                    [
                        'actions'=>[self::STR_ABOUT,
                                    self::STR_CONTACT,
                                    self::STR_COOKIE_POLICY,
                                    ACTION_INDEX
                                ],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs'=>[
                STR_CLASS=>VerbFilter::className(),
                'actions' =>[
                    ACTION_INDEX=>['get']
                ]
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            ERROR => [
                STR_CLASS => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                STR_CLASS => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render(self::STR_ABOUT);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash(
                    SUCCESS,
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );
            } else {
                Yii::$app->session->setFlash(ERROR, 'There was an error sending email.');
            }
            return $this->refresh();
        }

        return $this->render(self::STR_CONTACT, ['model' => $model]);
    }

    /**
     * Displays Cookie policy page.
     *
     * @return string
     */
    public function actionCookiePolicy()
    {
        return $this->render(self::STR_COOKIE_POLICY);
    }

    /**
     * Displays Error page.
     *
     * @return string
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception instanceof \yii\web\NotFoundHttpException) {
            return $this->render('404', ['message' => $exception]);
        } else {
            return $this->render(ERROR, ['exception' => $exception]);
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render(ACTION_INDEX);
    }


     /**
     * @return string|\yii\web\Response the maintenance page or a redirect
     * response if not in maintenance mode
     */
    public function actionMaintenanc()
    {
        if (empty(Yii::$app->catchAll)) {
            return $this->redirect(Yii::$app->homeUrl);
        }
        Yii::$app->response->statusCode = 503;
        return $this->render('maintenance');
    }
}
