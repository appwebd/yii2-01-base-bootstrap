<?php
/**
  * Profiles
  *
  * @package     Model of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 20:29:24
  * @version     1.0
*/

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * Profile
 * Profiles
 *
 * @property tinyint(1)      active           Active
 * @property int(11)         profile_id       Profile
 * @property char(80)        profile_name     Name
 *
 */
class Profile extends ActiveRecord
{

    const ACTIVE           = 'active';
    const PROFILE_ID       = 'profile_id';
    const PROFILE_NAME     = 'profile_name';
    const TITLE            = 'Profiles';
    const CREATED_AT       = 'created_at';
    const UPDATE_AT        = 'updated_at';

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::PROFILE_NAME], 'required'],
            [self::PROFILE_NAME, STRING, LENGTH => [1, 80]],
            [[self::PROFILE_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::PROFILE_NAME], 'trim'],
            [[self::PROFILE_NAME], function ($attribute) {
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
            self::ACTIVE     => Yii::t('app', 'Active'),
            self::PROFILE_ID => Yii::t('app', 'Profile'),
            self::PROFILE_NAME => Yii::t('app', 'Name'),

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
                    ActiveRecord::EVENT_BEFORE_INSERT => [self::CREATED_AT, self::UPDATE_AT],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [self::UPDATE_AT],
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
        return 'profile';
    }

    /**
     * Get a Profile name given a profile_id
     *
     * @param integer $profile_id
     * @return void
     */
    public static function getProfileName($profileId)
    {
        $model = Profile::find()->where([self::PROFILE_ID => $profileId])->one();

        $return = ' ';
        if (isset($model->profile_name)) {
            $return = $model->profile_name;
        }

        return $return;
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPermission()
    {
        return $this->hasMany(Permission::className(), [self::PROFILE_ID => self::PROFILE_ID]);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasMany(User::className(), [self::PROFILE_ID => self::PROFILE_ID]);
    }

    /**
     * Get array from Profiles
     * @return array
     */
    public static function getProfileList()
    {
        $droptions = Profile::find(['active'=>1])->orderBy(self::PROFILE_NAME)->asArray()->all();
        return ArrayHelper::map($droptions, self::PROFILE_ID, self::PROFILE_NAME);
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
