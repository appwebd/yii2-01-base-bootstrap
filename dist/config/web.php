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
                    'message' => [
                        'from' => 'pro@localhost',
                        'to' => 'pro@localhost'
                    ],
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
                //  'encryption' => 'tls',
            ],
        ],

        'request' => [
            'cookieValidationKey' => '-ep1N8LQ66XkS34oQIEgZAogO466l7HX',
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
            'csrfCookie' => [
                'httpOnly' => true,
            ],
        ],
        'session' => [
            STR_CLASS => 'yii\web\DbSession',
            //'name' => 'MYAPPSID',
            //'savePath' => '@app/tmp/sessions',
            'timeout' => 1440, //24 minutos?
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login/index'],
            'identityCookie' => [
                'name' => '_identity-backend',
                'httpOnly' => true,
            ],
        ],
    ],
    'defaultRoute' => 'site/index',
    'id' => 'basic',
    'name' => 'Base',
    'language' => 'en',
    'layoutPath' => '@app/views/layouts',
    'params' => $params,
    'sourceLanguage' => 'en',
    'vendorPath' => '@app/vendor',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config[BOOTSTRAP][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting
        // from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config[BOOTSTRAP][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting
        // from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
