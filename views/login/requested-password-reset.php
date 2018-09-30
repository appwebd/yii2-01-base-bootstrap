<?php
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var \app\models\User $model */

$this->title = Yii::t('app', 'Request password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">';

            echo Yii::$app->ui->header('black', 'user', 'Request password reset', Yii::t('app', 'Thanks you'));
            echo '<br>
                <p class="alert alert-info">';
                echo Yii::t(
                    'app',
                    'We have sent you an email with a reset link. Please check your Inbox'
                );
                echo '
                </p>';

                echo Yii::$app->view->render('@app/views/partials/_links_return_to');

                echo '
        </div>
        <div class="col-sm-3 "> &nbsp; </div>
    </div>
</div>';
