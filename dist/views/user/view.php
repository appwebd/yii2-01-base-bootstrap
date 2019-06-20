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
use app\controllers\BaseController;
use app\models\Profile;
use app\models\User;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];

echo HTML_WEBPAGE_OPEN;

echo UiComponent::header(
    'user',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of User')
);

try {
    echo DetailView::widget([
        'model' => $model,
        OPTIONS => [STR_CLASS => DETAILVIEW_CLASS],
        'attributes' => [
            User::USERNAME,
            User::FIRSTNAME,
            User::LASTNAME,
            User::EMAIL,
            User::TELEPHONE,

            [
                ATTRIBUTE => User::PROFILE_ID,
                HEADER => Yii::t('app', 'Profile'),
                VALUE => function ($model) {
                    return Profile::getProfileName($model->profile_id);
                },
            ],
            User::IPV4_ADDRESS_LAST_LOGIN,
            [
                ATTRIBUTE => User::ACTIVE,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                FORMAT => 'raw'
            ],
        ],
    ]);
} catch (Exception $errorexception) {
    BaseController::bitacora(
        Yii::t(
            'app',
            'Failed to show information, error: {error}',
            ['error' => $errorexception]
        ),
        MSG_ERROR
    );
}

echo UiComponent::buttonsViewBottom($model);
echo HTML_WEBPAGE_CLOSE;
