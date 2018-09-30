<?php
/**
  * Permission
  *
  * @package     form of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:33
  * @version     1.0
*/

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Action;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */
/* @var $form yii\widgets\ActiveForm */

?>

<div class='row'>
    <div class='col-sm-5'>
        texto de Ayuda
    </div>

    <div class='col-sm-7'>
        <?php
        $form = ActiveForm::begin(
            [
               'id' => 'form-permission',
               'method'  => 'post',
               'options' => ['class' => 'form-vertical webpage'],
            ]
        );


        $items = $model->getProfileList();
        echo $form->field($model, Permission::PROFILE_ID)->dropDownList(
            $items,
            [
                PROMPT=>Yii::t('app', 'Select Profile'),
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 1,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
            ]
        )->label();

        $items = $model->getControllersList();
        echo $form->field($model, Permission::CONTROLLER_ID)->dropDownList(
            $items,
            [
                PROMPT => Yii::t('app', 'Select Controller'),
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 2,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
                'onchange'=>'
                    $.get( "'. Yii::$app->urlManager->createUrl('permission/actiondropdown') .
                    '", {id: $(this).val()})
                        .done(function( data ) {
                            $( "#'.Html::getInputId($model, Permission::ACTION_ID) . '" ).html( data );
                        }
                    );'
            ]
        );


        if ($model->isNewRecord) {
            echo $form->field($model, Permission::ACTION_ID)->dropDownList(
                [
                    1=>'que permiso falta definir aqui'
                ],
                [
                    AUTOFOCUS => AUTOFOCUS,
                    TABINDEX => 3,
                    REQUIRED => REQUIRED,
                    //AUTOCOMPLETE => 'off',
                    PROMPT => Yii::t('app', 'Select Action'),
                ]
            )->label();

            $model->action_permission=1;

        } else {

            $items = Action::getActionListById($model->controller_id);
            echo $form->field($model, Permission::ACTION_ID)->dropDownList(
                    $items,
                    [
                        PROMPT=>Yii::t('app', 'Select Action'),
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 3,
                        REQUIRED => REQUIRED,
                    ]
            )->label();
        }

        echo $form->field($model, Permission::ACTION_PERMISSION)->checkbox(
            [
                UNCHECK => 0, LABEL => '&nbsp; Assign access ?',
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 4,
            ]
        );

        echo '<div class=\'form-group\'>';
            echo Yii::$app->ui->buttonsCreate(5);
            echo $form->errorSummary($model, array(STR_CLASS => "alert alert-danger"));
        echo '</div>';
        ActiveForm::end();

        ?>

    </div>
</div>
