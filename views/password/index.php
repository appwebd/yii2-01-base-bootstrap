<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\UiComponent;
use app\models\forms\PasswordResetRequestForm;

/* @var yii\web\View $this */
/* @var yii\bootstrap\ActiveForm $form */
/* @var \app\models\forms\PasswordResetRequestForm $model */

$this->title = Yii::t('app', 'Request password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">

            <div class="webpage ">';

                echo UiComponent::header(
                    'user',
                    $this->title,
                    Yii::t(
                        'app',
                        'Please, write your registered mail in this platform to reset your password'
                    )
                );


                $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'options' => ['class' => 'form-horizontal webpage'],
                ]);

                echo $form->field($model, PasswordResetRequestForm::EMAIL, [
                    'inputTemplate' => '<div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-envelope"></span>
                                            </span>
                                            {input}
                                        </div>'
                ])->textInput([
                    'placeholder'=> Yii::t(
                        'app',
                        ' valid email account, Ex: account@domain.com'
                    )
                ])->label(false);

                echo '
                <div class="form-group">
                    <div class="help-block text-justify">';
                echo Yii::t(
                    'app',
                    'A link to reset the password will be sent to your email account.'
                );
                echo '
                    </div>';

                echo Html::submitButton(
                    Yii::t('app', 'Submit'),
                    ['class' => 'btn btn-primary']
                );

                echo '&nbsp;
                </div>';

                ActiveForm::end();

                echo Yii::$app->view->render('@app/views/partials/_links_return_to');
                echo '
        </div>
        <div class="col-sm-3 "> &nbsp;&nbsp; </div>
    </div>
</div>
</div>';
