<?php
/**
  * Logs (user bitacora)
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
use yii\grid\GridView;
use app\models\search\LogsSearch;
use app\models\Logs;
use app\models\Status;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
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

echo GridView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'layout'=>'{items}{summary}{pager}',
'filterSelector' => 'select[name="per-page"]',
'tableOptions' =>[STR_CLASS => GRIDVIEW_CSS],
'columns' => [
    Logs::LOGS_ID,
    Logs::DATE,
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Logs::STATUS_ID,
        FILTER => LogsSearch::getStatusListSearch(),
        VALUE => function ($model) {
            $status = Status::getStatusName($model->status_id);
            return Yii::$app->ui->badgetStatus($model->status_id, $status);
        },
        FORMAT => 'raw',
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Logs::CONTROLLER_ID,
        FILTER => LogsSearch::getControllersListSearch(),
        VALUE => Logs::CONTROLLER_CONTROLLER_NAME,
        FORMAT => 'raw',
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Logs::ACTION_ID,
        FILTER => LogsSearch::getActionListSearch($controller_id),
        VALUE => Logs::ACTION_ACTION_NAME,
        FORMAT => 'raw',
    ],
    Logs::EVENT,
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Logs::USER_ID,
        FILTER => LogsSearch::getUserList(),
        VALUE => function ($model) {
            
            $model=  User::getUsername($model->user_id);
            if ($model) {
                $return = $model->username;
            } else {
                $return = Yii::t('app', 'Unkown');
            }
            return $return;            
        },
        FORMAT => 'raw',
    ],
]]);
echo '<br/><br/>';
echo HTML_WEBPAGE_CLOSE;
