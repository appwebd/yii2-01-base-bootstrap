<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\forms\SingupForm;

/**
 * Class SingupController
 *
 * @package     Signup
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        11/1/18 10:33 PM
 * @version     1.0
 */
class SingupController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_INDEX => ['GET','POST'],
                ],
            ],
        ];
    }

     /**
     * @return string|\yii\web\Response the singup form, the singup message or
     * a redirect response
     */
    public function actionIndex()
    {

        $model = new SingupForm;
        if ($model->load(Yii::$app->request->post()) && $model->singup() !== null) {
            return $this->render('signed-up');
        }

        BaseController::bitacora(Yii::t('app', 'showing the view'), MSG_INFO);
        return $this->render(ACTION_INDEX, ['model'=> $model]);
    }
}
