<?php
/**
  * Informative status of events in all the platform
  *
  * @package     Index of Status
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/


use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Status codes');
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Html::beginForm(['logs/status'], 'post');
echo Yii::$app->ui->headerAdmin(
    'road',
    $this->title,
    Yii::t('app', 'This view exists for to do more easy the stadistica process in the web application'),
    'status',
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
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => 'status_id',
            OPTIONS => [STR_CLASS=> COLSM1],
            FORMAT=>'raw'
        ],
        'status_name',
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'active',
            OPTIONS => [STR_CLASS=> COLSM1],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],

    ]
]);

echo '<br/><br/>';
Html::endForm();

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
