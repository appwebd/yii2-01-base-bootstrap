<?php
/**
 * User _form
 *
 * @package     _form Update/create information for table user
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private commercial license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 23:03:06
 * @version     1.0
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Profile;
use app\models\Company;

/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class='row'>
    <div class='col-sm-4'>
        <p class="text-justify">
        <br/>
        Select the company associated with the user account you are creating:<br><br>
        Enter all the information of user identification and user account.
        <br/><br/><br/><br/>
        User account is the account with which the user or person will connect to this system.
</P>
    </div>

    <div class='col-sm-8'>

        <?php
        $form = ActiveForm::begin(
            ['id' => 'form-user',
                'method'  => 'post',
                'options' => ['class' => 'form-vertical webpage'],
            ]
        );

        $items = Company::getCompanyList();
        echo $form->field($model, 'company_id')->dropDownList(
            $items,
            [
                'prompt' => Yii::t('app', 'Select Company'),
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 1,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
            ]
        )->label();

        echo HTML_ROW_DIV6;

        echo $form->field($model, 'firstName')->textInput(
            [
                MAXLENGTH => true,
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 2,
                PLACEHOLDER => 'First name for example John'
            ]
        )->label();

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, 'lastName')->textInput(
            [
                MAXLENGTH => true,
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 3,
                PLACEHOLDER => 'Last name for example Doe'
            ]
        )->label();

        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

        echo $form->field($model, 'telephone')->textInput(
            [
                TYPE => 'tel',
                PATTERN => PATTERN_PHONE,
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 4,
                PLACEHOLDER => 'Phone number ex. 56 9 12345678'
            ]
        )->label()->hint('Format phone:'.PATTERN_PHONE);

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, 'email')->input(
        'email',
           [
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 5,
                PLACEHOLDER => Yii::t('app', 'john.doe@domain.com')
           ]
        )->label();

        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;


        echo $form->field($model, 'username')->textInput(
            [
                MAXLENGTH => true,
                AUTOFOCUS   => AUTOFOCUS,
                TABINDEX    => 6,
                PLACEHOLDER => 'User account'

            ]
        )->label();

        echo HTML_DIV_CLOSE_DIV6_OPEN;
        echo $form->field($model, 'password')->passwordInput(
            [
                MAXLENGTH => true,
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX  => 7
            ]
        )->label()
        ->hint(Yii::t('app', 'When entering a password, it will be updated for this user account'));

        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

        $items = Profile::getProfileList();
        echo $form->field($model, 'profile_id')->radioList(
            $items,
            [
                'prompt'=>Yii::t('app', 'Select Profile'),
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX  => 8,
            ]
        );

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, 'active')->checkbox(
            [
                UNCHECK => 0,
                LABEL => '&nbsp; Active?',
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 9,
            ]
        );

        echo HTML_DIV_CLOSEX2, '<br/>';
        echo '<div class=\'form-group\'>';
            echo Yii::$app->ui->buttonsCreate(10);
            echo $form->errorSummary($model, array(STR_CLASS => "alert alert-danger"));
        echo '</div>';
        ActiveForm::end();

        ?>

    </div>
</div>

