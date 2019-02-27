<?php

use app\components\UiComponent;

/* @var yii\web\View $this */
/* @var \app\models\User $model */

$this->title = Yii::t('app', 'Request password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">';

            echo UiComponent::header(
                'user',
                Yii::t('app', 'Request password reset'),
                Yii::t('app', 'Requested password reset')
            );
            echo '<div class="success"><h4>',
                Yii::t(
                    'app',
                    'We have sent you an email with a reset link. Please check your Inbox'
                ),
                '<br/><br/><br/></h4></div>';

                echo Yii::$app->view->render('@app/views/partials/_links_return_to');

                echo '
        </div>
        <div class="col-sm-3 "> &nbsp; </div>
    </div>
</div>';
