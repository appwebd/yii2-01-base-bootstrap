<?php
/**
  * Logs (user bitacora)
  *
  * @package     Index of Logs
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:23:23
  * @version     1.0
*/

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\search\LogsSearch;
use app\models\Logs;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs (user bitacora)');
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
    'record',
    $this->title,
    Yii::t('app', 'This view is the event log of the web application.'),
    'logs',
    false,
    true
);

echo GridView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'layout'=>'{items}{summary}{pager}',
'filterSelector' => 'select[name="per-page"]',
'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
'columns' => [
    [STR_CLASS => 'yii\grid\CheckboxColumn', OPTIONS => [STR_CLASS=>'width10px']],
    'logs_id',
    'date',
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => 'status_id',
        FILTER => LogsSearch::getStatusListSearch(),
        LABEL => 'Status',
        VALUE => function ($model) {
            $status = Status::getStatusName($model->status_id);
            return Yii::$app->ui->badgetStatus($model->status_id, $status);
        },
        FORMAT => 'raw',
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => "controller_id",
        FILTER => LogsSearch::getControllersListSearch(),
        VALUE=>'controllers.controller_name',
        FORMAT => 'raw',
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => "action_id",
        FILTER => LogsSearch::getActionListSearch($controller_id),
        VALUE => 'action.action_name',
        FORMAT => 'raw',
    ],
    'event',
    'user_agent',
    [
        ATTRIBUTE => 'ipv4_address',
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        VALUE => 'ipv4_address',
    ],
    [
        ATTRIBUTE => 'confirmed',
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        FILTER => Yii::$app->ui->yesOrNoArray(),
        VALUE => function ($model) {
            return Yii::$app->ui->yesOrNo($model->confirmed);
        }
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => "user_id",
        FILTER => LogsSearch::getUserList(),
        VALUE => function ($model) {
            return $model->user->firstName . ' ' .$model->user->lastName;
        },
        FORMAT => 'raw',
    ],
]]);

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
