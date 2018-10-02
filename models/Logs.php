<?php
/**
  * Logs (user bitacora)
  *
  * @package     Model of Logs
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
use yii\helpers\HtmlPurifier;

/**
 * Logs
 * Logs (user bitacora)
 *
 * @property tinyint(1)      confirmed            ipv4 address confirmed
 * @property int(11)         controller_id        Controller
 * @property int(11)         action_id            Action
 * @property datetime        date                 date
 * @property char(80)        event                Activity / Event
 * @property char(20)        ipv4_address         ipv4_address
 * @property bigint(20)      ipv4_address_int     ipv4_address integer
 * @property int(11)         logs_id              Logs
 * @property int(11)         status_id            Status
 * @property char(250)       user_agent           user agent browser
 * @property int(11)         user_id              User
 *
 */
class Logs extends \yii\db\ActiveRecord
{
    const ACTION_ID            = 'action_id';
    const CONFIRMED            = 'confirmed';
    const CONTROLLER_ID        = 'controller_id';
    const DATE                 = 'date';
    const EVENT                = 'event';
    const IPV4_ADDRESS         = 'ipv4_address';
    const IPV4_ADDRESS_INT     = 'ipv4_address_int';
    const LOGS_ID              = 'logs_id';
    const STATUS_ID            = 'status_id';
    const USER_AGENT           = 'user_agent';
    const USER_ID              = 'user_id';

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::ACTION_ID,
              self::CONFIRMED,
              self::CONTROLLER_ID,
              self::EVENT,
              self::IPV4_ADDRESS,
              self::IPV4_ADDRESS_INT,
              self::STATUS_ID,
              self::USER_AGENT,
              self::USER_ID], 'required'],
            [[self::ACTION_ID], 'in', RANGE=>array_keys(Action::getActionList())],
            [[self::CONTROLLER_ID], 'in', RANGE=>array_keys(Controllers::getControllersList())],
            [self::EVENT, STRING, LENGTH => [1, 80]],
            [self::IPV4_ADDRESS, STRING, LENGTH => [1, 20]],
            [self::USER_AGENT, STRING, LENGTH => [1, 250]],
            [[self::USER_ID], 'in', RANGE=>array_keys(User::getUserList())],
            [[self::ACTION_ID,
              self::CONTROLLER_ID,
              self::IPV4_ADDRESS_INT,
              self::LOGS_ID,
              self::STATUS_ID,
              self::USER_ID], 'integer'],
            [[self::CONFIRMED], 'boolean'],
            [[self::DATE], 'datetime'],
            [[self::EVENT,
              self::IPV4_ADDRESS,
              self::USER_AGENT], 'trim'],
            [[self::EVENT,
              self::IPV4_ADDRESS,
              self::USER_AGENT], function ($attribute) {
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
            self::ACTION_ID      => Yii::t('app', 'Action'),
            self::CONFIRMED      => Yii::t('app', 'ipv4 address confirmed'),
            self::CONTROLLER_ID  => Yii::t('app', 'Controller'),
            self::DATE           => Yii::t('app', 'date'),
            self::EVENT          => Yii::t('app', 'Activity / Event'),
            self::IPV4_ADDRESS   => Yii::t('app', 'ipv4_address'),
            self::IPV4_ADDRESS_INT => Yii::t('app', 'ipv4_address integer'),
            self::LOGS_ID        => Yii::t('app', 'Logs'),
            self::STATUS_ID      => Yii::t('app', 'Status'),
            self::USER_AGENT     => Yii::t('app', 'user agent browser'),
            self::USER_ID        => Yii::t('app', 'User'),

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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date'],
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
        return 'logs';
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
    public function getStatus()
    {
        return $this->hasOne(
            Status::className(),
            [self::STATUS_ID => self::STATUS_ID]
        );
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(
            User::className(),
            [self::USER_ID => self::USER_ID]
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
}
