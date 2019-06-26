<?php
/**
 * Informative status of events in all the platform
 *
 * @package     Index of Status
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:28:34
 * @version     1.0
 */

use app\components\UiComponent;
use app\models\queries\Bitacora;
use app\models\Status;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Status::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->headerAdmin(
    'road',
    $this->title,
    Yii::t('app', 'This view exists for to do more easy the stadistica process in the web application'),
    'status',
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
                ATTRIBUTE => Status::STATUS_ID,
                OPTIONS => [STR_CLASS => COLSM1],
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
            ],
            Status::STATUS_NAME,
            [
                ATTRIBUTE => Status::ACTIVE,
                OPTIONS => [STR_CLASS => COLSM1],
                FILTER => UiComponent::yesOrNoArray(),
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => function ($model) {
                    return UiComponent::yesOrNo($model->active);
                },
            ],

        ]
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'app\views\log\status::GridView', MSG_ERROR);
}

echo '<br/><br/>';
echo HTML_WEBPAGE_CLOSE;
