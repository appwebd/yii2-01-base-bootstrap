<?php
/**
  * Permission
  *
  * @package     View of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = $model->permission_id;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->header(
    'ok-circle',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Permission')
);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    //'permission_id',
        [
            ATTRIBUTE => 'profile.profile_name',
            VALUE => function ($model) {
                return $model->profile->profile_name;
            },
            LABEL =>'Profile'
        ],
        [
            ATTRIBUTE => 'controllers.controller_name',
            VALUE => function ($model) {
                    return $model->controllers->controller_name;
            },
            LABEL =>'View'
        ],
        [
            ATTRIBUTE => 'action.action_name',
            VALUE => function ($model) {
                    return $model->action->action_name;
            },
            LABEL =>'action'
        ],
        [
            ATTRIBUTE => 'action_permission',
            OPTIONS => [STR_CLASS=>'col-sm-1'],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->action_permission);
            },
            FORMAT=>'raw'
        ],
    ],
]);

echo Yii::$app->ui->buttonsViewBottom($model);
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;
