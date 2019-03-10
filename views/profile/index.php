<?php
/**
 * Profiles
 *
 * @package     Index of Profile
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-08-26 17:15:29
 * @version     1.0
 */

use yii\grid\GridView;
use yii\helpers\Html;
use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\queries\Common;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $searchModelProfile app\models\search\ProfileSearch */
/* @var $dataProviderProfile yii\data\ActiveDataProvider */
/* @var $pageSize int */

$template = Common::getProfilePermissionString();
$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['profile/index'], 'post');
echo UiComponent::headerAdmin(
    'user',
    $this->title,
    Yii::t('app', 'This view permit Create a new User, update or delete information related of user'),
    'profile',
    '111',
    false
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProviderProfile,
        'filterModel' => $searchModelProfile,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [STR_CLASS => 'yii\grid\CheckboxColumn', 'options' => [STR_CLASS => 'width10px']],
            Profile::PROFILE_NAME,
            [
                ATTRIBUTE => Profile::ACTIVE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                OPTIONS => [STR_CLASS => 'col-sm-1'],
                STR_CLASS => yii\grid\DataColumn::class,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
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
} catch (Exception $errorexception) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [MODULE=> 'app\views\profile\index::GridView::widget', ERROR => $errorexception]
        ),
        MSG_ERROR
    );
}

UiComponent::buttonsAdmin('111', false);

Html::endForm();

echo HTML_WEBPAGE_CLOSE;
