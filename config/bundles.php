<?php

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
