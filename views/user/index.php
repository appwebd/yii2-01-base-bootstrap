<?php
/**
  * User
  *
  * @package     Index of user
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 14:27:11
  * @version     1.0
*/

use yii\grid\GridView;
use yii\helpers\Html;
use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\queries\Common;
use app\models\Profile;
use app\models\User;
use app\models\search\ProfileSearch;

/* @var $this yii\web\View */
/* @var $searchModelUser app\models\search\UserSearch */
/* @var $dataProviderUser yii\data\ActiveDataProvider */
/* @var $pageSize int */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['user/index'], 'post');
echo UiComponent::headerAdmin(
    'user',
    $this->title,
    Yii::t('app', 'This view permit Create a new User, update or delete information related of user'),
    'user',
    '111',
    false
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProviderUser,
        'filterModel' => $searchModelUser,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [

            [
                STR_CLASS => 'yii\grid\CheckboxColumn',
                'options' => [STR_CLASS => 'width:10px'],
            ],
            User::USERNAME,
            User::FIRSTNAME,
            User::LASTNAME,
            User::EMAIL,
            [
                STR_CLASS => GRID_DATACOLUMN,
                ATTRIBUTE => User::PROFILE_ID,
                FILTER => ProfileSearch::getProfileListSearch('user'),
                VALUE => function ($model) {
                    $profile_name = Profile::getProfileName($model->profile_id);
                    return UiComponent::badgetStatus($model->profile_id, $profile_name);
                },
                FORMAT => 'raw',
            ],
            [
                STR_CLASS => GRID_DATACOLUMN,
                FILTER => UiComponent::yesOrNoArray(),
                ATTRIBUTE => User::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                FORMAT => 'raw'
            ],
            [
                STR_CLASS => yii\grid\ActionColumn::class,
                'contentOptions' => [STR_CLASS => 'GridView'],
                HEADER => UiComponent::pageSizeDropDownList($pageSize),
                'headerOptions' => ['style' => 'color:#337ab7'],
                TEMPLATE => Common::getProfilePermissionString(),
                'buttons' => [
                    ACTION_VIEW => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url,
                            [
                                TITLE => Yii::t('app', 'Full details'),
                                'data-pjax' => '0',
                            ]
                        );
                    },

                    ACTION_UPDATE => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,
                            [
                                TITLE => Yii::t('app', ACTION_UPDATE),
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    ACTION_DELETE => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            $url,
                            [
                                TITLE => Yii::t('app', 'Delete'),
                                'data-confirm' => Yii::t(
                                    'app',
                                    'Are you sure you want to delete?'
                                ),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    }
                ],

                'urlCreator' => function ($action, $model, $key, $index) {
                    $key = BaseController::stringEncode($key);
                    $url = 'index.php?r='. Yii::$app->controller->id;

                    if ($action === ACTION_VIEW) {
                        $url = $url . '/' . $action . '&id='.$key;
                    }

                    if ($action === ACTION_UPDATE) {
                        $url = $url . '/' . $action . '&id='.$key;
                    }

                    if ($action === ACTION_DELETE) {
                        $url = $url . '/' . $action . '&id='.$key;
                    }

                    return $url;
                },
            ]
        ]
    ]);
} catch (\yii\db\Exception $e) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [
                MODULE=> '@app\views\user\index',
                ERROR => $e
            ]
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
            [
                MODULE=> '@app\views\User\index::UiComponent::buttonsAdmin',
                ERROR => $e
            ]
        ),
        MSG_ERROR
    );
}

Html::endForm();
echo HTML_WEBPAGE_CLOSE;

