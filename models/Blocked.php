<?php
/**
  * Ipv4 Blocked
  *
  * @package     Model of Blocked
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
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
 * Blocked
 * Ipv4 Blocked
 *
 * @property datetime        date                 date time
 * @property int(11)         id                   id
 * @property char(20)        ipv4_address         IPV4 address
 * @property bigint(20)      ipv4_address_int     IPV4 address integer
 * @property int(11)         status_id            Status
 *
 */
class Blocked extends \yii\db\ActiveRecord
{


    const DATE             = 'date';
    const ID               = 'id';
    const IPV4_ADDRESS     = 'ipv4_address';
    const IPV4_ADDRESS_INT = 'ipv4_address_int';
    const STATUS_ID        = 'status_id';
    const STATUS_NAME      = 'status_name';
    const TITLE            = 'Ipv4 Blocked';
    const STATUS_STATUS_NAME = 'status.status_name';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::DATE,
              self::IPV4_ADDRESS,
              self::IPV4_ADDRESS_INT,
              self::STATUS_ID], 'required'],
            [self::IPV4_ADDRESS, STRING, LENGTH => [1, 20]],
            [[self::STATUS_ID], 'in', 'range'=>array_keys(Status::getStatusList())],
            [[self::ID,
              self::IPV4_ADDRESS_INT,
              self::STATUS_ID], 'integer'],
            [[self::DATE], 'datetime'],
            [[self::IPV4_ADDRESS], 'trim'],
            [[self::IPV4_ADDRESS], function ($attribute) {
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
            self::DATE           => Yii::t('app', 'date time'),
            self::ID             => Yii::t('app', 'id'),
            self::IPV4_ADDRESS   => Yii::t('app', 'IPV4 address'),
            self::IPV4_ADDRESS_INT => Yii::t('app', 'IPV4 address integer'),
            self::STATUS_ID      => Yii::t('app', 'Status'),

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
        return 'blocked';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), [self::STATUS_ID => self::STATUS_ID]);
    }


    /**
     * Get array from Informative status of events in all the platform
     * @return Arrayhelper::map
     */
    public static function getStatusList()
    {
        $droptions = Status::find([self::ACTIVE=>1])->asArray()->all();
        return ArrayHelper::map($droptions, self::STATUS_ID, self::STATUS_NAME);
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
