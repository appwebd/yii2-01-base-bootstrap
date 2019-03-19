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
use app\controllers\BaseController;
use app\models\Permission;
use app\models\queries\Common;
use app\models\search\ActionSearch;
use app\models\search\ControllersSearch;
use app\models\search\ProfileSearch;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pageSize int */
/* @var $controller_id int */


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

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [STR_CLASS => 'yii\grid\CheckboxColumn', 'options' => [STR_CLASS => 'width10px']],
            [
                STR_CLASS => GRID_DATACOLUMN,
                ATTRIBUTE => Permission::PROFILE_ID,
                FILTER => ProfileSearch::getProfileListSearch(Permission::TABLE),
                VALUE => 'profile.profile_name',
                FORMAT => 'raw',
            ],
            [
                STR_CLASS => GRID_DATACOLUMN,
                ATTRIBUTE => Permission::CONTROLLER_ID,
                FILTER => ControllersSearch::getControllersListSearch(Permission::TABLE),
                VALUE => 'controllers.controller_name',
                FORMAT => 'raw',
            ],
            [
                STR_CLASS => GRID_DATACOLUMN,
                ATTRIBUTE => Permission::ACTION_ID,
                FILTER => ActionSearch::getActionListSearch($controller_id, Permission::TABLE),
                VALUE => 'action.action_name',
                FORMAT => 'raw',
            ],

            [
                STR_CLASS => GRID_DATACOLUMN,
                FILTER => UiComponent::yesOrNoArray(),
                ATTRIBUTE => Permission::ACTION_PERMISSION,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    $url = "permission/toggle";
                    return Html::a(
                        '<span class="' . UiComponent::yesOrNoGlyphicon($model->action_permission) . '"></span>',
                        $url,
                        [
                            'title' => Yii::t('yii', 'Toggle value active'),
                            'data-value' => $model->action_permission,
                            'data' => [
                                METHOD => 'post',
                            ],
                            'data-pjax' => 'w0',
                        ]
                    );
                },
                FORMAT => 'raw'
            ],

            [
                'buttons' => UiComponent::buttonsActionColumn(),
                'contentOptions' => [STR_CLASS => 'GridView'],
                HEADER => UiComponent::pageSizeDropDownList($pageSize),
                'headerOptions' => ['style' => 'color:#337ab7'],
                STR_CLASS => yii\grid\ActionColumn::class,
                TEMPLATE => Common::getProfilePermissionString('111'),
            ]
        ]
    ]);
} catch (Exception $e) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [MODULE => 'app\views\permission\index::GridView::widget', ERROR => $e]
        ),
        MSG_ERROR
    );
}

try {
    UiComponent::buttonsAdmin('111', false);
} catch (\yii\db\Exception $e) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [MODULE => 'app\views\permission\index::GridView::widget', ERROR => $e]
        ),
        MSG_ERROR
    );
}
Html::endForm();
echo HTML_WEBPAGE_CLOSE;
