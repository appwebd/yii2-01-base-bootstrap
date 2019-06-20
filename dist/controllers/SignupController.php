<?php

namespace app\controllers;

use app\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

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
class SignupController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                STR_CLASS => AccessControl::className(),
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
                STR_CLASS => VerbFilter::className(),
                ACTIONS => [
                    ACTION_INDEX => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * @return string|Response the singup form, the singup message or
     * a redirect response
     */
    public function actionIndex()
    {

        $model = new SignupForm;
        if ($model->load(Yii::$app->request->post()) && $model->singup() !== null) {
            return $this->render('signed-up');
        }
        return $this->render(ACTION_INDEX, ['model' => $model]);
    }
}
