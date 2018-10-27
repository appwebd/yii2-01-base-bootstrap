<?php
/**
  * Permission
  *
  * @package     Index of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use app\components\UiComponent;
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

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['permission/index'], 'post');

echo UiComponent::headerAdmin(
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
            VALUE => 'profile.profile_name',
            FORMAT => 'raw',
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Permission::CONTROLLER_ID,
            FILTER => ControllersSearch::getControllersListSearch(Permission::TABLE),
            VALUE => 'controllers.controller_name',
            FORMAT => 'raw',
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => Permission::ACTION_ID,
            FILTER => ActionSearch::getActionListSearch($controller_id, Permission::TABLE),
            VALUE => 'action.action_name',
            FORMAT => 'raw',
        ],

        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => UiComponent::yesOrNoArray(),
            ATTRIBUTE => Permission::ACTION_PERMISSION,
            OPTIONS => [STR_CLASS => COLSM1],
            VALUE => function ($model) {
                $url = "permission/toggle";
                return Html::a(
                    '<span class="'.UiComponent::yesOrNoGlyphicon($model->action_permission).'"></span>',
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
            HEADER => UiComponent::pageSizeDropDownList($pageSize),
            STR_CLASS => yii\grid\ActionColumn::className(),
            'contentOptions'=>[STR_CLASS => 'GridView'],
        ],
    ]
]);

echo UiComponent::buttonsAdmin('111', false);
Html::endForm();
echo HTML_WEBPAGE_CLOSE;
