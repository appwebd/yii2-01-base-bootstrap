<?php
/**
  * Common routines
  *
  * @package     Common funcions
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-08-23 19:19:35
  * @version     1.0
*/


namespace app\models\queries;

use yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\models\Controllers;
use app\models\Action;

class Common extends ActiveQuery
{
    public static function getNroRows($table)
    {

        $count = (new Query())->select('COUNT(*)')->from($table) ->limit(1);

        $return = 0;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    public static function getNroRowsForeignkey($table, $field, $value)
    {
        $count = (new Query())->select('COUNT(*)')
            ->from($table)
            ->where(["$field"=>$value])
            ->limit(1);

        $return = 0;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    /**
     * Check user permission for any resources like tables (Controllers/Action)
     * @param $action_name
     */
    public static function getProfilePermission($actionName)
    {
        $profileId = Yii::$app->user->identity->profile->profile_id;
        if ($profileId==99) {  // 99=profile administrator
            return 1;          // OK = 1 = Permit access
        }

        $controllerName    = Yii::$app->controller->id;  // controller name

        $controllerId = ((new Query())->select('controller_id')
            ->from('controllers')
            ->where(["controller_name"=>$controllerName])
            ->limit(1)->createCommand())->queryColumn();

        $actionId = ((new Query())->select('action_id')
            ->from('action')
            ->where(["action_name"=>$actionName])
            ->limit(1)->createCommand())->queryColumn();


        $actionPermission = 0;
        if (isset($controllerId[0]) && isset($actionId[0])) {
            $actionPermission = Yii::$app->db->createCommand(
                "SELECT action_permission
                     FROM permission
                     WHERE action_id=".$actionId[0]."
                   and controller_id=".$controllerId[0] . " and profile_id=". $profileId
            )->queryOne();

            if (!isset($actionPermission) || $actionPermission===false) {
                $actionPermission=0;
            }
        }

        return $actionPermission;
    }

    public static function getProfilePermissionString()
    {
        $template = '';
        if (Common::getProfilePermission('view')) {
            $template .='{view}';
        }

        if (Common::getProfilePermission('update')) {
            $template .=' {update}';
        }

        if (Common::getProfilePermission('delete')) {
            $template .=' {delete}';
        }
        return $template;
    }
}
