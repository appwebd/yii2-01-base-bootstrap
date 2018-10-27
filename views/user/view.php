<?php
/**
  * View of table User
  *
  * @package     View of User
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        @DATETIME
  * @version     1.0
*/

use app\components\UiComponent;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $Model app\models\User */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = $model->user_id;

echo HTML_WEBPAGE_OPEN;

echo UiComponent::header(
    'user',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of User')
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        User::USER_ID,
        User::USERNAME,
        User::FIRSTNAME,
        User::LASTNAME,
        User::EMAIL,
        User::TELEPHONE,
        User::IPV4_ADDRESS_LAST_LOGIN,
        'profile.profile_name',
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => UiComponent::yesOrNoArray(),
            ATTRIBUTE => User::ACTIVE,
            OPTIONS => [STR_CLASS=>'col-sm-1'],
            VALUE => function ($model) {
                return UiComponent::yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
    ],
]);

echo UiComponent::buttonsViewBottom($model);
echo HTML_WEBPAGE_CLOSE;
