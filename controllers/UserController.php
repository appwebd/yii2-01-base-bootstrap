<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\UiComponent;
use app\models\User;
use app\models\search\UserSearch;
use app\models\queries\Common;

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
    const USER_ID ='user_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param string $action action name
     *
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
        return BaseController::behaviorsCommon();
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \yii\db\Exception
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


            if  (Common::transaction($model, 'save')) {

                $primaryKey = BaseController::stringEncode($model->user_id);
                BaseController::flashMessage(
                    Yii::t('app', 'New record saved successfully'),
                    MSG_SUCCESS
                );
                return $this->redirect([ACTION_VIEW, 'id' => $primaryKey]);
            }
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }


    /**
     * Deletes an existing row of User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key iof table user
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionDelete($id)
    {
        $id = BaseController::stringDecode($id);
        if (! BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);

        if ($this->fkCheck($model->user_id)==0) {
            if (Common::transaction($model, 'delete')) {
                BaseController::bitacoraAndFlash(
                    Yii::t(
                        'app',
                        'Record {id} has been deleted',
                        ['id'=>$model->user_id]
                    ),
                    MSG_SUCCESS
                );
            }
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
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModelUser  = new UserSearch();
        $dataProviderUser = $searchModelUser->search(
            Yii::$app->request->queryParams
        );

        $pageSize = UiComponent::pageSize();
        $dataProviderUser ->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelUser' => $searchModelUser ,
                'dataProviderUser' => $dataProviderUser,
                'pageSize' => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table User
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionRemove()
    {
        $result = Yii::$app->request->post('selection');

        if (! BaseController::okRequirements(ACTION_DELETE)
            || ! BaseController::okSeleccionItems($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nroSelections = sizeof($result);
        $deleteOk = '';
        $deleteKo = '';

        for ($counter = 0; $counter < $nroSelections; $counter++) {
            $userId = $result[$counter];

            if (($model = User::findOne($userId)) !== null) {
                if ($this->fkCheck($userId) <= 0) {
                    if (Common::transaction($model, 'delete')) {
                        $deleteOk .= $userId . ', ';
                    }
                } else {
                    $deleteKo .= $userId . ', ';
                }
            }
        }

        BaseController::summaryDisplay($deleteOk, $deleteKo);

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table user
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $id = BaseController::stringDecode($id);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())
            && Common::transaction($model, 'save')
        ) {
            $id = BaseController::stringEncode($model->user_id);
            return $this->redirect([ACTION_VIEW, 'id' => $id]);
        }

        return $this->render(ACTION_UPDATE, [MODEL=> $model]);
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
        $id = BaseController::stringDecode($id);
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
     *
     * @param integer $primaryKey primary key of table user
     *
     * @return object User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($primaryKey)
    {
        $model = User::findOne($primaryKey);
        if ($model !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t(
                'app',
                'The requested page does not exist {id}',
                [
                    'id'=>$primaryKey
                ]
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
}
