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

use yii\grid\GridView;
use app\models\search\LogsSearch;
use app\models\Logs;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Logs::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
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
    Logs::USER_AGENT,
    [
        ATTRIBUTE => Logs::IPV4_ADDRESS,
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        VALUE => Logs::IPV4_ADDRESS,
    ],
    [
        ATTRIBUTE => Logs::CONFIRMED,
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        FILTER => Yii::$app->ui->yesOrNoArray(),
        VALUE => function ($model) {
            return Yii::$app->ui->yesOrNo($model->confirmed);
        }
    ],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Logs::USER_ID,
        FILTER => LogsSearch::getUserList(),
        VALUE => function ($model) {
            return $model->user->firstName . ' ' .$model->user->lastName;
        },
        FORMAT => 'raw',
    ],
]]);

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
