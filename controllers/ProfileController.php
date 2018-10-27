<?php
/**
  * Profiles
  *
  * @package     Controller of Profile table
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
use app\models\Profile;
use app\models\search\ProfileSearch;
use app\models\queries\Common;

class ProfileController extends Controller
{
    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const PROFILE_ID = 'profile_id';

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
                 'only' => [
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
                     self::ACTION_TOGGLE_ACTIVE,
                    ACTION_UPDATE,
                    ACTION_VIEW
                    ],
                'rules' => [
                    [
                        ACTIONS => [
                           ACTION_CREATE,
                           ACTION_DELETE,
                           ACTION_INDEX,
                           ACTION_REMOVE,
                           self::ACTION_TOGGLE_ACTIVE,
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
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX  => ['get'],
                    ACTION_REMOVE => ['post'],
                    self::ACTION_TOGGLE_ACTIVE =>['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW   => ['get'],
                ],
            ],
        ];
    }



    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Profile();

        if ($model->load(Yii::$app->request->post()) && $this->transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->profile_id]);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

   /**
     * Deletes an existing row of Profile model.
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
        if ($this->referentialIntegrityCheck($model->profile_id)==0) {
            $model->delete();
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} has been deleted',
                    ['id'=>$model->profile_id]
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
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $profile_id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($profileId)
    {
        if (($model = Profile::findOne($profileId)) !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id'=>$profileId]),
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModelProfile  = new ProfileSearch();
        $dataProviderProfile = $searchModelProfile->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProviderProfile->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelProfile' => $searchModelProfile,
                'dataProviderProfile' => $dataProviderProfile,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table Profile
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

        $deleteOK = "";
        $deleteKO = "";
        $nroSelections = sizeof($result);
        for ($i = 0; $i < $nroSelections; $i++) {
            $profileId = $result[$i];

            if (($model = Profile::findOne($profileId)) !== null) {
                if ($this->referentialIntegrityCheck($model->profile_id) <= 0) {
                    $deleteOK .= $profileId . ", ";
                    $model->delete();
                } else {
                    $deleteKO .= $profileId . ", ";
                }
            }
        }

        BaseController::resumeOperationRemove($deleteOK, $deleteKO);

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Toggle the value active in the table Profile
     *
     * @param $id primary Key
     * @return \yii\web\Response
     */
    public function actionToggle($id)
    {

        if (!Yii::$app->request->isPost || !isset($id)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $sqlcode = "UPDATE profile SET active=not(active) WHERE profile_id = ". $id;
        if (! Yii::$app->db->createCommand($sqlcode)->execute()) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} was not possible to update the value active',
                    ['id'=>$id]
                ),
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $this->transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->profile_id]);
        }

        return $this->render(ACTION_UPDATE, [MODEL=> $model]);
    }

    /**
     * Displays a single Profile model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id'=>$model->profile_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param $profileId Primary Key of table Profile
     * @return int numbers of rows in other tables with integrity referential found.
     */
    private function referentialIntegrityCheck($profileId)
    {
        $nroRegs = common::getNroRowsForeignkey(
            'permission',
            self::PROFILE_ID,
            $profileId
        );

        return $nroRegs + common::getNroRowsForeignkey(
            'user',
            self::PROFILE_ID,
            $profileId
        );
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
                    Yii::t('app', 'new record {id}', ['id'=>$model->profile_id]),
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
