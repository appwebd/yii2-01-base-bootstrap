<?php
/**
  * Permission
  *
  * @package     View of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use app\components\UiComponent;
use yii\widgets\DetailView;
use app\models\Permission;

/* @var $this yii\web\View */
/* @var $model app\models\Permission */

$this->title = Yii::t('app', Permission::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = $model->permission_id;

echo HTML_WEBPAGE_OPEN;

echo UiComponent::header(
    'ok-circle',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Permission')
);

try {
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                ATTRIBUTE => Permission::PROFILE_NAME,
                VALUE => function ($model) {
                    return $model->profile->profile_name;
                },
            ],
            [
                ATTRIBUTE => Permission::CONTROLLER_ID,
                VALUE => function ($model) {
                    return $model->controllers->controller_name;
                },
            ],
            [
                ATTRIBUTE => Permission::ACTION_ID,
                VALUE => function ($model) {
                    return $model->action->action_name;
                },
            ],
            [
                ATTRIBUTE => Permission::ACTION_PERMISSION,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->action_permission);
                },
                FORMAT => 'raw'
            ],
        ],
    ]);
} catch (Exception $errorexception) {
    BaseController::bitacora(
        Yii::t(
            'app',
            'Failed to show information, error: {error}',
            ['error' => $errorexception]
        ),
        MSG_ERROR
    );
}

echo UiComponent::buttonsViewBottom($model);
echo HTML_WEBPAGE_CLOSE;
