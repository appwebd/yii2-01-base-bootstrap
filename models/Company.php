<?php
/**
  * Company
  *
  * @package     Model of Company
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-08-27 16:26:03
  * @version     1.0
*/

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\Product;

/**
 * Company
 * Company
 *
 * @property tinyint(1)      active              Active
 * @property char(100)       address             Address
 * @property int(11)         company_id          Company
 * @property char(60)        company_name        Name
 * @property char(254)       contact_email       Contact email
 * @property char(80)        contact_person      Contact person
 * @property char(20)        contact_phone_1     Contact phone
 * @property char(20)        contact_phone_2     Phone additional
 * @property char(20)        contact_phone_3     Phone additional
 * @property char(254)       webpage             URL Webpage
 *
 */
class Company extends \yii\db\ActiveRecord
{
    const ACTIVE = 'active';
    const ADDRESS = 'address';
    const COMPANY_ID = 'company_id';
    const COMPANY_NAME = 'company_name';
    const CONTACT_PERSON = 'contact_person';
    const CONTACT_EMAIL = 'contact_email';
    const CONTACT_PHONE_1 = 'contact_phone_1';
    const CONTACT_PHONE_2 = 'contact_phone_2';
    const CONTACT_PHONE_3 = 'contact_phone_3';
    const WEBPAGE = 'webpage';
    const TABLE = 'company';
    const TITLE = 'Customers';

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_PERSON], 'required'],
            [self::ADDRESS, STRING, LENGTH => [1, 100]],
            [self::COMPANY_ID, 'integer'],
            [self::COMPANY_NAME, STRING, LENGTH => [1, 60]],
            [self::CONTACT_EMAIL, STRING, 'max' => 254],
            [self::CONTACT_PERSON, STRING, LENGTH => [1, 80]],
            [self::CONTACT_PHONE_1, STRING, 'max' => 20],
            [self::CONTACT_PHONE_2, STRING, 'max' => 20],
            [self::CONTACT_PHONE_3, STRING, 'max' => 20],
            [self::WEBPAGE, STRING, 'max' => 254],
            [[self::ACTIVE], 'boolean'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], 'trim'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], function ($attribute) {
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
            self::ACTIVE        => Yii::t('app', 'Active'),
            self::ADDRESS       => Yii::t('app', 'Address'),
            self::COMPANY_ID    => Yii::t('app', 'Company'),
            self::COMPANY_NAME  => Yii::t('app', 'Company Name'),
            self::CONTACT_EMAIL => Yii::t('app', 'Contact email'),
            self::CONTACT_PERSON=> Yii::t('app', 'Contact person'),
            self::CONTACT_PHONE_1   => Yii::t('app', 'Contact phone'),
            self::CONTACT_PHONE_2   => Yii::t('app', 'Phone additional'),
            self::CONTACT_PHONE_3   => Yii::t('app', 'Phone additional'),
            self::WEBPAGE           => Yii::t('app', 'URL Webpage'),
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
        return 'company';
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProduct()
    {
        return $this->hasMany(Product::className(), [self::COMPANY_ID => self::COMPANY_ID]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasMany(Contact::className(), [self::COMPANY_ID => self::COMPANY_ID]);
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
     * @param $company_id
     */
    public static function getCompanyName($companyId)
    {
        $model = Company::find()->where([self::COMPANY_ID => $companyId])->one();
        $return = ' ';
        if (isset($model->company_name)) {
            $return = $model->company_name;
        }

        return $return;
    }

    /**
     * Get array from Company (1692)
     * @return Arrayhelper::map
     */
    public static function getCompanyList()
    {
        $droptions = Company::find([self::ACTIVE=>1])
                    ->orderBy([self::COMPANY_NAME => SORT_ASC])
                    ->asArray()->all();
        return ArrayHelper::map($droptions, self::COMPANY_ID, self::COMPANY_NAME);
    }
}
