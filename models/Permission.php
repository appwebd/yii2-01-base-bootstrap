<?php
/**
  * Permission
  *
  * @package     Model of Permission
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 20:29:23
  * @version     1.0
*/

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Permission
 * Permission
 *
 * @property int(11)         action_id             Action
 * @property tinyint(1)      action_permission     Action permission
 * @property int(11)         controller_id         Controller
 * @property int(11)         permission_id         Permission
 * @property int(11)         profile_id            Profile
 *
 */
class Permission extends \yii\db\ActiveRecord
{
    const ACTION_ID             = 'action_id';
    const ACTION_NAME           = 'action_name';
    const ACTION_PERMISSION     = 'action_permission';
    const CONTROLLER_ID         = 'controller_id';
    const CONTROLLER_NAME       = 'controller_name';
    const PERMISSION_ID         = 'permission_id';
    const PROFILE_ID            = 'profile_id';
    const PROFILE_NAME          = 'profile_name';
    const TABLE                 = 'permission';
    const TITLE                 = 'Permission';

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::ACTION_ID,
              self::ACTION_PERMISSION,
              self::CONTROLLER_ID,
              self::PROFILE_ID], 'required'],
            [[self::CONTROLLER_ID], 'in', 'range'=>array_keys(Controllers::getControllersList())],
            [[self::PROFILE_ID], 'in', 'range'=>array_keys(Profile::getProfileList())],
            [[self::ACTION_ID,
              self::CONTROLLER_ID,
              self::PERMISSION_ID,
              self::PROFILE_ID], 'integer'],
            [[self::ACTION_PERMISSION], 'boolean'],
         ];
    }

    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return [
            self::ACTION_ID       => Yii::t('app', 'Action'),
            self::ACTION_PERMISSION => Yii::t('app', 'Action permission'),
            self::CONTROLLER_ID   => Yii::t('app', 'Controller'),
            self::PERMISSION_ID   => Yii::t('app', 'Permission'),
            self::PROFILE_ID      => Yii::t('app', 'Profile'),

        ];
    }
    /**
     * behaviors
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(
            Action::className(),
            [self::ACTION_ID => self::ACTION_ID]
        );
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getControllers()
    {
        return $this->hasOne(
            Controllers::className(),
            [self::CONTROLLER_ID => self::CONTROLLER_ID]
        );
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProfile()
    {
        return $this->hasOne(
            Profile::className(),
            [self::PROFILE_ID => self::PROFILE_ID]
        );
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

    /**
     * Get array from Action
     * @return Arrayhelper::map
     */
    public static function getActionList()
    {

        $droptions = Action::find([ACTIVE=>1])
            ->orderBy([self::ACTION_NAME => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * Get array from Controllers
     * @return Arrayhelper::map
     */
    public static function getControllersList()
    {

        $droptions = Controllers::find([ACTIVE=>1])
               ->orderBy([self::CONTROLLER_NAME => SORT_ASC])
               ->asArray()->all();
        return ArrayHelper::map($droptions, self::CONTROLLER_ID, self::CONTROLLER_NAME);
    }

    /**
     * Get array from Users
     * @return Arrayhelper::map
     */
    public static function getProfileList()
    {
        $droptions = Profile::find(['active'=>1])
            ->orderBy([self::PROFILE_NAME => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::PROFILE_ID, self::PROFILE_NAME);
    }
}
