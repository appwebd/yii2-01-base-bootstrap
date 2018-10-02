<?php
/**
 * Actions
 *
 * @package     Index of Action
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private comercial license
 * @link        https://appwebd.github.io
 * @date        2018-08-02 20:07:03
 * @version     1.0
 */

use yii\grid\GridView;
use app\models\search\ControllersSearch;
use app\models\Action;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Action::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->headerAdmin(
    'list-alt',
    $this->title,
    Yii::t('app', 'This view recollect all the views or windows that exists in this web application. 
    (for a proposal of privileges and access control)'),
    'action',
    false,
    true
);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '{items}{summary}{pager}',
    'filterSelector' => 'select[name="per-page"]',
    'tableOptions' =>[STR_CLASS => GRIDVIEW_CSS],
    'columns' => [
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Action::ACTION_ID,
            OPTIONS => [STR_CLASS=>'col-sm-1'],
            FORMAT => 'raw'
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Action::CONTROLLER_ID,
            FILTER => ControllersSearch::getControllersListSearch(Action::TABLE),
            VALUE => Action::CONTROLLER_CONTROLLER_NAME,
            FORMAT => 'raw',
        ],
        Action::ACTION_NAME,
        Action::ACTION_DESCRIPTION,
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => Action::ACTIVE,
            OPTIONS => [STR_CLASS => 'col-sm-1'],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
    ]
]);

echo '<br/><br/>';

echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
