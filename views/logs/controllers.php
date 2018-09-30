<?php
/**
  * Controllers
  *
  * @package     Index of Controllers
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:20:11
  * @version     1.0
*/

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ControllersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Controllers');
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
    'eye-open',
    $this->title,
    Yii::t('app', 'This view recollect all the controllers that exists in this web application'),
    'controllers',
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
            ATTRIBUTE => 'controller_id',
            OPTIONS => [STR_CLASS=>COLSM1],
            FORMAT=>'raw'
        ],
        'controller_name',
        'controller_description',
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'menu_boolean_private',
            OPTIONS => [STR_CLASS=>'col-sm-2'],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'menu_boolean_visible',
            OPTIONS => [STR_CLASS=>COLSM1],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'active',
            OPTIONS => [STR_CLASS=>COLSM1],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
    ]
]);

echo '<br/><br/>';

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
