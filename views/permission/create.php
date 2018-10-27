<?php
/**
  * Permission
  *
  * @package     Create of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use app\components\UiComponent;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Create');

echo HTML_WEBPAGE_OPEN;

echo UiComponent::header(
    'ok-circle',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information of Permission.'
    )
);

echo $this->render('_form', ['model' => $model,]);
echo HTML_WEBPAGE_CLOSE;
