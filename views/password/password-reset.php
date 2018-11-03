<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\UiComponent;
use app\models\forms\PasswordResetForm;

/* @var yii\bootstrap\ActiveForm $form */
/* @var \app\models\forms\PasswordResetForm $model */

$this->title = Yii::t('app', 'Password reset');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">

            <div class="webpage ">';

                echo UiComponent::header(
                    'lock',
                    $this->title,
                    Yii::t(
                        'app',
                        'Please, write your new password'
                    )
                );


                $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'options' => ['class' => 'form-horizontal webpage'],
                ]);

                echo $form->field($model, PasswordResetForm::USER_ID)->hiddenInput(
                    [
                        VALUE => $model->user_id,
                    ]
                )->label(false);

                echo $form->field($model, PasswordResetForm::PASSW0RD, [
                    'inputTemplate' => '<div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon-glyphicon-lock"></span>
                                            </span>
                                            {input}
                                        </div>'
                ])->passwordInput([
                    'id' =>'passwd',
                    'placeholder'=> Yii::t(
                        'app',
                        'New password, Minimum password length are 8 chars and max. 50.'
                    )
                ])->label(false);

                echo '<input type="checkbox" onclick="js:showPassword()">&nbsp;&nbsp;', Yii::t('app', 'show password');

                echo '<br/><br/><br/>';

                echo Html::submitButton(
                    Yii::t('app', 'Submit'),
                    ['class' => 'btn btn-primary']
                );
                echo '<br/><br/><br/>
                </div>';

                ActiveForm::end();

                echo Yii::$app->view->render('@app/views/partials/_links_return_to');
                echo '
        </div>
        <div class="col-sm-3 "> &nbsp;&nbsp; </div>
    </div>
</div>
</div>';

$script=<<< JS
function showPassword() {
    var object = document.getElementById("passwd");
    if (object.type === "password") {
        object.type = "text";
    } else {
        object.type = "password";
    }
}
JS;

$this->registerJs($script, View::POS_HEAD);