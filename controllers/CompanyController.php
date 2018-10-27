<?php
/**
  * Company
  *
  * @package     Controller of Company table
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-08-20 16:37:24
  * @version     1.0
*/

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Company;
use app\models\search\CompanySearch;
use yii\helpers\Json;

class CompanyController extends Controller
{
    const COMPANY_ID                = 'company_id';
    const COMPANY_NAME              = 'company_name';
    const COMPANY_AUTOCOMPLETE      = 'companyautocomplete';
    const COMPANY_SEARCH_MODAL      = 'companysearchmodal';
    const COMPANY_CREATE_MODAL      = 'companycreatemodal';

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
                    self::COMPANY_AUTOCOMPLETE,
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
                           self::COMPANY_AUTOCOMPLETE,
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
                    self::COMPANY_AUTOCOMPLETE=>['get'],
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX  => ['get'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW   => ['get'],
                ],
            ],
        ];
    }

     /**
     * Search Company
     * @param string $term pattern to search
     * @return JSON
     */
    public function actionCompanyautocomplete($term)
    {
        if (Yii::$app->request->isAjax) {
            $results = [];

            if (is_numeric($term)) {
                $model = Company::findOne([self::COMPANY_ID => $term]);

                if ($model) {
                    $results[] = [
                        self::COMPANY_ID => $model[self::COMPANY_ID],
                        LABEL => $model[self::COMPANY_NAME] . '|' . $model[self::COMPANY_ID],
                    ];
                }
            } else {
                $query = addslashes($term);
                foreach (Company::find()
                                  ->where("(`" . self::COMPANY_NAME ."` like '%{$query}%')")
                                  ->all() as $model) {
                    $results[] = [
                        self::COMPANY_ID => $model[self::COMPANY_ID],
                        LABEL => $model[self::COMPANY_NAME] . '|' . $model[self::COMPANY_ID],
                    ];
                }
            }

            echo Json::encode($results);
        }
    }

    /**
    * Search modal view of Company
    *
    * @param intenger company_id integer primary key of table company
    * @return void
    */
    public function actionCompanysearchmodal()
    {

        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->renderAjax(
            '_companySearch_modal',
            [
                'searchModel' => $searchModel,
                'dataProvider'=>$dataProvider,
                'pageSize' => $pageSize
            ]
        );
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post()) && $this->transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->company_id]);
        }

        return $this->render(ACTION_CREATE, [MODEL=> $model]);
    }

   /**
     * Deletes an existing row of Company model.
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
        if ($this->referentialIntegrityCheck()==0) {
            $model->delete();
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} has been deleted',
                    ['id'=>$model->company_id]
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {

        $companySearchModel  = new CompanySearch();
        $dataProvider = $companySearchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                SEARCH_MODEL => $companySearchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table Company
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
            $companyId = $result[$i];

            if (($model = Company::findOne($companyId)) !== null) {
                if ($this->referentialIntegrityCheck() <= 0) {
                    $deleteOK .= $companyId . ", ";
                    $model->delete();
                } else {
                    $deleteKO .= $companyId . ", ";
                }
            }
        }

        BaseController::resumeOperationRemove($deleteOK, $deleteKO);

        return $this->redirect([ACTION_INDEX]);
    }


    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $company_id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($companyId)
    {
        if (($model = Company::findOne($companyId)) !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id'=>$companyId]),
            MSG_ERROR
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $this->transaction($model)) {
            return $this->redirect([ACTION_VIEW, 'id' => $model->company_id]);
        }

        return $this->render(ACTION_UPDATE, [MODEL=> $model]);
    }


    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id'=>$model->company_id]),
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
    private function referentialIntegrityCheck()
    {

        return 0;
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
                    Yii::t('app', 'new record {id}', ['id'=>$model->company_id]),
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
