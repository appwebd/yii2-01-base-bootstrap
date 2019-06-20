<?php

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\web\Controller;

/**
 * Class BaseController
 *
 * @package     Ui
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        11/1/18 8:13 PM
 * @version     1.0
 */
class BaseController extends Controller
{
    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const ENCRIPTED_METHOD = 'aes-256-cbc';
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const SHA256 = 'sha256';
    const SECRET_KEY = 'money20343';
    const SECRET_IV = '2034312280';
    const PARAM_STR_KEY = 'passwordKey';
    const STR_PER_PAGE = 'per-page';
    const STR_PAGESIZE = 'pageSize';
    const TIME_FORMAT = 'php:H:i:s';

    public static function getPasswordParam()
    {

        $return = Yii::$app->params['passwordKey'];
        if (!isset($return)) {

            $session = Yii::$app->session;
            if (isset($session[self::PARAM_STR_KEY])) {
                $return = $session[self::PARAM_STR_KEY];
            } else {
                try {
                    $return = Yii::$app->security->generateRandomString();
                } catch (Exception $exception) {
                    $bitacora = new Bitacora();
                    $bitacora->register($exception, 'getPasswordParam', MSG_ERROR);
                }
            }

            $session->set(self::PARAM_STR_KEY, $return);
        }
        return $return;
    }

    /**
     * @return array
     */
    public function behaviorsCommon()
    {
        return [
            'access' => [
                STR_CLASS => \yii\filters\AccessControl::className(),
                'only' => [
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
                    self::ACTION_TOGGLE_ACTIVE,
                    ACTION_UPDATE,
                    ACTION_VIEW
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_CREATE,
                            ACTION_DELETE,
                            ACTION_INDEX,
                            ACTION_REMOVE,
                            self::ACTION_TOGGLE_ACTIVE,
                            ACTION_UPDATE,
                            ACTION_VIEW
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                STR_CLASS => \yii\filters\VerbFilter::className(),
                ACTIONS => [
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get'],
                    self::ACTION_TOGGLE_ACTIVE => ['post'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * Check if user profile has access privilege over one controller/action
     *
     * @param string $action action name
     *
     * @return bool
     */
    public function checkBadAccess($action)
    {
        if (!Common::getProfilePermission($action)) {
            $event = Yii::t(
                'app',
                'Your account don\'t have priviledges for this action,
                    please do not repeat this requirement. All site traffic is being monitored'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash($event, 'checkBadAccess', MSG_SECURITY_ISSUE);
            return true;
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    public static function pageSize()
    {

        $session = Yii::$app->session;
        $pageSize = Yii::$app->request->get(self::STR_PER_PAGE);

        if (!isset($pageSize)) {
            $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            if (isset($pageSize)) {
                $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            } else {
                if (isset($session[self::STR_PAGESIZE])) {
                    $pageSize = $session[self::STR_PAGESIZE];
                } else {
                    $pageSize = Yii::$app->params['pageSizeDefault'];
                    $session->set(self::STR_PAGESIZE, $pageSize);
                }
            }
        }

        $session->set(self::STR_PAGESIZE, $pageSize);
        return $pageSize;
    }

    /**
     *  show a status message of saving record
     *
     * @param boolean $status
     * @return void
     */
    public function saveReport($status)
    {
        if ($status) {
            $key = SUCCESS;
            $value = 'Record saved successfully';
        } else {
            $key = ERROR;
            $value = 'Error saving record';
        }
        $value = Yii::t('app', $value);
        Yii::$app->session->setFlash($key, $value );
    }
    /**
     * Encode a string
     *
     * @param string $plaintext plain text (text to encode)
     * @return string
     */
    public static function stringEncode($plaintext)
    {

        $encryptmethod = self::ENCRIPTED_METHOD;
        $secretkey = self::SECRET_KEY;
        $secretiv = self::SECRET_IV;

        // hash
        $keyValue = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        $output = openssl_encrypt($plaintext, $encryptmethod, $keyValue, 0, $ivencripted);

        return base64_encode($output);
    }

    /**
     * Decode a string
     * @param string $ciphertext text to decode
     * @return string decoded
     */
    public static function stringDecode($ciphertext)
    {

        $encryptmethod = self::ENCRIPTED_METHOD;
        $secretkey = self::SECRET_KEY;
        $secretiv = self::SECRET_IV;

        // hash
        $keyValue = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        return openssl_decrypt(base64_decode($ciphertext), $encryptmethod, $keyValue, 0, $ivencripted);
    }

}
