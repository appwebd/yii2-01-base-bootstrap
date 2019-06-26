<?php

namespace app\controllers;

use app\models\queries\Bitacora;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Base controller
 * PHP version 7.2.0
 *
 * @category  Controller
 * @package   Base
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */
class BaseController extends Controller
{
    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const ENCRIPTED_METHOD = 'aes-256-cbc';
    const SHA256 = 'sha256';
    const SECRET_KEY = 'money20343';
    const SECRET_IV = '2034312280';
    const PARAM_STR_KEY = 'passwordKey';
    const STR_PER_PAGE = 'per-page';
    const STR_PAGESIZE = 'pageSize';

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
     * @return array|mixed
     */
    public static function pageSize()
    {

        $session = Yii::$app->session;
        $page_size = Yii::$app->request->get(self::STR_PER_PAGE);

        if (!isset($page_size)) {
            $page_size = Yii::$app->request->post(self::STR_PER_PAGE);
            if (isset($page_size)) {
                $page_size = Yii::$app->request->post(self::STR_PER_PAGE);
            } else {
                if (isset($session[self::STR_PAGESIZE])) {
                    $page_size = $session[self::STR_PAGESIZE];
                } else {
                    $page_size = Yii::$app->params['pageSizeDefault'];
                    $session->set(self::STR_PAGESIZE, $page_size);
                }
            }
        }

        $session->set(self::STR_PAGESIZE, $page_size);
        return $page_size;
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
        $key_value = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        $output = openssl_encrypt($plaintext, $encryptmethod, $key_value, 0, $ivencripted);

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
        $key_value = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        return openssl_decrypt(base64_decode($ciphertext), $encryptmethod, $key_value, 0, $ivencripted);
    }

    /**
     * @return array
     */
    public function behaviorsCommon()
    {
        return [
            'access' => [
                STR_CLASS => AccessControl::className(),
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
                STR_CLASS => VerbFilter::className(),
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
            $bitacora->registerAndFlash(
                $event,
                'checkBadAccess',
                MSG_SECURITY_ISSUE
            );
            return true;
        }

        return false;
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
            $key_value = SUCCESS;
            $value = 'Record saved successfully';
        } else {
            $key_value = ERROR;
            $value = 'Error saving record';
        }
        $value = Yii::t('app', $value);
        Yii::$app->session->setFlash($key_value, $value);
    }
}
