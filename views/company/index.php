<?php
/**
  * Company
  *
  * @package     Index of Company
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-09-16 18:30:42
  * @version     1.0
*/

use app\components\UiComponent;
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\queries\Common;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Company::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

$template = Common::getProfilePermissionString();

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
                STR_CLASS => yii\grid\DataColumn::className(),
                FILTER => UiComponent::yesOrNoArray(),
                ATTRIBUTE =>  ACTIVE,
                OPTIONS => [STR_CLASS=> COLSM1],
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                FORMAT=>'raw'
            ],

        [
            STR_CLASS => yii\grid\ActionColumn::className(),
            HEADER => UiComponent::pageSizeDropDownList($pageSize),
            'template' => $template,
            'contentOptions' => [STR_CLASS => 'GridView'],
        ]
    ]
]);

echo UiComponent::buttonsAdminBottom('111', false);
Html::endForm();
echo HTML_WEBPAGE_CLOSE;
