<?php
/**
  * Permission
  *
  * @package     Controller of Permission table
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
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
use app\components\UiComponent;
use app\models\queries\Common;
use app\models\search\PermissionSearch;
use app\models\Action;
use app\models\Permission;

class PermissionController extends Controller
{
    const ACTION_DROPDOWN = 'actiondropdown';
    const CONTROLLER_ID   = 'controller_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action
     * @return mixed
     */
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
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post()) && Common::transaction($model, 'save')) {
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
        if (! BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);

        if (Common::transaction($model, 'delete')) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    'record {id} was deleted',
                    ['id' => $model->permission_id]
                ),
                MSG_INFO
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    public function actionActiondropdown($id)
    {
        if (Yii::$app->request->isAjax) {

            echo Common::relatedDropdownList(
                Action::class,
                self::CONTROLLER_ID,
                $id,
                'action_id',
                'action_name',
                'action_name'
            );
        }
    }

    /**
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {

        $permissionSearchModel  = new PermissionSearch();
        $dataProvider = $permissionSearchModel->search(Yii::$app->request->queryParams);

        $pageSize = UiComponent::pageSize();
        $dataProvider->pagination->pageSize=$pageSize;
        $request= Yii::$app->request->get('PermissionSearch');

        if (isset($request[self::CONTROLLER_ID])) {
            $controllerId = $request[self::CONTROLLER_ID];
        } else {
            $controllerId = null;
        }

        return $this->render(
            ACTION_INDEX,
            [
                SEARCH_MODEL => $permissionSearchModel,
                DATA_PROVIDER => $dataProvider,
                'pageSize' => $pageSize,
                self::CONTROLLER_ID => $controllerId
            ]
        );
    }

    /**
     * Delete many records of this table
     *
     * @return mixed
     */
    public function actionRemove()
    {

        $result = Yii::$app->request->post('selection');

        if (! BaseController::okRequirements(ACTION_DELETE) ||
            ! BaseController::okSeleccionItems($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $deleteOK = "";
        $deleteKO = "";
        $nroSelections = sizeof($result);
        for ($i = 0; $i < $nroSelections; $i++) {
            if (($model = Permission::findOne($result[$i])) !== null) {
                if (Common::transaction($model, 'delete')) {
                    $deleteOK .= $model->permission_id . ", ";
                } else {
                    $deleteKO .= $model->permission_id . ", ";
                }
            }
        }

        BaseController:: summaryDisplay($deleteOK, $deleteKO);

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

        if ($model->load(Yii::$app->request->post()) && Common::transaction($model, 'save')) {
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
     * @param integer $permissionId
     * @return app/models/Permission Permission the loaded model
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
}
