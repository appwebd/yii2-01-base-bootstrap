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
     * @param string|array $event events or activities
     * @param string $functionCode Name of function in source code
     * @param integer $statusId status_id related to table status
     * @return void
     */
    public function register($event, $functionCode, $statusId)
    {
        $model = new Logs();
        $model->status_id = $statusId;
        $model->functionCode = $functionCode;

        if (is_array($event)) {
            $error = print_r($event, true);
        } else {
            $error  = $event;
        }
        $model->event = substr($error, 0, 250);

        $usero = new UserMethods();
        $model->user_id = $usero->getUserId();
        $model->controller_id = $this->getControllerId(Yii::$app->controller->id); // controller name
        $model->action_id = $this->getActionId(Yii::$app->controller->action->id, $model->controller_id); // Action name

        if ($model->controller_id == 0 || $model->action_id == 0) {
            $message = Yii::t(
                'app',
                'Could not save new log information: {error}',
                [
                    'error' => print_r($model->errors, true)
                ]
            );
            Yii::$app->session->setFlash(ERROR, $message);
        } else {
            $model->user_agent = substr(Yii::$app->request->userAgent, 0, 250);
            $model->ipv4_address = substr(
                Yii::$app->getRequest()->getUserIP(),
                0,
                20
            );
            $model->ipv4_address_int = ip2long($model->ipv4_address);
            $model->confirmed = 0;
            try {
                if ($model->validate()) {
                    $model->save();
                }
            } catch (Exception $exception) {
                $message = Yii::t(
                    'app',
                    'Could not save new log information: {error}',
                    [
                        'error' => print_r($model->errors, true)
                    ]
                );
                Yii::$app->session->setFlash(ERROR, $message);
            }
        }
    }

    /**
     * Get controller_id using controllerName to search
     *
     * @param string $controller_name Name of controller
     *
     * @return int Controller ID
     */
    public function getControllerId($controller_name)
    {
        $model_controller = Controllers::getControllers($controller_name);
        if ($model_controller) {
            $controller_id = $model_controller->controller_id;
        } else {
            Controllers::addControllers($controller_name, 'not verified', 1, 0, 1);
            $model_controller = Controllers::getControllers($controller_name);
            if ($model_controller) {
                $controller_id = $model_controller->controller_id;
            } else {
                $message = Yii::t(
                    'app',
                    'Error creating controlller name: {controller_name}',
                    ['controllerName' => $controller_name]
                );
                Yii::$app->session->setFlash(ERROR, $message);
                $controller_id = 0;
            }
        }
        return $controller_id;
    }

    /**
     * Get action_id of table action
     *
     * @param string $action_name Name of action
     * @param int $controller_id controller_id primary key of table controller
     *
     * @return int
     */
    public function getActionId($action_name, $controller_id)
    {
        $model_action = Action::getAction($action_name, $controller_id);
        if ($model_action) {
            $action_id = $model_action->action_id;
        } else {
            try {
                Action::addAction($controller_id, $action_name, 'not verified', 1);
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
            $model_action = Action::getAction($action_name, $controller_id);
            if ($model_action) {
                $action_id = $model_action->action_id;
            } else {
                $mesage = Yii::t(
                    'app',
                    'Error creating action name: {action_name}',
                    ['action_name' => $action_name]
                );
                Yii::$app->session->setFlash(ERROR, $mesage);
                $action_id = 0;
            }
        }
        return $action_id;
    }
}
