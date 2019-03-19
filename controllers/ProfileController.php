<?php
/**
 * Profiles
 *
 * @category  Controller
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      2018-08-09 14:26:44
 */

namespace app\controllers;

use app\components\UiComponent;
use app\models\Profile;
use app\models\queries\Common;
use app\models\search\ProfileSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ProfileController
 *
 * @category  Controller
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      11/1/18 4:25 PM
 * @php       version 7.2
 */
class ProfileController extends Controller
{
    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const PROFILE_ID = 'profile_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action
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
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get'],
                    ACTION_REMOVE => ['post'],
                    self::ACTION_TOGGLE_ACTIVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {

        $model = new Profile();
        if ($model->load(Yii::$app->request->post())) {
            $this->saveRecord($model);
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
            $status = Common::transaction($model, 'save');
            BaseController::saveReport($status);
            if ($status) {
                $primary_key = BaseController::stringEncode($model->profile_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primary_key]);
            } else {
                $this->refresh();
            }
        } catch (\yii\db\Exception $error_exception) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [
                        MODULE => 'app\models\queries\Common::transaction method: save',
                        ERROR => $error_exception
                    ]
                ),
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Deletes an existing row of Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key of table profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionDelete($id)
    {
        if (!BaseController::okRequirements(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);

        if ($this->fkCheck($model->profile_id)) {
            BaseController::deleteReport(2);
            return $this->redirect([ACTION_INDEX]);
        }
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
                        MODULE => 'app\controllers\UserController::actionDelete',
                    ]
                ),
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $profileId primary key of table Profile
     *
     * @return object Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($profile_id)
    {
        $profile_id = BaseController::stringDecode($profile_id);
        if (($model = Profile::findOne($profile_id)) !== null) {
            return $model;
        }

        BaseController::bitacora(
            Yii::t('app', 'The requested page does not exist {id}', ['id' => $profile_id]),
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param integer $profileId integer Primary Key of table Profile
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    private function fkCheck($profileId)
    {
        $nro_regs = Common::getNroRowsForeignkey(
            'permission',
            self::PROFILE_ID,
            $profileId
        );

        return $nro_regs + Common::getNroRowsForeignkey(
                'user',
                self::PROFILE_ID,
                $profileId
            );
    }

    /**
     * Lists all Profile models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $sm_profile = new ProfileSearch();
        $dp_profile = $sm_profile->search(Yii::$app->request->queryParams);

        $page_size = UiComponent::pageSize();
        $dp_profile->pagination->pageSize = $page_size;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelProfile' => $sm_profile,
                'dataProviderProfile' => $dp_profile,
                'pageSize' => $page_size
            ]
        );
    }

    /**
     * Delete many records of this table Profile
     *
     * @return mixed
     * @throws \yii\db\Exception
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
            try {
                $profile_id = $result[$i];
                if (($model = Profile::findOne($profile_id)) !== null) {
                    $profile_id = $model->profile_id;
                    if ($this->fkCheck($profile_id) <= 0) {
                        if (Common::transaction($model, 'delete')) {
                            $delete_ok .= $profile_id . ", ";
                        } else {
                            $delete_ko .= $profile_id . ", ";
                        }
                    } else {
                        $delete_ko .= $profile_id . ", ";
                    }
                }
            } catch (\Exception $exception) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [
                            MODULE => 'app\controllers\ProfileController::actionRemove',
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
     * Toggle the value active in the table Profile
     *
     * @param $id integer primary Key of table profile
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionToggle($id)
    {

        if (!Yii::$app->request->isPost || !isset($id)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $sqlcode = "UPDATE profile SET active=not(active) WHERE profile_id = " . $id;

        if (!Common::sqlCreateCommand($sqlcode)) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Record {id} was not possible to update the value active',
                    ['id' => $id]
                ),
                MSG_ERROR
            );
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table Profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            return $this->saveRecord($model);
        }

        return $this->render(ACTION_UPDATE, [MODEL => $model]);
    }

    /**
     * Displays a single Profile model.
     *
     * @param integer $id primary key of table Profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        BaseController::bitacora(
            Yii::t('app', 'view record {id}', ['id' => $model->profile_id]),
            MSG_INFO
        );
        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
