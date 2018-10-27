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

use Yii;
use yii\helpers\HtmlPurifier;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Action
 * Actions
 *
 * @property char(80)        action_description     Description
 * @property int(11)         action_id              Actions
 * @property char(100)       action_name            Name
 * @property tinyint(1)      active                 Active
 *
 */
class Action extends \yii\db\ActiveRecord
{
    const ACTION_DESCRIPTION = 'action_description';
    const ACTION_NAME   = 'action_name';
    const ACTIVE        = 'active';
    const ACTION_ID     = 'action_id';
    const CONTROLLER_ID = 'controller_id';
    const CONTROLLER_CONTROLLER_NAME = 'controllers.controller_name';
    const TABLE = 'action';
    const TITLE = 'Actions';

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
            self::ACTION_ID        => Yii::t('app', 'Actions'),
            self::ACTION_NAME      => Yii::t('app', 'Name'),
            self::ACTIVE           => Yii::t('app', 'Active'),
            self::CONTROLLER_ID    => Yii::t('app', 'Controller'),
        ];
    }

    /**
     * Permits add a Action
     *
     * @param string $actionName Action Name
     * @param string $actionDesc A description of action
     * @param boolean $active Indicates records active
     * @return void
     */
    public static function addAction(
        $controllerId,
        $actionName,
        $actionDesc,
        $active
    ) {
        $model = new Action();
        $model->controller_id = $controllerId;
        $model->action_name = $actionName;
        $model->action_description = $actionDesc;
        $model->active = $active;

        if ($model->save()) {
            Yii::info(
                Yii::t(
                    'app',
                    'OK your action {actionName} was saved.',
                    ['actionName'=>$actionName]
                ),
                __METHOD__
            );
            return true;
        }

        Yii::$app->ui->warning(
            Yii::t('app', 'Could not save new Action:'),
            $model->errors
        );
        return false;
    }


    /**
     * behaviors
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                //'class' => 'yii\behaviors\TimestampBehavior',
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('now()'),
            ],
        ];
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'action';
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getControllers()
    {
        return $this->hasOne(Controllers::className(), [self::CONTROLLER_ID => self::CONTROLLER_ID]);
    }
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getLogs()
    {
        return $this->hasMany(Logs::className(), [self::ACTION_ID => self::ACTION_ID]);
    }

    /**
     * Get controller_name of Controllers table
     * @return Action
     */
    public static function getAction($actionName, $controllerId)
    {
        return static::findOne([self::ACTION_NAME => $actionName, self::CONTROLLER_ID=>$controllerId]);
    }

    /**
     * Get array from Actions
     * @return array
     */
    public static function getActionList()
    {
        $droptions = Action::find([self::ACTIVE=>1])->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }
    /**
     * Get array from Actions
     * @return Arrayhelper::map
     */
    public static function getActionListById($actionId)
    {
        $droptions = Action::find([self::CONTROLLER_ID=>$actionId])->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * Get array from Controller
     * @return Arrayhelper::map
     */
    public static function getControllersList()
    {
        $droptions = Controllers::find([self::ACTIVE=>1])
                    ->orderBy([self::CONTROLLER_NAME => SORT_ASC])
                    ->asArray()->all();
        return ArrayHelper::map($droptions, self::CONTROLLER_ID, self::CONTROLLER_NAME);
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
