<?php
/**
  * Permission
  *
  * @package     Controller of Permission table
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-08-09 14:26:44
  * @version     1.0
*/

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier;
use app\models\Action;
use app\models\Permission;
use app\models\search\PermissionSearch;
use app\models\queries\Common;

class PermissionController extends Controller
{
    const ACTION_DROPDOWN = 'actiondropdown';
    const CONTROLLER_ID   = 'controller_id';

    /**
     * Defaults actions
     *
     * @return void
     */
    public function actions()
    {
        return [
            ERROR => [
                STR_CLASS => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action
     * @return void
     */
    public function beforeAction($action)
    {
        if (BaseController::checkBadAccess($action->id)) {
            return $this->redirect(['/']);
        }
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
                'only' => [
                    self::ACTION_DROPDOWN,
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
                    ACTION_UPDATE,
                    ACTION_VIEW
                ],
                'rules' => [
                    [
                        ACTIONS => [
                           self::ACTION_DROPDOWN,
                           ACTION_CREATE,
                           ACTION_DELETE,
                           ACTION_INDEX,
                           ACTION_REMOVE,
                           ACTION_UPDATE,
                           ACTION_VIEW
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                ACTIONS => [
                    self::ACTION_DROPDOWN=>['get'],
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX  => ['get','post'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW   => ['get'],
                ],
            ],
        ];
    }

    /**
     *
     */
    public function actionActiondropdown($id)
    {
        echo Yii::$app->ui->relatedDropdownList(
            Action::className(),
            self::CONTROLLER_ID,
            $id,
            'action_id',
            'action_name',
            'action_name'
        );
    }

     /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            BaseController::bitacora(
                Yii::t('app', 'new record {id}', ['id'=>$model->permission_id]),
                MSG_INFO
            );
            return $this->redirect([ACTION_VIEW, 'id' => $model->permission_id]);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

   /**
     * Deletes an existing row of Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (! $this->previousRequirementToRemoveRecords()) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);

        if ($this->referentialIntegrityCheck($model->permission_id)==0) {
            $model->delete();
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} has been deleted',
                    ['id'=>$model->permission_id]
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
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel  = new PermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;
        $request= Yii::$app->request->get('PermissionSearch');

        if (isset($request[self::CONTROLLER_ID])) {
            $controllerId = $request[self::CONTROLLER_ID];
        } else {
            $controllerId =null;
        }
        return $this->render(
            ACTION_INDEX,
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'pageSize' => $pageSize,
                self::CONTROLLER_ID=> $controllerId
            ]
        );
    }

    /**
     * Delete many records of this table
     *
     * @return void
     */
    public function actionRemove()
    {

        if (!Yii::$app->request->isPost) {
            return $this->redirect([ACTION_INDEX]);
        }

        $result = Yii::$app->request->post('selection');
        if (!isset($result)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $deleteOK = "";
        $deleteKO = "";
        $nroSelections    = sizeof($result);

        for ($i = 0; $i < $nroSelections; $i++) {
            if (($model = Permission::findOne($result[$i])) !== null) {
                $nroRegs = 0;

                if ($nroRegs <= 0) {
                    $deleteOK .= $model->permission_id . ", ";
                    $model->delete();
                } else {
                    $deleteKO .= $model->permission_id . ", ";
                }
            }
        }

        BaseController:: resumeOperationRemove($deleteOK, $deleteKO);

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $this->transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->permission_id]);
        }

        return $this->render(ACTION_UPDATE, [MODEL=> $model]);
    }

    /**
     * Displays a single Permission model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id'=>$model->permission_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $permission_id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permissionId)
    {
        if (($model = Permission::findOne($permissionId)) !== null) {
            return $model;
        }
        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id'=>$permissionId]),
            MSG_ERROR
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    /**
     * @param $model
     * @return bool Success o failed to create/update a $model in this view
     * @throws \yii\db\Exception
     */
    private function transaction($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                $transaction->commit();
                BaseController::bitacora(
                    Yii::t('app', 'new record {id}', ['id'=>$model->permission_id]),
                    MSG_INFO
                );
                return true;
            }
            $transaction->rollBack();
        } catch (\Exception $errorException) {
            BaseController::bitacoraAndFlash(
                Yii::t('app', 'Failed to create a new record'),
                MSG_ERROR
            );
            $transaction->rollBack();
            throw $errorException;
        }

        return false;
    }

}
