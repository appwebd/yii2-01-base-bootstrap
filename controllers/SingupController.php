<?php
/**
  * Singup process
  *
  * @package     Controller of SingUp process
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

use app\models\forms\SignupForm;

class SingupController extends Controller
{
    /**
     * Defaults actions
     *
     * @return void
     */
    public function actions()
    {
        return [
            // declares "error" action using a class name
            ERROR => 'yii\web\ErrorAction',
        ];
    }

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
                'only' => [ACTION_INDEX],
                'rules' => [
                    [
                        ACTIONS => [ACTION_INDEX],
                        ALLOW => true,
                        ROLES => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                ACTIONS => [
                    ACTION_INDEX => ['GET','POST'],
                ],
            ],
        ];
    }

     /**
     * @return string|\yii\web\Response the signup form, the signup message or
     * a redirect response
     */
    public function actionIndex()
    {

        $model = new SignupForm;
        if ($model->load(Yii::$app->request->post()) && $model->signup() !== null) {
            return $this->render('signed-up');
        }

        return $this->render(ACTION_INDEX, ['model'=> $model]);
    }
}
