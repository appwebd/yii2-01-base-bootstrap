<?php

namespace app\controllers;

use app\components\UiComponent;
use app\models\queries\Common;
use app\models\search\UserSearch;
use app\models\User;
use Yii;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UserController
 *
 * @category  Controller
 * @package   User
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:12 PM
 * @php       version 7.2
 */
class UserController extends Controller
{
    const USER_ID = 'user_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action name
     *
     * @return mixed \yii\web\Response
     * @throws BadRequestHttpException
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
        return BaseController::behaviorsCommon();
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->post('User');
            $model->email_is_verified = false;
            $model->email_confirmation_token = null;
            $model->setPassword($request['password']);
            $model->generateAuthKey();
            $model->ipv4_address_last_login = Yii::$app->getRequest()->getUserIP();

            $model->generateEmailConfirmationToken(true);
            $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL => $model]);
    }

    /**
     * @param object $model
     * @return bool|Response
     * @throws Exception
     */
    private function saveRecord($model)
    {
        try {
            $status = Common::transaction($model, 'save');
            BaseController::saveReport($status);
            if ($status) {
                $primary_key = BaseController::stringEncode($model->user_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primary_key]);
            }
        } catch (Exception $exception_error) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [
                        MODULE => 'app\controllers\UserController::saveRecord',
                        ERROR => $exception_error
                    ]
                ),
                MSG_ERROR
            );
            throw $exception_error;
        }
        return false;
    }

    /**
     * Deletes an existing row of User model. If deletion is successful,
     * the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key iof table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionDelete($id)
    {
        if (!BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($this->fkCheck($model->user_id) > 0) {
            BaseController::deleteReport(2);
            return $this->redirect([ACTION_INDEX]);
        }

        try {
            $status = Common::transaction($model, ACTION_DELETE);
            BaseController::deleteReport($status);
        } catch (\Exception $error_exception) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    TRANSACTION_MODULE,
                    [
                        ERROR => $error_exception,
                        METHOD => ACTION_DELETE,
                        MODULE => 'app\controllers\UserController::actionDelete',
                    ]
                ),
                MSG_ERROR
            );
        }
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $primary_key primary key of table user
     *
     * @return object User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($primary_key)
    {
        $primary_key = BaseController::stringDecode($primary_key);
        $model = User::findOne($primary_key);
        if ($model !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t(
                'app',
                'The requested page does not exist {id}',
                ['id' => $primary_key]
            ),
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException(
            Yii::t(
                'app',
                'The requested page does not exist.'
            )
        );
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param integer $userId int Primary Key of table User
     *
     * @return integer numbers of rows in other tables (integrity referential)
     */
    private function fkCheck($userId)
    {
        return Common::getNroRowsForeignkey(
            'logs',
            self::USER_ID,
            $userId
        );
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $searchmodel_user = new UserSearch();
        $dataprovide_user = $searchmodel_user->search(
            Yii::$app->request->queryParams
        );

        $page_size = UiComponent::pageSize();
        $dataprovide_user->pagination->pageSize = $page_size;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelUser' => $searchmodel_user,
                'dataProviderUser' => $dataprovide_user,
                'pageSize' => $page_size
            ]
        );
    }

    /**
     * Delete many records of this table User
     *
     * @return mixed
     * @throws Exception
     */
    public function actionRemove()
    {
        $result = Yii::$app->request->post('selection');

        if (!BaseController::okRequirements(ACTION_DELETE)
            || !BaseController::okSeleccionItems($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nro_selections = sizeof($result);
        $delete_ok = '';
        $delete_ko = '';

        for ($counter = 0; $counter < $nro_selections; $counter++) {
            try {
                $user_id = $result[$counter];
                if (($model = User::findOne($user_id)) !== null) {
                    if ($this->fkCheck($user_id) == 0) {
                        if (Common::transaction($model, ACTION_DELETE)) {
                            $delete_ok .= $user_id . ', ';
                        } else {
                            $delete_ko .= $user_id . ', ';
                        }
                    } else {
                        $delete_ko .= $user_id . ', ';
                    }
                }
            } catch (\Exception $exception) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [
                            MODULE => 'app\controllers\UserController::actionRemove',
                            ERROR => $exception
                        ]
                    ),
                    MSG_ERROR
                );
            }

        }

        BaseController::summaryDisplay($delete_ok, $delete_ko);

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->saveRecord($model);
        }

        return $this->render(ACTION_UPDATE, [MODEL => $model]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id primary key of table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id' => $model->user_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
