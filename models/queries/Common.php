<?php
/**
  * Common routines
  *
  * @package     Common funcions
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-08-23 19:19:35
  * @version     1.0
*/

namespace app\models\queries;

use yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\Exception;
use app\controllers\BaseController;
use app\models\Action;
use app\models\Controllers;
use app\models\Permission;

class Common extends ActiveQuery
{
    const DENY_ACCESS = 0;
    const PERMIT_ACCESS = 1;
    const PROFILE_ID_ADMINISTRATOR = 99;
    const PROFILE_ID_VISIT = 0;
    const DONT_REMOVE= 1;
    const ROWS_ZERO = 0;
    const ROWS_ONE = 1;
    const USER_ID_VISIT = 0;

    /**
     * @param $dateInitial string in format YYYY-mm-dd H:i:s
     * @return string Get date time
     */
    public static function getDateDiffNow($dateInitial)
    {

        try {
            $now = Common::getNow();

            $return = 10000; // default any value greather than zero
            $differenceMinutes= (strtotime($now) - strtotime($dateInitial))/60; // answer in minutes
            if (isset($differenceMinutes)) {
                $return = $differenceMinutes;
            }
            return $return;
        } catch (Exception $exception) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => 'getDateDiffNow', ERROR => $exception]
                ),
                MSG_ERROR
            );
        }
        return 10000; // default any value greather than zero
    }

    /**
     * @param $table string name of table
     * @param $column string name of column
     * @param $field string name of column
     * @param $value  string value of column to compare
     * @return string description value
     */
    public static function getDescription($table, $column, $field, $value)
    {
        $return = '';
        try {
            $result = ((new Query())->select($column)
                ->from($table)
                ->where([$field => $value])
                ->limit(1)->createCommand())->queryColumn();
            $return = '';
            if (isset($result[0])) {
                $return = $result[0];
            }
        } catch (Exception $errorexception) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => '@app\models\queries\getDescription', ERROR => $errorexception]
                ),
                MSG_ERROR
            );
        }

        return $return;
    }

    /**
     * @param $table string table name
     * @return int value of count nro of records in table
     */
    public static function getNroRows($table)
    {

        $count = (new Query())->select('COUNT(*)')->from($table) ->limit(1);

        $return = Common::ROWS_ZERO;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    /**
     * @param $table string name of table in database
     * @param $field string column name in table
     * @param $value string value
     * @return int numbers of rows in other tables with integrity referential found.
     */
    public static function getNroRowsForeignkey($table, $field, $value)
    {
        $count = (new Query())->select('count(*)')
            ->from($table)
            ->where(["$field" => $value])
            ->limit(1);

        $return = Common::ROWS_ZERO;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    /**
     * @return string Get date time
     * @throws yii\db\Exception
     */
    public static function getNow()
    {
        $result = ((new Query())->select('now()')
            ->limit(1)->createCommand())->queryColumn();

        $return = date(DATEFORMAT);
        if (isset($result[0])) {
            $return = $result[0];
        }
        return $return;
    }

    /**
     * Check user permission for any resources like tables (Controllers/Action)
     * @param $actionName
     * @return int grant or deny access
     * @throws Exception
     */
    public static function getProfilePermission($actionName)
    {
        try {
            if (isset(Yii::$app->user->identity->profile->profile_id)) {
                $profileId = Yii::$app->user->identity->profile->profile_id;
            } else {
                $profileId = Common::PROFILE_ID_VISIT;
            }

            if ($profileId == Common::PROFILE_ID_ADMINISTRATOR) {
                return Common::PERMIT_ACCESS;
            }

            $controllerName = Yii::$app->controller->id;  // controller name
            $controllerId = Controllers::getControllerId($controllerName);
            $actionId = Action::getActionId($actionName);

            $actionPermission = Common::DENY_ACCESS;
            if (isset($controllerId) && isset($actionId)) {
                $actionPermission = Permission::getPermission($actionId, $controllerId, $profileId);
            }

            return $actionPermission;
        } catch (Exception $e) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE=> 'app\models\queries\Common::getProfilePermission', ERROR => $e]
                ),
                MSG_ERROR
            );
//            throw $errorException;
        }

        return Common::DENY_ACCESS;
    }

    /**
     * @param $showButtons string what buttons should show in the view
     * @return string
     * @throws Exception
     */
    public static function getProfilePermissionString($showButtons = '111')
    {
        $aButton = str_split($showButtons, 1);

        $template = '';
        if ($aButton[0] && Common::getProfilePermission('view')) {
            $template .=' {view} ';
        }

        if ($aButton[1] && Common::getProfilePermission('update')) {
            $template .=' {update} ';
        }

        if ($aButton[2] && Common::getProfilePermission('delete')) {
            $template .=' {delete}';
        }
        return $template;
    }

    /**
     * Get information for dropdown list
     * @param $model Classname defined in app\models to get information
     * @param $parentModelId String column related model
     * @param $valueId integer id to search in model
     * @param $key integer column to get column code
     * @param $value string column to get column description
     * @param $orderBy String Order by column
     * @return string String
     */
    public static function relatedDropdownList($model, $parentModelId, $valueId, $key, $value, $orderBy)
    {
        $rows = $model::find()->where([$parentModelId => $valueId])->orderBy([$orderBy => SORT_ASC])->all();

        $dropdown = Yii::t('app', 'Please select one option');
        $dropdown  = HTML_OPTION  . $dropdown . HTML_OPTION_CLOSE;

        if (count($rows)>0) {
            foreach ($rows as $row) {
                $dropdown  .= '<option value='.$row->$key.'>'.$row->$value . HTML_OPTION_CLOSE;
            }
        } else {
            $dropdown  .= HTML_OPTION . Yii::t('app', 'No results found') . HTML_OPTION_CLOSE;
        }

        return $dropdown ;
    }

    /**
     * @param $model mixed class defined in @app\models\
     * @param $method
     * @return bool Success o failed to create/update a $model in this view
     * @throws yii\db\Exception Failed to save a record error: {error}
     */
    public static function transaction(&$model, $method)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->$method()) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
        } catch (\Exception $exception) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => 'app\models\queries\Common::transaction method:'.$method, ERROR => $exception]
                ),
                MSG_ERROR
            );
            $transaction->rollBack();
        }

        return false;
    }

    /**
     * @param $sqlcode string sql instruction
     * @return bool int true/false answer: query was executed with errors?
     * @throws Exception
     */
    public static function sqlCreateCommand($sqlcode)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand($sqlcode)->execute();
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => 'app\models\queries\Common::sqlCreateCommand sqlcode:'.$sqlcode, ERROR => $e]
                ),
                MSG_ERROR
            );
            $transaction->rollBack();
//            throw $exception;
        } catch (\Throwable $e) {
            BaseController::bitacoraAndFlash(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => 'app\models\queries\Common::sqlCreateCommand sqlcode:'.$sqlcode, ERROR => $e]
                ),
                MSG_ERROR
            );
            $transaction->rollBack();
            //throw $exception;
        }
        return false;
    }
}
