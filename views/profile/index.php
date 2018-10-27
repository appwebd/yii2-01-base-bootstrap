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

use app\components\UiComponent;
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\queries\Common;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

echo GridView::widget([
    'dataProvider' => $dataProviderProfile,
    'filterModel' => $searchModelProfile,
    'layout'=>'{items}{summary}{pager}',
    'filterSelector' => 'select[name="per-page"]',
    'tableOptions' =>[STR_CLASS => GRIDVIEW_CSS],
    'columns' => [
        [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS => 'width10px']],
        Profile::PROFILE_NAME,
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => UiComponent::yesOrNoArray(),
            ATTRIBUTE => Profile::ACTIVE,
            OPTIONS => [STR_CLASS=>'col-sm-1'],
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

echo UiComponent::buttonsAdmin('111', false);
Html::endForm();

echo HTML_WEBPAGE_CLOSE;
