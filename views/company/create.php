<?php
/**
 * Company
 *
 * @package     Create of Company
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-09-16 18:30:43
 * @version     1.0
 */

use app\components\UiComponent;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('app', Company::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Create');

echo HTML_WEBPAGE_OPEN;
echo UiComponent::header(
    'home',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    )
);

echo $this->render('_form', ['model' => $model,]);
echo HTML_WEBPAGE_CLOSE;
