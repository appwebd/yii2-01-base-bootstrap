<?php
/**
 * PHP Version 7.2.0
 *
 * @copyright 2008 Copyright (c) Yii Software LLC
 * @license   BSD 3-clause Clear license
 * @link      http://www.yiiframework.com/
 * @license   http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath  = '@webroot';
    public $jsOptions = ['position' => View::POS_END];
    public $css = [
        ['css/style.min.css', 'media' => 'all', 'type' => 'text/css'],
    ];
    public $js = [
        ['js/custom.min.js', 'async' => true] // or defer
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
