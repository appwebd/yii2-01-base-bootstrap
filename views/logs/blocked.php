<?php
/**
 * Ipv4 Blocked
 *
 * @package     Index of Blocked
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 19:17:13
 * @version     1.0
 */

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Blocked;
use app\models\search\BlockedSearch;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BlockedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Blocked::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo HTML_WEBPAGE_OPEN;

echo UiComponent::headerAdmin(
    'remove-circle',
    $this->title,
    Yii::t(
        'app',
        'This view shows the IP addresses that have been blocked for security or administrative reasons.'
    ),
    'blocked',
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
            [STR_CLASS => 'yii\grid\CheckboxColumn', 'options' => [STR_CLASS => 'width10px']],
            [
                ATTRIBUTE => Blocked::ID,
                OPTIONS => [STR_CLASS => 'col-sm-1'],
                FORMAT => 'raw',
                STR_CLASS => GRID_DATACOLUMN,
            ],
            Blocked::IPV4_ADDRESS,
            Blocked::DATE,
            [
                ATTRIBUTE => Blocked::STATUS_ID,
                FILTER => BlockedSearch::getStatusListSearch(),
                FORMAT => "raw",
                STR_CLASS => GRID_DATACOLUMN,
                VALUE => Blocked::STATUS_STATUS_NAME,
            ],
        ]]);
} catch (Exception $errorException) {
    BaseController::bitacora(
        Yii::t(
            'app',
            ERROR_MODULE,
            [MODULE => 'app\views\logs\blocked::gridView::widget', ERROR => $errorException]
        ),
        MSG_ERROR
    );
}

echo HTML_WEBPAGE_CLOSE;
