<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var yii\web\View $this */
/* @var yii\bootstrap\ActiveForm $form */
/* @var \app\models\forms\LoginForm $model */

$this->title = Yii::t('app', 'Login');
$this->params[BREADCRUMBS][] = $this->title;

?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">



                <?= Yii::$app->ui->header(
                    'user',
                    $this->title,
                    Yii::t('app', 'Please complete the following fields to start your session:')
                );?>

                <?php $form = ActiveForm::begin(
                    ['id' => 'login-form',
                        'options' => [STR_CLASS => 'form-horizontal webpage'],
                    ]
                );?>

                <?= $form->field($model, 'username', [
                    'inputOptions' =>  ['autofocus' => 'autofocus',
                                        'tabindex' => '1',
                                        'autocomplete'=>'off',
                                        'placeholder'=> Yii::t('app', 'User account'),
                                        'required'=>'required',
                                         'title'             =>'The user account is required information!',
                                         'x-moz-errormessage'=>'The user account is required information!'
                                        ],
                    'inputTemplate' => '<div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                            {input}
                                        </div>'
                ])->textInput()->label(false); ?>

                <?= $form->field($model, 'password', [
                    'inputOptions' =>  ['autofocus' => 'autofocus',
                                        'tabindex' => '2',
                                        'autocomplete'=>'off',
                                        'placeholder'=> Yii::t('app', 'Password'),
                                        'required'=>'required',
                                        'title'             => Yii::t('app', 'The password is required information!'),
                                        'x-moz-errormessage'=> Yii::t('app', 'The password is required information!')
                                       ],
                    'inputTemplate' => '<div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                            {input}
                                        </div>'
                ])->passwordInput(['placeholder'=> Yii::t('app', ' Password')])->label(false); ?>


                <div class="help-block">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                            'title'=>'We don\'t recommend this in shared computers.',
                            'autofocus' => 'autofocus',
                            'tabindex' => '3'
                    ]) ?>
                </div>

                <div class="form-group">

                    <?= Html::submitButton(
                        Yii::t('app', 'Login'),
                        [
                            STR_CLASS => 'btn btn-primary',
                            'name' => 'login-button',
                            'autofocus' => 'autofocus',
                            'tabindex' => '4'
                        ]
                    ) ?>
                </div>
                <?php ActiveForm::end(); ?>

                <br/>

                <div class="text-center border-top help-block">
                    <br/>
                    <?= Html::a(
                        Yii::t(
                            'app',
                            'forget your password?'
                        ),
                        ['/login/resetrequest']
                    );?>.

                    &nbsp; | &nbsp;
                    <?=
                    Yii::t('app', 'You do not have an account?'),
                    '&nbsp;',
                    Html::a(Yii::t('app', 'Signup'), ['singup/']);
                    ?>
                </div>


        </div>

        <div class="col-sm-3 "> &nbsp; </div>
    </div>


</div>
