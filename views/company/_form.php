<?php
/**
  * Company
  *
  * @package     form of Company
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-09-16 18:30:41
  * @version     1.0
*/

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Company;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */
if ($model->isNewRecord) {
    $model->active = 1;
}
echo '
<div class="row">
    <div class="col-sm-4">';
?>
        some text of help
<?php
    echo '</div>',
    '<div class="col-sm-8">';
        $form = ActiveForm::begin(
            [
               'id' => 'form-company',
               'method'  => 'post',
               'options' => [STR_CLASS => 'form-vertical webpage'],
               'enableAjaxValidation' => false,
            ]
        );

        echo $form->field($model, Company::COMPANY_NAME, [
                        INPUT_OPTIONS => [
                            AUTOFOCUS => AUTOFOCUS,
                            TABINDEX => 1,
                            REQUIRED => REQUIRED,
                            AUTOCOMPLETE => 'off',
                        ],
        ])->textInput([MAXLENGTH => true,])->label();

        echo HTML_ROW_DIV6;

        echo $form->field($model, Company::ADDRESS, [
            INPUT_OPTIONS => [
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => 2,
                REQUIRED => REQUIRED,
                AUTOCOMPLETE => 'off',
                PLACEHOLDER => 'Stree # 123, city, country'

            ],

        ])->textInput([MAXLENGTH => true,])->label();

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, Company::CONTACT_PERSON, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 3,
                        REQUIRED => REQUIRED,
                        AUTOCOMPLETE => 'off',
                        PLACEHOLDER => 'John Doe'
                    ],

        ])->textInput([MAXLENGTH => true,])->label();

        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

        echo $form->field($model, Company::CONTACT_PHONE_1, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 4,
                        AUTOCOMPLETE => 'off',
//                        PATTERN => PATTERN_PHONE,
                        PLACEHOLDER => 'phone number example: 579-1234-1234'
                    ],

        ])->Input('tel')->label();

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, Company::CONTACT_PHONE_2, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 5,
                        AUTOCOMPLETE => 'off',
//                        PATTERN => PATTERN_PHONE,
                        PLACEHOLDER => 'phone number example: 569-6903-4007'
                    ],

        ])->Input('tel')->label();

        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;

        echo $form->field($model, Company::CONTACT_PHONE_3, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 6,
                        AUTOCOMPLETE => 'off',
 //                       PATTERN => PATTERN_PHONE
                    ],
        ])->Input('tel')->label();

        echo HTML_DIV_CLOSE_DIV6_OPEN;

        echo $form->field($model, Company::CONTACT_EMAIL, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 7,
                        AUTOCOMPLETE => 'off',
                    ],

        ])->Input('email')->label();
        echo HTML_DIV_CLOSEX2, HTML_ROW_DIV6;
        echo $form->field($model, Company::WEBPAGE, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 8,
                        PLACEHOLDER => 'https://www.company.com',
                        AUTOCOMPLETE => 'off',
                    ],

        ])->Input('url')->label();
        echo HTML_DIV_CLOSE_DIV6_OPEN,'<br>';
        echo $form->field($model, Company::ACTIVE, [
                    INPUT_OPTIONS => [
                        AUTOFOCUS => AUTOFOCUS,
                        TABINDEX => 9,
                        REQUIRED => REQUIRED,
                        AUTOCOMPLETE => 'off',
                    ],
                ])->checkbox([
                    UNCHECK=>0,
                    LABELOPTIONS => array(STR_CLASS => COLSM1),
                ])->label('Active');

        echo HTML_DIV_CLOSEX2,
             '<br><br><div class=\'form-group\'>';
        echo Yii::$app->ui->buttonsCreate(10);
        echo $form->errorSummary($model);
        echo '</div>';
        ActiveForm::end();

        echo '
    </div>
</div>';
