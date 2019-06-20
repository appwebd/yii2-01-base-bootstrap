<?php


namespace app\models\queries;

use app\models\Action;
use app\models\Controllers;
use app\models\Logs;
use app\models\Status;
use Yii;
use yii\db\Exception;

class Bitacora extends Logs
{
    /**
     * Save in table logs all events and activities of this web application and flash message respective
     *
     * @param string $event events or activities
     * @param string $functionCode Name of function in source code
     * @param integer $statusId status_id related to table status
     * @return void
     */
    public function registerAndFlash($event, $functionCode, $statusId)
    {
        $this->register($event, $functionCode, $statusId);
        $badge = Status::getStatusBadge($statusId);
        Yii::$app->session->setFlash($badge, $event);
    }

    /**
     * Save in table logs all events and activities of this web application
     *
     * @param string $event events or activities
     * @param string $functionCode Name of function in source code
     * @param integer $statusId status_id related to table status
     * @return void
     */
    public function register($event, $functionCode, $statusId)
    {
        $model = new Logs();
        $model->status_id = $statusId;
        $model->functionCode = $functionCode;
        $model->event = $event;
        $model->user_id = UserMethods::getIdentityUserId();
        $model->controller_id = $this->getControllerId(Yii::$app->controller->id); // controller name
        $model->action_id = $this->getActionId(Yii::$app->controller->action->id, $model->controller_id); // Action name

        if ($model->controller_id == 0 || $model->action_id == 0) {
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
     * Get controller_id using controllerName to search
     *
     * @param string $controllerName Name of controller
     * @return int Controller ID
     */
    function getControllerId($controllerName)
    {
        $modelControllers = Controllers::getControllers($controllerName);
        if ($modelControllers) {
            $controllerId = $modelControllers->controller_id;
        } else {
            Controllers::addControllers($controllerName, 'not verified', 1, 0, 1);
            $modelControllers = Controllers::getControllers($controllerName);
            if ($modelControllers) {
                $controllerId = $modelControllers->controller_id;
            } else {
                $message = Yii::t(
                    'app',
                    'Error creating controlller name: {controller_name}',
                    ['controllerName' => $controllerName]
                );
                Yii::$app->session->setFlash(ERROR, $message);
                $controllerId = 0;
            }
        }
        return $controllerId;
    }

    function getActionId($actionName, $controllerId)
    {

        $modelAction = Action::getAction($actionName, $controllerId);
        if ($modelAction) {
            $actionId = $modelAction->action_id;
        } else {
            try {
                Action::addAction($controllerId, $actionName, 'not verified', 1);
            } catch (Exception $exception) {
                $message = Yii::t(
                    'app',
                    ERROR_MODULE,
                    [
                        MODULE => 'app\models\queries\Bitacora::getActionId',
                        ERROR => $exception
                    ]
                );
                Yii::$app->session->setFlash(ERROR, $message);
            }
            $modelAction = Action::getAction($actionName, $controllerId);
            if ($modelAction) {
                $actionId = $modelAction->action_id;
            } else {
                $mesage = Yii::t(
                    'app',
                    'Error creating action name: {action_name}',
                    ['action_name' => $actionName]
                );
                Yii::$app->session->setFlash(ERROR, $mesage);
                $actionId = 0;
            }
        }
        return $actionId;
    }
}