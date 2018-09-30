<?php
/**
  * User
  *
  * @package     Update of User
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-06-16 23:03:07
  * @version     1.0
*/


/* @var $this yii\web\View */
/* @var $model app\models\User */


$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Update');

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->header(
    'user',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information of User.'
    )
);

echo $this->render('_form', ['model' => $model,]);
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
