<?php
/**
 * Base Controllers
 *
 * @package     Base controllers
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private comercial license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 23:03:06
 * @version     1.0
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\queries\Common;
use app\models\Action;
use app\models\Logs;
use app\models\Controllers;
use app\models\Status;

class BaseController extends Controller
{
       
    const USER_ID_VISIT     = 1;
    const SHA256       = 'sha256';
    const ENCRIPTED_METHOD = 'AES-256-CBC';
    const SECRET_KEY = 'money20343';
    const SECRET_IV  = '2034312280';

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

        BaseController::bitacora(Yii::t('app', 'showing the view'), MSG_INFO);
        return false;
    }

    /**
     * Save in table logs all events and activities of this web application
     *
     * @param string  $event    events or activities
     * @param integer $statusId status_id related to table status
     * @param integer $userId   user_id related user
     * @return true|false if logs was saved
     */
    public static function bitacora($event, $statusId)
    {
        $errorValidation   = false;
        $model             = new Logs();
        $model->status_id  = $statusId;
        $model->event      = $event;
        $model->user_id    = Yii::$app->user->isGuest?self::USER_ID_VISIT: Yii::$app->user->identity->getId();

        $actionName        = Yii::$app->controller->action->id; // Action name
        $controllerName    = Yii::$app->controller->id;         // controller name

        $modelControllers  = Controllers::getControllers($controllerName);
        if ($modelControllers) {
            $model->controller_id    = $modelControllers->controller_id;
        } else {
            Controllers::addControllers($controllerName, 'not verified', 1, 0, 1);
            $modelControllers = Controllers::getControllers($controllerName);
            if ($modelControllers) {
                $model->controller_id = $modelControllers->controller_id;
            } else {
                $msg =Yii::t(
                    'app',
                    'Error creating controlller name: {controller_name}',
                    ['controller_name'=>$controllerName]
                );
                Yii::warning($msg, __METHOD__);
                $errorValidation = true;
            }
        }

        $modelAction = Action::getAction($actionName, $model->controller_id);
        if ($modelAction) {
            $model->action_id    = $modelAction->action_id;
        } else {
            Action::addAction($model->controller_id, $actionName, 'not verified', 1);
            $modelAction= Action::getAction($actionName, $model->controller_id);
            if ($modelAction) {
                $model->action_id = $modelAction->action_id;
            } else {
                $msg =Yii::t(
                    'app',
                    'Error creating action name: {action_name}',
                    ['action_name'=>$actionName]
                );
                Yii::warning($msg, __METHOD__);
                $errorValidation = true;
            }
        }



        if ($errorValidation) {
            return null;
        }

        $model->user_agent       = Yii::$app->request->userAgent;
        $model->ipv4_address     = Yii::$app->getRequest()->getUserIP();
        $model->ipv4_address_int = ip2long($model->ipv4_address);
        $model->confirmed        = 0;
    
        if ($model->save()) {
            return true;
        }

        echo Yii::$app->ui->warning('Could not save new log information:', $model->errors);
        return null;
    }

    /**
     * Save in table logs all events and activities of this web application and flash message respective
     *
     * @param string  $event    events or activities
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
     * Verify if the variable $result has information (used for delete records of gridview)
     *
     * @param string $result
     * @return bool
     */
    public static function requestPostSeleccionItems($result)
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
     * Previous requirement to remove a records
     *
     * @return void
     */
    public static function previousRequirementToRemoveRecords()
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

        if (!Common::getProfilePermission(ACTION_DELETE)) {
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
     * Resume of operation
     *
     * @param $deleteOK String with all the records deleted
     * @param $deleteKO string with all the records not deleted for some reason.
     */
    public static function resumeOperationRemove($deleteOK, $deleteKO)
    {
        if ($deleteOK != "") {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    'Records selected: \'{ids}\' has been deleted.',
                    ['ids' => $deleteOK]
                ),
                MSG_SUCCESS
            );
        }
        if ($deleteKO != "") {
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
     * Encode a string
     * @param $string
     * @return string
     */
    public static function stringEncode($string)
    {

        $encrypt_method = self::ENCRIPTED_METHOD;
        $secret_key     = self::SECRET_KEY;
        $secret_iv      = self::SECRET_IV;

        // hash
        $key    = hash(self::SHA256, $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv     = substr(hash(self::SHA256, $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);

        return  base64_encode($output);
    }

    /**
     * Decode a string
     * @param $string
     * @return string
     */
    public static function stringDecode($string)
    {

        $encrypt_method = self::ENCRIPTED_METHOD;
        $secret_key     = self::SECRET_KEY;
        $secret_iv      = self::SECRET_IV;

        // hash
        $key            = hash(self::SHA256, $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv             = substr(hash(self::SHA256, $secret_iv), 0, 16);
        return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
}
