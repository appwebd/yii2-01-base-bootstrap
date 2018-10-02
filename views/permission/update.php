<?php
/**
  * Permission
  *
  * @package     Update of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = ['label' => $model->permission_id, 'url' => ['view', 'id' => $model->permission_id]];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Update');

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->header(
    'ok-circle', //Icons
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    )
);

echo $this->render('_form', ['model' => $model,]);
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
