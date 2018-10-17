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
use app\models\Controllers;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ControllersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Controllers::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
    'eye-open',
    $this->title,
    Yii::t('app', 'This view recollect all the controllers that exists in this web application'),
    'controllers',
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
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Controllers::CONTROLLER_ID,
            OPTIONS => [STR_CLASS=>COLSM1],
            FORMAT=>'raw'
        ],
        Controllers::CONTROLLER_NAME,
        Controllers::CONTROLLER_DESCRIPTION,
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => Controllers::MENU_BOOLEAN_PRIVATE,
            OPTIONS => [STR_CLASS=>'col-sm-2'],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => Controllers::MENU_BOOLEAN_VISIBLE,
            OPTIONS => [STR_CLASS=>COLSM1],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => Controllers::ACTIVE,
            OPTIONS => [STR_CLASS=>COLSM1],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
    ]
]);

echo '<br/><br/>';

echo HTML_WEBPAGE_CLOSE;
