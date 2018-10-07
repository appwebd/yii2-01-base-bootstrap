<?php
/**
  * Permission
  *
  * @package     Index of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Permission;
use app\models\search\ActionSearch;
use app\models\search\ProfileSearch;
use app\models\search\ControllersSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Html::beginForm(['permission/index'], 'post');

echo Yii::$app->ui->headerAdmin(
    'ok-circle',
    $this->title,
    Yii::t('app', 'This view permit Create, update or delete information related of permission'),
    Permission::TABLE,
    '111',
    false
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
            ATTRIBUTE => Permission::PROFILE_ID,
            FILTER => ProfileSearch::getProfileListSearch(Permission::TABLE),
            VALUE=>'profile.profile_name',
            FORMAT => 'raw',
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Permission::CONTROLLER_ID,
            FILTER => ControllersSearch::getControllersListSearch(Permission::TABLE),
            VALUE=>'controllers.controller_name',
            FORMAT => 'raw',
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => "action_id",
            FILTER => ActionSearch::getActionListSearch($controller_id, Permission::TABLE),
            VALUE => 'action.action_name',
            FORMAT => 'raw',
        ],

        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'action_permission',
            OPTIONS => [STR_CLASS=>'col-sm-1'],
            VALUE => function ($model) {
                $url = "permission/toggle";
                return Html::a(
                    '<span class="'.Yii::$app->ui->yesOrNoGlyphicon($model->action_permission).'"></span>',
                    $url,
                    [
                        'title' => Yii::t('yii', 'Toggle value active'),
                        'data-value'=>$model->action_permission,
                        'data' => [
                            METHOD => 'post',
                        ],
                        'data-pjax'=>'w0',
                    ]
                );
            },
            FORMAT=>'raw'
        ],

        [
            'header' => Yii::$app->ui->pageSizeDropDownList($pageSize),
            STR_CLASS  => 'yii\grid\ActionColumn',
            'contentOptions'=>[STR_CLASS=>'GridView'],
        ],
    ]
]);

echo '<br/><br/>';
echo Yii::$app->ui->buttonsAdmin('111', false);
Html::endForm();
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
