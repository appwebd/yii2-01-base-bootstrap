<?php

$bundles = require __DIR__ . '/bundles.php';
$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';


$config = [
    'id' => 'basic',
    'name' => 'Base',
    'basePath' => dirname(__DIR__),
    'language' => 'en',
    'sourceLanguage' => 'en',
    'charset' => 'UTF-8',
    'layoutPath' => '@app/views/layouts',
    'vendorPath' => '@app/vendor',
    'defaultRoute' => 'site/index',
    //'catchAll' => self::env('MAINTENANCE', false) ? ['site/maintenance'] : null,

// https://www.yiiframework.com/doc/api/2.0/yii-filters-hostcontrol
// the following configuration is only preferred like last resource (is preferable web server configuration instead)
/*
    'as hostControl' => [
        'class' => 'yii\filters\HostControl',
        'allowedHosts' => [
            'base.local',
            '*.base.local',
        ],
        'fallbackHostInfo' => 'https://base.local',
    ],
*/
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        [
            'class' => 'app\components\LanguageSelector',
            'supportedLanguages' => ['en', 'es'],
        ],
    ],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => false,
            STR_CLASS => 'yii\web\AssetManager',
            'bundles' => $bundles,
        ],
        'cache' => DISABLE_CACHE ?
            'yii\caching\DummyCache' :
            [
                STR_CLASS => 'yii\caching\ApcCache',
                'useApcu' => true,
            ],
        'db' => $db,
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    STR_CLASS => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                ],
                'app*' => [
                    STR_CLASS => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        'log' => [

            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    STR_CLASS => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/app.log',
                    'except' => [
                      'yii\web\HttpException:404',
                    ],
                ],
                /*
                'file'=>[
                    STR_CLASS => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                    'logFile' => '@runtime/logs/sql.log',
                    'categories' => [
                        'yii\db\*',
                    ],
                    'except' => [
                      'yii\web\HttpException:404',
                    ],
                ],
                'email' => [
                    STR_CLASS => 'yii\log\EmailTarget',
                    'except' => ['yii\web\HttpException:404'],
                    'levels' => ['error', 'warning'],
                    //'categories' => ['yii\db\*'],
                    'message' => ['from' => 'pro@localhost', 'to' => 'pro@localhost'],
                    'subject' => 'Database errors at example.com',
                ],*/
            ],
        ],
        'mailer' => [
            STR_CLASS => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'transport' => [
                STR_CLASS => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'pro@dev-master.local',
                'password' => 'password', // your password
                'port' => '25',
//                'encryption' => 'tls',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-ep1N8LQ66XkS34oQIEgZAogO466l7HX',
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
        ],
        'session' => [
            STR_CLASS => 'yii\web\DbSession',
            //'name' => 'MYAPPSID',
            //'savePath' => '@app/tmp/sessions',
            'timeout' => 1440, //24 minutos?
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login/index'],
        ],

        'ui' => [
            STR_CLASS => 'app\components\UiComponent',
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
return $config;
