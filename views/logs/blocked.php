<?php
/**
  * Ipv4 Blocked
  *
  * @package     Index of Blocked
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:17:13
  * @version     1.0
*/

use yii;
use yii\grid\GridView;
use \app\models\search\BlockedSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlockedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ipv4 Blocked ');
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
    'remove-circle',
    $this->title,
    Yii::t(
        'app',
        'This view shows the IP addresses that have been blocked for security or administrative reasons.'
    ),
    'blocked',
    false,
    true
);

echo GridView::widget([
'dataProvider' => $dataProvider,
'filterModel' => $searchModel,
'layout'=>'{items}{summary}{pager}',
'filterSelector' => 'select[name="per-page"]',
    'tableOptions' =>[STR_CLASS => 'table maxwidth items table-striped table-condensed'],
'columns' => [
    [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS=>'width10px']],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => 'id',
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        FORMAT => 'raw'
    ],
    'ipv4_address',
    'date',
    [
        "class" => yii\grid\DataColumn::className(),
        "attribute" => "status_id",
        'filter' => BlockedSearch::getStatusListSearch(),
        "value" => 'status.status',
        "format" => "raw",
    ],
]]);

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
