<?php
/**
  * Usuarios
  *
  * @package     Controller of User
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-06-16 23:03:06
  * @version     1.0
*/

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\search\UserSearch;
use app\models\queries\Common;

class UserController extends Controller
{
    const USER_ID ='user_id';

    public function beforeAction($action)
    {
        if (BaseController::checkBadAccess($action->id)) {
            return $this->redirect(['/']);
        }
        BaseController::bitacora(Yii::t('app', 'showing the view'), MSG_INFO);
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return BaseController::behaviorsCommon();
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && BaseController::transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->user_id]);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }


   /**
     * Deletes an existing row of User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (! BaseController::previousRequirementToRemoveRecords()) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);

        if ($this->referentialIntegrityCheck($model->user_id)==0) {
            $model->delete();
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} has been deleted',
                    ['id'=>$model->user_id]
                ),
                MSG_SUCCESS
            );
        } else {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record could not be deleted because it is being used in the system'
                ),
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModelUser  = new UserSearch();

        $dataProviderUser = $searchModelUser->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProviderUser->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelUser' => $searchModelUser,
                'dataProviderUser' => $dataProviderUser,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table User
     *
     * @return void
     */
    public function actionRemove()
    {

        $result = Yii::$app->request->post('selection');

        if (! BaseController::previousRequirementToRemoveRecords() ||
            ! BaseController::requestPostSeleccionItems($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nroSelections = sizeof($result);
        $deleteOK = "";
        $deleteKO = "";


        for ($i = 0; $i < $nroSelections; $i++) {
            $userId = $result[$i];

            if (($model = User::findOne($userId)) !== null) {
                if ($this->referentialIntegrityCheck($userId) <= 0) {
                    $deleteOK .= $userId . ", ";
                    $model->delete();
                } else {
                    $deleteKO .= $userId. ", ";
                }
            }
        }

        BaseController::resumeOperationRemove($deleteOK, $deleteKO);

        return $this->redirect([ACTION_INDEX]);
    }

   /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && BaseController::transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->user_id]);
        }

        return $this->render(ACTION_UPDATE, [MODEL=> $model]);
    }


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id'=>$model->user_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($primaryKey)
    {
        $model = User::findOne($primaryKey);
        if ($model !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id'=>$primaryKey]),
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param $profileId Primary Key of table User
     * @return int numbers of rows in other tables with integrity referential found.
     */
    private function referentialIntegrityCheck($userId)
    {
        return common::getNroRowsForeignkey(
            'logs',
            self::USER_ID,
            $userId
        );
    }
}
