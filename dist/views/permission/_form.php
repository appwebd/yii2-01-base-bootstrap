<?php
/**
 * Permission
 *
 * @package     form of Permission
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:28:33
 * @version     1.0
 */

use app\components\UiButtons;
use app\models\Action;
use app\models\Controllers;
use app\models\Permission;
use app\models\Profile;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */
/* @var $form yii\widgets\ActiveForm */


echo '<div class="row">
    <div class="col-sm-5 text-justify"><p>';
echo Yii::t(
    'app',
    'Select the user profile to which you want to create / update the access properties'
);
echo '<br/><br/><br/><br/><br/><br/>';
echo Yii::t(
    'app',
    'Select the view / web page to then identify the action that is performed on it.
    Setting access Yes / No will indicate if the profile has access to this resource'
);
echo '</p></div><div class="col-sm-7">';

$form = ActiveForm::begin(
    [
        'id' => 'form-permission',
        'method' => 'post',
        'options' => ['class' => 'form-vertical webpage'],
    ]
);


$items = Profile::getProfileList();
echo $form->field($model, Permission::PROFILE_ID)->RadioList(
    $items,
    [
        PROMPT => Yii::t('app', 'Select Profile'),
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 1,
        REQUIRED => REQUIRED,
        AUTOCOMPLETE => 'off',
    ]
)->label();

$items = Controllers::getControllersList();
echo $form->field($model, Permission::CONTROLLER_ID)->dropDownList(
    $items,
    [
        PROMPT => Yii::t('app', 'Select Controller'),
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 2,
        REQUIRED => REQUIRED,
        AUTOCOMPLETE => 'off',
        'onchange' => '
            $.get( "' . Yii::$app->urlManager->createUrl('permission/actiondropdown') .
            '", {id: $(this).val()})
                .done(function( data ) {
                    $( "#' . Html::getInputId($model, Permission::ACTION_ID) . '" ).html( data );
                }
            );'
    ]
);


if ($model->isNewRecord) {
    echo $form->field($model, Permission::ACTION_ID)->dropDownList(
        [
            1 => 'que permiso falta definir aqui'
        ],
        [
            AUTOFOCUS => AUTOFOCUS,
            TABINDEX => 3,
            REQUIRED => REQUIRED,
            PROMPT => Yii::t('app', 'Select Action'),
        ]
    )->label();

    $model->action_permission = 1;
} else {
    $items = Action::getActionListById($model->controller_id);
    echo $form->field($model, Permission::ACTION_ID)->dropDownList(
        $items,
        [
            PROMPT => Yii::t('app', 'Select Action'),
            AUTOFOCUS => AUTOFOCUS,
            TABINDEX => 3,
            REQUIRED => REQUIRED,
        ]
    )->label();
}

echo $form->field($model, Permission::ACTION_PERMISSION)->checkbox(
    [
        AUTOFOCUS => AUTOFOCUS,
        TABINDEX => 4,
        UNCHECK => 0,
    ]
);

echo '<div class=\'form-group\'>';

$button = new UiButtons();
echo $button->ButtonsCreate(5);

echo $form->errorSummary($model, array(STR_CLASS => "alert alert-danger"));
echo '</div>';
ActiveForm::end();

echo '</div></div>';
