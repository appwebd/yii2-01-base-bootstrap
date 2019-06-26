<?php
/**
 * Login view
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Login
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var yii\bootstrap\ActiveForm $form */
/* @var object $model \app\models\forms\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params[BREADCRUMBS][] = $this->title;

echo '<div class="container">
    <div class="row">
        <div class="col-sm-3 "> &nbsp;</div>
        <div class="col-sm-6 box">';

$uiComponent = new UiComponent();
$uiComponent->header(
    'user',
    $this->title,
    Yii::t(
        'app',
        'Please complete the following fields to start your session:'
    )
);


$form = ActiveForm::begin(
    ['id' => 'login-form',
        'options' => [STR_CLASS => 'form-horizontal webpage'],
    ]
);

echo $form->field(
    $model,
    'username',
    [
        'inputOptions' => [
            AUTOFOCUS => AUTOFOCUS,
            TABINDEX => '1',
            AUTOCOMPLETE => 'off',
            PLACEHOLDER => Yii::t('app', 'User account'),
            REQUIRED => REQUIRED,
            TITLE => 'The user account is required information!',
            'x-moz-errormessage' => 'The user account is required information!'
        ],
        'inputTemplate' => '<div class="input-group">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-user">
                                </span>
                            </span>
                            {input}
                        </div>'
    ]
)->textInput()->label(false);

echo $form->field(
    $model, 'password',
    [
        'inputOptions' => [AUTOFOCUS => AUTOFOCUS,
            TABINDEX => '2',
            AUTOCOMPLETE => 'off',
            PLACEHOLDER => Yii::t(
                'app',
                'Password'
            ),
            REQUIRED => REQUIRED,
            TITLE => Yii::t(
                'app',
                'The password is required information!'
            ),
            'x-moz-errormessage' => Yii::t(
                'app',
                'The password is required information!'
            )
        ],
        'inputTemplate' => '<div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </span>
                                {input}
                            </div>'
    ]
)->passwordInput(
    [
        PLACEHOLDER => Yii::t(
            'app',
            ' Password'
        )
    ]
)->label(false);

echo '<div class="help-block">',
$form->field($model, 'rememberMe')->checkbox(
    [
        TITLE => 'We don\'t recommend this in shared computers.',
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => '3'
    ]
),
'
       </div>
       <div class="form-group">',
Html::submitButton(
    Yii::t('app', 'Submit'),
    [
        STR_CLASS => 'btn btn-primary',
        'name' => 'login-button',
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => '4'
    ]
),
'</div>';
ActiveForm::end();


echo '<br/>
      <div class="text-center border-top help-block">
      <br/>',
Html::a(
    Yii::t(
        'app',
        'forget your password?'
    ),
    ['/password/index']
),
'&nbsp; | &nbsp;',
Yii::t('app', 'You do not have an account?'),
'&nbsp;',
Html::a(Yii::t('app', 'Signup'), ['/signup/index']),

'    </div>
        </div>
        <div class="col-sm-3 "> &nbsp;</div>
    </div>
</div>';
