<?php
/**
 * Company
 *
 * @category  View
 * @package   Index
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @link      https://appwebd.github.io
 * @date      2018-09-16 18:30:42
 * @version   1.0
 */

use yii\grid\GridView;
use yii\helpers\Html;
use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\queries\Common;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pageSize int */

$this->title = Yii::t('app', Company::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['company/index'], 'post');
echo UiComponent::headerAdmin(
    'home',
    $this->title,
    Yii::t('app', 'This view permit Create a new User, update or delete information related of user'),
    'company',
    '111',
    false
);
try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout'=>'{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' =>[STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS => 'width10px']],
            Company::COMPANY_ID,
            Company::COMPANY_NAME,
            Company::ADDRESS,
            Company::CONTACT_PERSON,
            Company::CONTACT_PHONE_1,
            Company::CONTACT_PHONE_2,
            Company::CONTACT_PHONE_3,
            Company::CONTACT_EMAIL,
            Company::WEBPAGE,
            [
                ATTRIBUTE =>  ACTIVE,
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT=>'raw',
                OPTIONS => [STR_CLASS=> COLSM1],
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
            'Failed to show information, error: {error}',
            [ERROR => $errorexception]
        ),
        MSG_ERROR
    );
}

UiComponent::buttonsAdmin('111', false);
Html::endForm();
echo HTML_WEBPAGE_CLOSE;
