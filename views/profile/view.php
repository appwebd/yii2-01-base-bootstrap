<?php
/**
  * Profiles
  *
  * @package     View of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use yii\widgets\DetailView;
use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];

echo HTML_WEBPAGE_OPEN;

echo UiComponent::header(
    'globe',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Profiles')
);

try {
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            Profile::PROFILE_ID,
            Profile::PROFILE_NAME,
            Profile::CREATED_AT,
            [
                ATTRIBUTE => Profile::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
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
