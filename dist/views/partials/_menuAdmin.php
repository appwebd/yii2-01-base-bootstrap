<?php
/**
 * @app/view/partials/menuAdmin.php
 *
 * @package     @app/view/partials/menuAdmin.php
 * @authors     Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        6/18/18 10:34 AM
 * @version     1.0
 */

use app\models\queries\Bitacora;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    OPTIONS => [STR_CLASS => 'navbar-inverse navbar-fixed-top'],
]);

try {
    echo Nav::widget([
        OPTIONS => [STR_CLASS => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            [LABEL => '<span class="glyphicon glyphicon-home"></span> &nbsp; ' . Yii::t('app', 'Home'), 'url' => ['/'],],
            [LABEL => Yii::t('app', 'Logs'), 'url' => ['logs/index']],
            [LABEL => Yii::t('app', 'Users'), 'url' => ['user/index']],
            [LABEL => Yii::t('app', 'Permission'), 'url' => ['permission/index']],
            [LABEL => Yii::t('app', 'Profiles'), 'url' => ['profile/index']],

            [
                LABEL => Yii::t('app', 'System'),

                OPTIONS => [STR_CLASS => 'dropdown'],
                'template' => 'a href="{url}" class="href_class">{label}</a>',
                'items' => [
                    [LABEL => Yii::t('app', 'Action'), 'url' => ['logs/actions']],
                    [LABEL => Yii::t('app', 'Blocked'), 'url' => ['logs/blocked']],
                    [LABEL => Yii::t('app', 'Controllers'), 'url' => ['logs/controllers']],
                    [LABEL => Yii::t('app', 'Status'), 'url' => ['logs/status']],
                ]

            ],
            Yii::$app->user->isGuest ? (
            [LABEL => Yii::t('app', 'Login'), 'url' => ['/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/login/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    [STR_CLASS => 'btn btn-link logout navbar-btn']
                )
                . Html::endForm()
                . '</li>'
            ),
        ],
    ]);
} catch (Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'app\views\partials\_menuAdmin::Nav', MSG_ERROR);
}

NavBar::end();
