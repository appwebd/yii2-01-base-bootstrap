<?php
/**
 * Allows you to configure what features of the framework will be loaded
 * PHP Version 7.4.0
 *
 * @category  Config
 * @package   Bundles
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */

if (YII_ENV) {
    $bundles = [
        'yii\web\JqueryAsset' => [
            'js' => ['jquery.js']
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'css' => ['css/bootstrap.min.css'],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'js' => ['js/bootstrap.js']
        ]
    ];
} else {
    $bundles = [
        'yii\web\JqueryAsset' => [
            'js' => ['jquery.min.js']
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'css' => ['css/bootstrap.min.css'],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'js' => ['js/bootstrap.min.js']
        ]
    ];
}

return $bundles;
