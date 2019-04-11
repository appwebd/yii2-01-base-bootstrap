<?php
/**
 * Informative status of events in all the platform
 *
 * @package     Model of Status
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:24
 * @version     1.0
 */

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * Status
 * Informative status of events in all the platform
 *
 * @property integer active        Active
 * @property string status_name   Status message
 * @property string created_at    Datetime created record
 * @property string updated_at    Datetime updated record
 * @property integer status_id     Status
 *
 */
class Status extends ActiveRecord
{
    const ACTIVE = 'active';
    const STATUS_ID = 'status_id';
    const STATUS_NAME = 'status_name';
    const TITLE = 'Status';

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * Get status description for a status_id
     *
     * @param int $statusID
     * @return string description od StatusId
     */
    public static function getStatusName($statusID)
    {
        switch ($statusID) {
            case 10:
                $return = INFO;
                break;
            case 20:
                $return = SUCCESS;
                break;
            case 30:
                $return = WARNING;
                break;
            case 40:
                $return = ERROR;
                break;
            case 50:
                $return = 'security issue';
                break;
            default:
                $return = INFO;
                break;
        }

        return $return;
    }

    /**
     * Get status description for a status_id
     *
     * @param $statusID
     * @return string
     */
    public static function getStatusBadge($statusID)
    {
        switch ($statusID) {
            case 10:
                $return = INFO;
                break;
            case 20:
                $return = SUCCESS;
                break;
            case 30:
                $return = WARNING;
                break;
            case 40:
                $return = ERROR;
                break;
            case 50:
                $return = ERROR;
                break;
            default:
                $return = INFO;
                break;
        }

        return $return;
    }

    /**
     * Get array from Informative status of events in all the platform
     * @return array
     */
    public static function getStatusList()
    {
        $droptions = [
            10 => INFO,
            20 => SUCCESS,
            30 => WARNING,
            40 => ERROR,
            50 => 'security issue',
        ];
        return ArrayHelper::map($droptions, self::STATUS_ID, self::STATUS_NAME);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [self::STATUS_NAME, STRING, 'max' => 80],
            [[self::STATUS_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::STATUS_NAME], 'trim'],
            [[self::STATUS_NAME], function ($attribute) {
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
            self::ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_ID => Yii::t('app', 'Status'),
            self::STATUS_NAME => Yii::t('app', 'Status message'),
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
     * @return ActiveQuery
     */
    public function getBlocked()
    {
        return $this->hasMany(
            Blocked::class,
            [self::STATUS_ID => self::STATUS_ID]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(
            Logs::class,
            [self::STATUS_ID => self::STATUS_ID]
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
