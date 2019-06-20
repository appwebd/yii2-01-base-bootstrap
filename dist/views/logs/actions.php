<?php
/**
 * Actions
 *
 * @package     Index of Action
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-08-02 20:07:03
 * @version     1.0
 */

use app\components\UiComponent;
use app\models\Action;
use app\models\queries\Bitacora;
use app\models\search\ControllersSearch;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Action::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo UiComponent::headerAdmin(
    'list-alt',
    $this->title,
    Yii::t(
        'app',
        'This view recollect all the views or windows that exists in this web application.
        (for a proposal of privileges and access control)'
    ),
    'action',
    '000',
    true
);

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{summary}{pager}',
        'filterSelector' => 'select[name="per-page"]',
        'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
        'columns' => [
            [
                ATTRIBUTE => Action::ACTION_ID,
                OPTIONS => [STR_CLASS => COLSM1],
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN
            ],
            [
                ATTRIBUTE => Action::CONTROLLER_ID,
                FILTER => ControllersSearch::getControllersListSearch(Action::TABLE),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => Action::CONTROLLER_CONTROLLER_NAME,
            ],
            Action::ACTION_NAME,
            Action::ACTION_DESCRIPTION,
            [
                ATTRIBUTE => Action::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
                STR_CLASS => GRID_DATACOLUMN,
            ],
        ]
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'app\views\logs\actions::GridView', MSG_ERROR);
}

echo '<br/><br/>';

echo HTML_WEBPAGE_CLOSE;
