<?php
/**
 * Actions
 *
 * @package     Model of Action
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-08-02 20:07:02
 * @version     1.0
 */

namespace app\models;

use app\controllers\BaseController;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * Action
 * Actions
 *
 * @property string action_description     Description
 * @property int action_id              Actions
 * @property int controller_id          Controller Id associated
 * @property string action_name            Name
 * @property int active                 Active
 *
 */
class Action extends ActiveRecord
{
    const ACTION_DESCRIPTION = 'action_description';
    const ACTION_NAME = 'action_name';
    const ACTIVE = 'active';
    const ACTION_ID = 'action_id';
    const CONTROLLER_ID = 'controller_id';
    const CONTROLLER_CONTROLLER_NAME = 'controllers.controller_name';
    const TABLE = 'action';
    const TITLE = 'Actions';

    /**
     * Permits add a Action
     *
     * @param $controllerId int primary key of controllers
     * @param string $actionName Action Name
     * @param string $actionDesc A description of action
     * @param boolean $active Indicates records active
     * @return int
     * @throws \yii\db\Exception
     */
    public static function addAction(
        $controllerId,
        $actionName,
        $actionDesc,
        $active
    )
    {
        $model = new Action();
        $model->controller_id = $controllerId;
        $model->action_name = $actionName;
        $model->action_description = $actionDesc;
        $model->active = $active;

        if (Common::transaction($model, 'save')) {
            $message = Yii::t(
                'app',
                'OK your action {actionName} was saved.',
                ['actionName' => $actionName]
            );
            Yii::$app->session->setFlash(ERROR, $message);
            return true;
        }

        $message = yii::t(
            'app',
            'Could not save new Action: {error}\n',
            [
                'error' => print_r($model->errors, true)
            ]
        );
        Yii::$app->session->setFlash(ERROR, $message);
        return false;
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return Action::TABLE;
    }

    /**
     * Get controller_name of Controllers table
     * @param $actionName string name of action_name
     * @param $controllerId int primary key of table controllers
     * @return Action
     */
    public static function getAction($actionName, $controllerId)
    {
        return static::findOne([self::ACTION_NAME => $actionName, self::CONTROLLER_ID => $controllerId]);
    }

    /**
     * get column action_id of table action given action_name
     *
     * @param string $actionName column action_name of table actions
     * @return int column of action_id
     * @throws \yii\db\Exception
     */
    public static function getActionId($actionName)
    {
        try {
            $actionId = ((new Query())->select('action_id')
                ->from(Action::TABLE)
                ->where(["action_name" => $actionName])
                ->limit(1)->createCommand())->queryColumn();
            if (isset($actionId[0])) {
                return $actionId[0];
            }
        } catch (Exception $errorexception) {
            BaseController::bitacora(
                Yii::t(
                    'app',
                    ERROR_MODULE,
                    [MODULE => 'getActionId', ERROR => $errorexception]
                ),
                MSG_ERROR
            );
        }
        return null;
    }

    /**
     * Get array from Actions
     * @return array
     */
    public static function getActionList()
    {
        $droptions = Action::find()->where([self::ACTIVE => 1])->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * Get array from Actions
     * @param $actionId integer primary key of table action
     * @return array
     */
    public static function getActionListById($actionId)
    {
        $droptions = Action::find()->where([self::CONTROLLER_ID => $actionId])->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTION_DESCRIPTION,
                self::ACTION_NAME,
                self::ACTIVE,
                self::CONTROLLER_ID], 'required'],
            [self::ACTION_DESCRIPTION, STRING, LENGTH => [1, 80]],
            [self::ACTION_NAME, STRING, LENGTH => [1, 100]],
            [[self::ACTION_ID,
                self::CONTROLLER_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::ACTION_DESCRIPTION,
                self::ACTION_NAME], 'trim'],
            [[self::ACTION_DESCRIPTION,
                self::ACTION_NAME], function ($attribute) {
                $this->$attribute = HtmlPurifier::process($this->$attribute);
            }
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            self::ACTION_DESCRIPTION => Yii::t('app', 'Description'),
            self::ACTION_ID => Yii::t('app', 'Actions'),
            self::ACTION_NAME => Yii::t('app', 'Name'),
            self::ACTIVE => Yii::t('app', 'Active'),
            self::CONTROLLER_ID => Yii::t('app', 'Controller'),
        ];
    }

    /**
     * behaviors
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                //'class' => 'yii\behaviors\TimestampBehavior',
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('now()'),
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getControllers()
    {
        return $this->hasOne(Controllers::class, [self::CONTROLLER_ID => self::CONTROLLER_ID]);
    }

    /**
     * @return ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Logs::class, [self::ACTION_ID => self::ACTION_ID]);
    }

    /**
     * Get primary key id
     *
     * @return integer primary key
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
}
