<?php
/**
  * Logs (user bitacora)
  *
  * @package     Controller of Logs table
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 15:34:07
  * @version     1.0
*/

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\search\ActionSearch;
use app\models\search\ControllersSearch;
use app\models\search\BlockedSearch;
use app\models\search\LogsSearch;
use app\models\search\StatusSearch;

class LogsController extends Controller
{
    const CONTROLLER_ID = 'controller_id';
    const ACTIONS  = 'actions';
    const CONTROLLERS = 'controllers';
    const BLOCKED = 'blocked';
    const STATUS = 'status';

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
                    self::ACTIONS,
                    self::BLOCKED,
                    self::CONTROLLERS,
                    ACTION_INDEX,
                    self::STATUS,
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            self::ACTIONS,
                            self::BLOCKED,
                            self::CONTROLLERS,
                            ACTION_INDEX,
                            self::STATUS,
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                ACTIONS => [
                    self::ACTIONS => ['get'],
                    self::BLOCKED => ['get'],
                    self::CONTROLLERS => ['get'],
                    ACTION_INDEX  => ['get'],
                    self::STATUS  => ['get'],
                ],
            ],
        ];
    }
    /**
     * Lists all Action models.
     * @return mixed
     */
    public function actionActions()
    {

        $logsSearchModel  = new ActionSearch();
        $dataProvider = $logsSearchModel->search(Yii::$app->request->queryParams);
        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;

        return $this->render(
            self::ACTIONS,
            [
                SEARCH_MODEL => $logsSearchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize
            ]
        );
    }

    /**
     * Lists all Blocked models.
     * @return mixed
     */
    public function actionBlocked()
    {
        
        $searchModel  = new BlockedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;

        return $this->render(
            self::BLOCKED,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE =>$pageSize
            ]
        );
    }

    /**
     * Lists all Controllers models.
     * @return mixed
     */
    public function actionControllers()
    {        
        $searchModel  = new ControllersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;

        return $this->render(
            self::CONTROLLERS,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
            ]
        );
    }

    /**
     * Lists all Logs models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $logsSearchModel  = new LogsSearch();
        $dataProvider = $logsSearchModel->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;
        $request= Yii::$app->request->get('LogsSearch');
        if (isset($request[self::CONTROLLER_ID])) {
            $controllerId = $request[self::CONTROLLER_ID];
        } else {
            $controllerId =null;
        }
        return $this->render(
            ACTION_INDEX,
            [
                SEARCH_MODEL => $logsSearchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
                self::CONTROLLER_ID => $controllerId
            ]
        );
    }
    /**
     * Lists all Status models.
     * @return mixed
     */
    public function actionStatus()
    {        
        $searchModel  = new StatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pageSize = Yii::$app->ui->pageSize();
        $dataProvider->pagination->pageSize=$pageSize;

        return $this->render(
            self::STATUS,
            [
                SEARCH_MODEL => $searchModel,
                DATA_PROVIDER => $dataProvider,
                PAGE_SIZE => $pageSize,
            ]
        );
    }
}
