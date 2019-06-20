<?php
/**
 * Logs (Logs user activities)
 *
 * @package     Index of Logs
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:23:23
 * @version     1.0
 */

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Logs;
use app\models\search\LogsSearch;
use app\models\Status;
use app\models\User;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $controller_id int app\controllers\LogsController */
/* @var $dataProvider int app\controllers\LogsController */
/* @var $searchModel int app\controllers\LogsController */
/* @var $pageSize int app\controllers\LogsController */
/* @var $searchModel app\models\search\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Logs::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo UiComponent::headerAdmin(
    'record',
    $this->title,
    Yii::t('app', 'This view is the event log of the web application.'),
    'logs',
    '000',
    true
);

try {
    /** @noinspection PhpDeprecationInspection */
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            Logs::LOGS_ID,
            Logs::DATE,
            [
                ATTRIBUTE => Logs::STATUS_ID,
                FILTER => LogsSearch::getStatusListSearch(),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    $status = Status::getStatusName($model->status_id);
                    return UiComponent::badgetStatus($model->status_id, $status);
                },
            ],
            [
                ATTRIBUTE => Logs::CONTROLLER_ID,
                FILTER => LogsSearch::getControllersListSearch(),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => Logs::CONTROLLER_CONTROLLER_NAME,
            ],
            [
                ATTRIBUTE => Logs::ACTION_ID,
                FILTER => LogsSearch::getActionListSearch($controller_id),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => Logs::ACTION_ACTION_NAME,
            ],
            Logs::EVENT,
            [
                ATTRIBUTE => Logs::USER_ID,
                FILTER => LogsSearch::getUserList(),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    if (($model = User::getUsername($model->user_id)) !== null) {
                        $return = $model->username;
                    } else {
                        $return = Yii::t('app', 'Unknown');
                    }
                    return $return;
                },

            ],
        ]]);
} catch (Exception $errorException) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [MODULE => 'app\views\logs\index::gridView::widget', ERROR => $errorException]
        ),
        MSG_ERROR
    );
}

echo '<br/><br/>';
echo HTML_WEBPAGE_CLOSE;
