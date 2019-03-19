<?php
/**
 * Permission
 *
 * @category  Controller
 * @package   Permission
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      2018-08-09 14:26:44
 * @php       version 7.2
 */

namespace app\controllers;

use app\components\UiComponent;
use app\models\Action;
use app\models\Permission;
use app\models\queries\Common;
use app\models\search\PermissionSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PermissionController extends Controller
{
    const ACTION_DROPDOWN = 'actiondropdown';
    const CONTROLLER_ID = 'controller_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action
     * @return mixed \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
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
     *
     * @return array
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
                    self::ACTION_DROPDOWN => ['get'],
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get', 'post'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Permission model. If creation is successful,
     * the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post())) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL => $model]);
    }

    /**
     * @param object $model
     * @return bool|\yii\web\Response
     */
    private function saveRecord($model)
    {
        try {
            $status = Common::transaction($model, 'save');
            BaseController::saveReport($status);
            if ($status) {
                $primary_key = BaseController::stringEncode($model->permission_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primary_key]);
            } else {
                $this->refresh();
            }
        } catch (\Exception $e) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [
                        MODULE => 'app\models\queries\Common::transaction method: save',
                        ERROR => $e
                    ]
                ),
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Deletes an existing row of Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key of table permission
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        try {
            $status = Common::transaction($model, 'delete');
            BaseController::deleteReport($status);
        } catch (\Exception $error_exception) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    TRANSACTION_MODULE,
                    [
                        METHOD => 'delete',
                        MODULE => 'app\controllers\PermissionController::actionDelete',
                    ]
                ),
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $permissionId primary key of table permission
     *
     * @return object Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permissionId)
    {

        $permissionId = BaseController::stringDecode($permissionId);
        if (($model = Permission::findOne($permissionId)) !== null) {
            return $model;
        }
        BaseController::bitacora(
            Yii::t(
                'app',
                'The requested page does not exist {id}',
                [
                    'id' => $permissionId
                ]
            ),
            MSG_ERROR
        );
        throw new NotFoundHttpException(
            Yii::t(
                'app',
                'The requested page does not exist.'
            )
        );
    }

    /**
     * Select object, loaded via ajax method
     *
     * @param integer $id primary key of table relations
     *
     * @return void
     */
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
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $searchmodel = new PermissionSearch();
        $dataprovider = $searchmodel->search(Yii::$app->request->queryParams);

        $page_size = UiComponent::pageSize();
        $dataprovider->pagination->pageSize = $page_size;
        $request = Yii::$app->request->get('PermissionSearch');

        if (isset($request[self::CONTROLLER_ID])) {
            $controller_id = $request[self::CONTROLLER_ID];
        } else {
            $controller_id = null;
        }

        return $this->render(
            ACTION_INDEX,
            [
                SEARCH_MODEL => $searchmodel,
                DATA_PROVIDER => $dataprovider,
                'pageSize' => $page_size,
                self::CONTROLLER_ID => $controller_id
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

        if (!BaseController::okRequirements(ACTION_DELETE) ||
            !BaseController::okSeleccionItems($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $delete_ok = "";
        $delete_ko = "";
        $nro_selections = sizeof($result);
        for ($i = 0; $i < $nro_selections; $i++) {
            if (($model = Permission::findOne($result[$i])) !== null) {
                try {
                    if (Common::transaction($model, 'delete')) {
                        $delete_ok .= $model->permission_id . ", ";
                    } else {
                        $delete_ko .= $model->permission_id . ", ";
                    }
                } catch (\Exception $exception) {
                    BaseController::bitacora(
                        Yii::t(
                            'app',
                            ERROR_MODULE,
                            [
                                MODULE => 'app\controllers\PermissionController::actionRemove',
                                ERROR => $exception
                            ]
                        ),
                        MSG_ERROR
                    );
                }

            }
        }

        BaseController:: summaryDisplay($delete_ok, $delete_ko);

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table permission
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            try {
                return $this->saveRecord($model);
            } catch (\Exception $exception) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [
                            MODULE => 'app\controllers\PermissionController::actionUpdate',
                            ERROR => $exception
                        ]
                    ),
                    MSG_ERROR
                );
            }
        }

        return $this->render(ACTION_UPDATE, [MODEL => $model]);
    }

    /**
     * Displays a single Permission model.
     *
     * @param integer $id primarykey of table permission
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id' => $model->permission_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
