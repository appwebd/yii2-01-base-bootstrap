<?php
/**
  * Company
  *
  * @package     View of Company
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-09-16 18:30:43
  * @version     1.0
*/

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('app', Company::TITLE);
$this->params[BREADCRUMBS][] = [LABEL => $this->title, 'url' => [ACTION_INDEX]];
$this->params[BREADCRUMBS][] = $model->company_id;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;
echo Yii::$app->ui->header(
    'home',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Company')
);

echo DetailView::widget([
    MODEL => $model,
    ATTRIBUTES => [
                Company::COMPANY_ID,
                Company::COMPANY_NAME,
                Company::ADDRESS,
                Company::CONTACT_PERSON,
                Company::CONTACT_PHONE_1,
                Company::CONTACT_PHONE_2,
                Company::CONTACT_PHONE_3,
                Company::CONTACT_EMAIL,
                Company::WEBPAGE,
                [
                        ATTRIBUTE => Company::ACTIVE,
                        OPTIONS => [STR_CLASS=> COLSM1],
                        VALUE => function ($model) {
                            return Yii::$app->ui->yesOrNo($model->active);
                        },
                ],
    ],
]);

echo Yii::$app->ui->buttonsViewBottom($model);
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
