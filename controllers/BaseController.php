<?php

namespace app\controllers;

use app\models\Action;
use app\models\Controllers;
use app\models\Logs;
use app\models\queries\Common;
use app\models\Status;
use app\models\User;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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

    const SHA256 = 'sha256';
    const ENCRIPTED_METHOD = 'AES-256-CBC';
    const SECRET_KEY = 'money20343';
    const SECRET_IV = '2034312280';
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';

    /**
     * @return array
     */
    public static function behaviorsCommon()
    {
        /** @noinspection PhpDeprecationInspection */
        /** @noinspection PhpDeprecationInspection */
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    ACTION_REMOVE,
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
                            ACTION_UPDATE,
                            ACTION_VIEW
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                ACTIONS => [
                    ACTION_CREATE => ['get', 'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get', 'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * @param string $extension is the string extension of file to download
     * @return string ContentType string
     */
    public static function contentType($extension)
    {

        switch ($extension) {
            case 'pdf':
                $contentType = 'application/pdf';
                break;
            case 'jpg':
                $contentType = 'image/jpg';
                break;
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            case 'tif':
                $contentType = 'image/tiff';
                break;
            case 'csv':
                $contentType = 'text/csv';
                break;
            case 'txt':
                $contentType = 'text/txt';
                break;
            default:
                $contentType = 'text/txt';
                break;
        }

        return 'Content-Type: ' . $contentType;
    }

    /**
     * Check if user profile has access privilege over one controller/action
     * @param  string $action action name
     * @return bool
     */
    public static function checkBadAccess($action)
    {

        if (!Common::getProfilePermission($action)) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Your account don\'t have priviledges for this action,
                    please do not repeat this requirement. All site traffic is being monitored'
                ),
                MSG_SECURITY_ISSUE
            );
            return true;
        }

        return false;
    }

    /**
     * Save in table logs all events and activities of this web application and flash message respective
     *
     * @param string $event events or activities
     * @param integer $statusId status_id related to table status
     * @return void
     */
    public static function bitacoraAndFlash($event, $statusId)
    {
        BaseController::bitacora($event, $statusId);
        $badge = Status::getStatusBadge($statusId);
        Yii::$app->session->setFlash($badge, $event);
    }

    /**
     * Save in table logs all events and activities of this web application
     *
     * @param string $event events or activities
     * @param integer $statusId status_id related to table status
     * @return void
     */
    public static function bitacora($event, $statusId)
    {
        $error = false;
        $model = new Logs();
        $model->status_id = $statusId;
        $model->event = $event;
        $model->user_id = User::getIdentityUserId();
        $actionName = Yii::$app->controller->action->id; // Action name
        $controllerName = Yii::$app->controller->id;         // controller name

        $modelControllers = Controllers::getControllers($controllerName);
        if ($modelControllers) {
            $model->controller_id = $modelControllers->controller_id;
        } else {
            Controllers::addControllers($controllerName, 'not verified', 1, 0, 1);
            $modelControllers = Controllers::getControllers($controllerName);
            if ($modelControllers) {
                $model->controller_id = $modelControllers->controller_id;
            } else {
                $message = Yii::t(
                    'app',
                    'Error creating controlller name: {controller_name}',
                    ['controllerName' => $controllerName]
                );
                Yii::$app->session->setFlash(ERROR, $message);
                $error = true;
            }
        }

        $modelAction = Action::getAction($actionName, $model->controller_id);
        if ($modelAction) {
            $model->action_id = $modelAction->action_id;
        } else {
            try {
                Action::addAction($model->controller_id, $actionName, 'not verified', 1);
            } catch (Exception $e) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [
                            MODULE => 'app\controllers\BaseController::bitacora addAction',
                            ERROR => $e
                        ]
                    ),
                    MSG_ERROR
                );
            }
            $modelAction = Action::getAction($actionName, $model->controller_id);
            if ($modelAction) {
                $model->action_id = $modelAction->action_id;
            } else {
                $mesage = Yii::t(
                    'app',
                    'Error creating action name: {action_name}',
                    ['action_name' => $actionName]
                );
                Yii::$app->session->setFlash(ERROR, $mesage);
                $error = true;
            }
        }


        if ($error) {
            $message = Yii::t('app', 'Could not save new log information: {error}', ['error' => print_r($model->errors, true)]);
            Yii::$app->session->setFlash(ERROR, $message);
        } else {
            $model->user_agent = Yii::$app->request->userAgent;
            $model->ipv4_address = Yii::$app->getRequest()->getUserIP();
            $model->ipv4_address_int = ip2long($model->ipv4_address);
            $model->confirmed = 0;
            $model->save();
        }
    }

    /**
     * To show information about status delete record
     *
     * @param integer $status false=0/true=1/2 of transaction delete record in table
     * @return void
     */
    public static function deleteReport($status)
    {

        switch ($status) {
            case 0:
                $msg_text = 'Record has been deleted';
                $msg_status = SUCCESS;
                break;
            case 1:
                $msg_text = 'There was an error removing the record';
                $msg_status = ERROR;
                break;
            default:
                $msg_text = 'Record could not be deleted because it is being used in the system';
                $msg_status = ERROR;
                break;
        }

        $msg_text = Yii::t('app', $msg_text);
        Yii::$app->session->setFlash($msg_status, $msg_text);
    }

    /**
     * @return string
     */
    public static function getDirectoryUpload()
    {
        $uploadDirectory = Yii::$app->params['upload_directory'];
        if (!isset($uploadDirectory)) {
            $uploadDirectory = '/web/uploads/';
        }

        if (!file_exists(Yii::$app->basePath . $uploadDirectory)) {
            mkdir(Yii::$app->basePath . $uploadDirectory, 0777);
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'To upload files was created the directory: {dir}',
                    ['dir' => $uploadDirectory]
                ),
                MSG_ERROR
            );
        }

        return $uploadDirectory;
    }

    /**
     * Previous requirement to remove a records
     *
     * @param string $action valid if this request is Post and get profile permission
     * @return boolean
     */
    public static function okRequirements($action)
    {
        if (!Yii::$app->request->isPost) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Page not valid Please do not repeat this requirement.
                    All site traffic is being monitored'
                ),
                MSG_SECURITY_ISSUE
            );
            return false;
        }

        if (!Common::getProfilePermission($action)) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Your account don\'t have priviledges for this action,
                    please do not repeat this requirement. All site traffic is being monitored'
                ),
                MSG_SECURITY_ISSUE
            );
            return false;
        }

        return true;
    }

    /**
     * Verify if the variable $result has information (used for delete records of gridview)
     *
     * @param string $result
     * @return bool
     */
    public static function okSeleccionItems($result)
    {
        if (!isset($result)) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    'called to remove items,
                    but has not send selection of records to remove: Possible Security issue event?'
                ),
                MSG_SECURITY_ISSUE
            );
            return false;
        }
        return true;
    }

    /**
     * Resume of operation
     *
     * @param $deleteOK String with all the records deleted
     * @param $deleteKO string with all the records not deleted for some reason.
     */
    public static function summaryDisplay($deleteOK, $deleteKO)
    {
        if (isset($deleteOK{1})) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Records selected: \'{ids}\' has been deleted.',
                    ['ids' => $deleteOK]
                ),
                MSG_SUCCESS
            );
        }

        if (isset($deleteKO{1})) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Selected records: \'{ids}\' have not been deleted (they are being used in the system)',
                    ['ids' => $deleteKO]
                ),
                MSG_ERROR
            );
        }
    }

    /**
     * Generate a random string (default length 20 chars)
     *
     * @param int $length length chars to generate string
     * @return string random string
     */
    public static function randomString($length = 20)
    {

        $randstr = '';
        srand((double)microtime(true) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $totalChars = count($chars) - 1;
        for ($iterator = 0; $iterator <= $length; $iterator++) {
            $random = 0;
            try {
                $random = random_int(0, $totalChars);
            } catch (\Exception $e) {
                BaseController::bitacora(
                    Yii::t(
                        'app',
                        ERROR_MODULE,
                        [MODULE => 'app\controllers\BaseControllers::randomString', ERROR => $e]
                    ),
                    MSG_ERROR
                );
            }
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    /**
     *  show a status message of saving record
     *
     * @param boolean $status
     * @return void
     */
    public static function saveReport($status)
    {
        if ($status) {
            Yii::$app->session->setFlash(
                SUCCESS,
                Yii::t(
                    'app',
                    'Record saved successfully'
                )
            );
        } else {
            Yii::$app->session->setFlash(
                ERROR,
                Yii::t(
                    'app',
                    'Error saving record'
                )
            );
        }
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
