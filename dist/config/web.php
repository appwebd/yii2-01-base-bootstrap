<?php
/**
 * Main application settings
 * PHP Version 7.2.0
 *
 * @category  Config
 * @package   Web
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */

$bundles = require __DIR__ . '/bundles.php';
$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';


$config = [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'basePath' => dirname(__DIR__),
    BOOTSTRAP => [
        'log',
        [
            STR_CLASS => 'app\components\LanguageSelector',
            'supportedLang' => ['en', 'es'],
        ],
    ],
    //'catchAll' => self::env('MAINTENANCE', false) ? ['site/maintenance'] : null,
    // https://www.yiiframework.com/doc/api/2.0/yii-filters-hostcontrol
    // the following configuration is only preferred like last resource
    // (is preferable web server configuration instead)
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

    'charset' => 'UTF-8',
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => false,
            'class' => 'yii\web\AssetManager',
            'bundles' => $bundles,
            'forceCopy' => true
        ],
        'cache' => DISABLE_CACHE ?
            'yii\caching\DummyCache' :
            [
                STR_CLASS => 'yii\caching\ApcCache',
                'useApcu' => true,
            ],
        'db' => $db,
        'errorHandler' => [
            'maxSourceLines' => 20,
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
            'timeFormat' => 'H:i:s',

            'locale' => 'es-ES', //your language locale
            'defaultTimeZone' => 'Chile/Continental', // time zone
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    'on missingTranslation' =>
                    [
                        'app\components\TranslationEvent', 'MissingTrans'
                    ]
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
                    'class' => 'yii\log\FileTarget',
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
                    'class' => 'yii\log\EmailTarget',
                    'except' => ['yii\web\HttpException:404'],
                    'levels' => ['error', 'warning'],
                    //'categories' => ['yii\db\*'],
                    'message' => [
                        'from' => 'pro@localhost',
                        'to' => 'pro@localhost'
                    ],
                    'subject' => 'Database errors at example.com',
                ],*/
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'pro@dev-master.local',
                'password' => 'password', // your password
                'port' => '25',
//                'encryption' => 'tls',
            ],
        ],

// Enable the following instruction
// only if you have a web page server with SSL certificate
/*
        'cookies' => [
            'class' => 'yii\web\Cookie',
            'httpOnly' => true,
            'secure' => true
        ],
*/
        'request' => [
            'cookieValidationKey' => '-ep1N8LQ66XkS34oQIEgZAogO466l7HX',
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
// Enable the following instruction
// only if you have a web page server with SSL certificate
/*
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true
            ]
*/
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'session',
            //'name' => 'MYAPPSID',
            //'savePath' => '@app/tmp/sessions',
            'timeout' => 1440, //24 minutes?

// Enable the following instruction
// only if you have a web page server with SSL certificate
/*
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => true
            ]
*/
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login/index'],
// Enable the following instruction
// only if you have a web page server with SSL certificate
/*
            'identityCookie' => [
                'name' => '_identity-backend',
                'httpOnly' => true,
                'secure' => true,
            ],
*/
        ],
/*
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable r= routes
            'enablePrettyUrl' => true,
            // Disable index.php
            'showScriptName' => true,
//            'enableStrictParsing' => true,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'defaultRoute' => '/site/index',
                '' => '/site/index' // En caso de esablecer enableStrictParsing=>true
            ],
        ],
*/
    ],
    'defaultRoute' => 'site/index',
    'id' => 'base',
    'language' => 'en',
    'layoutPath' => '@app/views/layouts',
    'name' => 'Base',
    'params' => $params,
    'sourceLanguage' => 'en',
    'vendorPath' => '@app/vendor',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP
        // if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting
        // from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
