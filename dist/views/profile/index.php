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
use app\components\UiButtons;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModelProfile app\models\search\ProfileSearch */
/* @var $dataProviderProfile yii\data\ActiveDataProvider */
/* @var $pageSize int */

$template = Common::getProfilePermissionString();
$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo Html::beginForm(['profile/index'], 'post');

$uiComponent = new UiComponent();
$uiComponent->headerAdmin(
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
                STR_CLASS => yii\grid\DataColumn::className(),
                VALUE => Common::isActive()
            ],
            [
                'buttons' => UiButtons::buttonsActionColumn(),
                'contentOptions' => [STR_CLASS => 'GridView'],
                HEADER => UiComponent::pageSizeDropDownList($pageSize),
                'headerOptions' => ['style' => 'color:#337ab7'],
                STR_CLASS => yii\grid\ActionColumn::class,
                TEMPLATE => Common::getProfilePermissionString('111'),
            ]
        ]
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'app\views\profile\index::GridView', MSG_ERROR);
}

$buttons = new UiButtons();
$buttons->buttonsAdmin('111', false);

Html::endForm();

echo HTML_WEBPAGE_CLOSE;
