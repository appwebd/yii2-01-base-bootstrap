<?php
/**
 * +----------------------------------------------------------------------------+
 * | Web applications Development  • Business Process Management consultant     |
 * +----------------------------------------------------------------------------+
 * | @Authors   : Patricio Rojas Ortiz <patricio-rojaso@outlook.com>            |
 * | @Copyright : Copyright (C) Web Application development                     |
 * | @Homepage  : https://appwebd.github.io                                     |
 * | @Date      : 5/25/18 4:59 PM                                               |
 * +----------------------------------------------------------------------------+
 * | For  the  full  copyright and license information, please view the LICENSE |
 * | file that was distributed with this source code.                           |
 * |                                                                            |
 * | If  you  did not receive a copy of the license and are unable to obtain it |
 * | through the world-wide-web, please send an email to                        |
 * | patricio-rojaso@outlook.com so we can send you a copy immediately.         |
 * +----------------------------------------------------------------------------+
 */
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    OPTIONS => [ STR_CLASS => 'navbar-inverse navbar-fixed-top'],

]);


echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        [LABEL => '<span class="glyphicon glyphicon-home"></span> &nbsp;'.Yii::t('app', 'Home'), 'url' => ['/'],],
        [LABEL => Yii::t('app', 'About'), 'url' => ['/site/about']],
        [LABEL => Yii::t('app',  'Contact'), 'url' => ['/site/contact']],
        [LABEL =>  Yii::t('app', 'Login'), 'url' => ['/login']]
    ],
]);

NavBar::end();
