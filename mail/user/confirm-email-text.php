<?php
/**
 * @var \yii\web\View $this
 * @var \yii\mail\MessageInterface $message
 * @var app\models\User $model
 */

use yii\helpers\Url;
use yii\helpers\Html;

$url = Url::to(['/user/confirm-email',
    'token' => $model->email_confirmation_token,
], true);
?>

Hello <?= Html::encode($model->username) ?>,

Follow the link below to complete your registration:

<?= Html::a(Html::encode($url), $url) ?>

