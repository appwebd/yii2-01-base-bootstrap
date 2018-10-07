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

use yii\grid\GridView;
use \app\models\search\BlockedSearch;
use app\models\Blocked;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BlockedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app',  Blocked::TITLE);
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
    [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS=>'width10px']],
    [
        STR_CLASS => yii\grid\DataColumn::className(),
        ATTRIBUTE => Blocked::ID,
        OPTIONS => [STR_CLASS=>'col-sm-1'],
        FORMAT => 'raw'
    ],
    Blocked::IPV4_ADDRESS,
    Blocked::DATE,
    [
        "class" => yii\grid\DataColumn::className(),
        "attribute" => Blocked::STATUS_ID,
        'filter' => BlockedSearch::getStatusListSearch(),
        "value" => Blocked::STATUS_STATUS_NAME,
        "format" => "raw",
    ],
]]);

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
