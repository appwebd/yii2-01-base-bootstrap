<?php
/**
 * Company
 *
 * @package   Controller
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @link      https://appwebd.github.io
 * @date      2018-08-20 16:37:24
 * @release   1.0
 */

namespace app\controllers;

use app\components\UiComponent;
use app\models\Company;
use app\models\queries\Common;
use app\models\search\CompanySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class CompanyController
 *
 * @category  Controller
 * @package   Company
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:37 AM
 * @php       version 7.2
 */
class CompanyController extends Controller
{
    const COMPANY_ID = 'company_id';
    const COMPANY_NAME = 'company_name';
    const COMPANY_AUTOCOMPLETE = 'autocomplete';
    const COMPANY_SEARCH_MODAL = 'searchmodal';
    const COMPANY_CREATE_MODAL = 'createmodal';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action
     *
     * @return mixed
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
                'class' => VerbFilter::class,
                ACTIONS => [
                    self::COMPANY_AUTOCOMPLETE => ['get'],
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * Search Company
     *
     * @param string $term pattern to search
     *
     * @return void
     */
    public function actionAutocomplete($term)
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
                             ->where("(`" . self::COMPANY_NAME . "` like '%{$query}%')")
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
     * @return mixed
     */
    public function actionSearchmodal()
    {

        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pageSize = UiComponent::pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->renderAjax(
            '_companySearch_modal',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'pageSize' => $pageSize
            ]
        );
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post())) {
            return $this->saveRecord($model);
        }

        return $this->render(ACTION_CREATE, [MODEL => $model]);
    }

    /**
     * @param object $model
     * @return bool|\yii\web\Response
     * @throws \yii\db\Exception
     */
    private function saveRecord($model)
    {
        try {
            if (Common::transaction($model, 'save')) {
                Yii::$app->session->setFlash(
                    SUCCESS,
                    Yii::t(
                        'app',
                        'Record saved successfully'
                    )
                );
                $primaryKey = BaseController::stringEncode($model->company_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primaryKey]);
            } else {
                Yii::$app->session->setFlash(
                    ERROR,
                    Yii::t(
                        'app',
                        'Error saving record'
                    )
                );
                $this->refresh();
            }
        } catch (\yii\db\Exception $e) {
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
     * Deletes an existing row of Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key of table Company
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $id = BaseController::stringDecode($id);
        if (!BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($this->fkCheck() == 0) {
            if (Common::transaction($model, 'delete')) {
                BaseController::bitacoraAndFlash(
                    Yii::t(
                        'app',
                        'Record {id} has been deleted',
                        ['id' => $model->company_id]
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $companyId company primary key
     *
     * @return string Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($companyId)
    {
        if (($model = Company::findOne($companyId)) !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id' => $companyId]),
            MSG_ERROR
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    private function fkCheck()
    {
        return 0;
    }

    /**
     * Lists all Company models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $companySearchModel = new CompanySearch();
        $dataProvider = $companySearchModel->search(Yii::$app->request->queryParams);
        $pageSize = UiComponent::pageSize();
        $dataProvider->pagination->pageSize = $pageSize;

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
        $nroSelections = sizeof($result);

        for ($i = 0; $i < $nroSelections; $i++) {
            try {
                $company_id = $result[$i];
                if (($model = Company::findOne($company_id)) !== null) {
                    if ($this->fkCheck() <= 0) {
                        if (Common::transaction($model, 'delete')) {
                            $delete_ok .= $company_id . ", ";
                        } else {
                            $delete_ko.= $company_id . ", ";
                        }
                    } else {
                        $delete_ko.= $company_id . ", ";
                    }
                }
            } catch (\Exception $exception) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [
                            MODULE => 'app\controllers\CompanyController::actionRemove',
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
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table company
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception driven in common::transaction
     */
    public function actionUpdate($id)
    {
        $id = BaseController::stringDecode($id);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            return $this->saveRecord($model);
        }

        return $this->render(ACTION_UPDATE, [MODEL => $model]);
    }

    /**
     * Displays a single Company model.
     *
     * @param integer $id primary key of table Company
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = BaseController::stringDecode($id);
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id' => $model->company_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
