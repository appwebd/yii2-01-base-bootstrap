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

use app\components\UiButtons;
use app\components\UiComponent;
use app\models\Profile;
use app\models\queries\Bitacora;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->header(
    'globe',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Profiles')
);

try {
    echo DetailView::widget([
        'model' => $model,
        OPTIONS => [STR_CLASS => DETAILVIEW_CLASS],
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
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'app\views\profile\DetailView::GridView', MSG_ERROR);
}

$buttons = new UiButtons();
$buttons->buttonsViewBottom($model);

echo HTML_WEBPAGE_CLOSE;
