<?php
/**
  * Profiles
  *
  * @package     form of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:33
  * @version     1.0
*/

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
    $model->active = 1;
}

echo '
<div class="row">
    <div class="col-sm-5">';
?>
        texto de Ayuda
<?php
    echo '
    </div>
    <div class="col-sm-7">';

        $form = ActiveForm::begin(
            [
               'id' => 'form-profile',
               'method'  => 'post',
               'options' => ['class' => 'form-vertical webpage'],
            ]
        );


       echo $form->field($model, 'profile_name')->textInput(
           [
                MAXLENGTH => true,
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 1,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
           ]
       )->label();

       echo $form->field($model, 'active')->checkbox(
            [
                UNCHECK => 0,
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 2,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
           ]
       )->label('Active');

        echo '<div class=\'form-group\'>';
            echo Yii::$app->ui->buttonsCreate(3);
            echo $form->errorSummary($model, array(STR_CLASS => "alert alert-danger"));
        echo '</div>';
        ActiveForm::end();

        ?>

    </div>
</div>
