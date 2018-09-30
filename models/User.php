<?php
/**
  * Users
  *
  * @package     Model of Users
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-06-16 16:49:58
  * @version     1.0
*/

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use app\models\queries\UserQuery;

/**
 * User
 *
 * @property tinyint(1)    active                      Active
 * @property char(32)      auth_key                    key auth
 * @property int(11)       company_id                  Company association
 * @property char(254)     email                       Email
 * @property char(255)     email_confirmation_token    Email token of confirmation
 * @property tinyint(1)    email_is_verified           Boolean is email verified
 * @property char(80)      firstName                   First Name
 * @property char(80)      lastName                    Last Name
 * @property char(255)     password_hash               password
 * @property char(255)     password_reset_token        password reset token
 * @property datetime      password_reset_token_date   password reset token date creation
 * @property int(11)       profile_id                  Profile
 * @property char(15)      telephone                   Phone number 12 digits
 * @property int(11)       user_id                     User
 * @property char(20)      username                    User account


 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE=1;
    const STATUS_DELETED=0;
    const PROFILE_USER=20;
    const STATUS_FALSE=0;
    const STATUS_TRUE=1;
    const ACTIVE                        = 'active';
    const AUTH_KEY                      = 'auth_key';
    const COMPANY_ID                    = 'company_id';
    const EMAIL                         = 'email';
    const EMAIL_CONFIRMATION_TOKEN      = 'email_confirmation_token';
    const EMAIL_IS_VERIFIED             = 'email_is_verified';
    const FIRSTNAME                     = 'firstName';
    const IPV4_ADDRESS_LAST_LOGIN       = 'ipv4_address_last_login';
    const LASTNAME                      = 'lastName';
    const PASSWORD_HASH                 = 'password_hash';
    const PASSWORD_RESET_TOKEN          = 'password_reset_token';
    const PASSWORD_RESET_TOKEN_DATE     = 'password_reset_token_date';
    const PROFILE_ID                    = 'profile_id';
    const TELEPHONE                     = 'telephone';
    const USERNAME                      = 'username';
    const USER_ID                       = 'user_id';

    const TITLE                         = 'Users';

    /**
     * @var string|null the current password value from form input
     */

    public $_password;
    public $password;


    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::AUTH_KEY,
              self::COMPANY_ID,
              self::EMAIL,
              self::EMAIL_CONFIRMATION_TOKEN,
              self::EMAIL_IS_VERIFIED,
              self::FIRSTNAME,
              self::IPV4_ADDRESS_LAST_LOGIN,
              self::LASTNAME,
              self::PASSWORD_HASH,
              self::PROFILE_ID,
              self::USERNAME], 'required'],

            [self::AUTH_KEY, STRING, LENGTH => [1, 32]],
            [self::EMAIL, 'email'],
            [self::EMAIL, 'unique'],
            [self::EMAIL_CONFIRMATION_TOKEN, STRING, LENGTH => [1, 255]],
            [self::EMAIL_CONFIRMATION_TOKEN, 'unique'],
            [self::FIRSTNAME, STRING, LENGTH => [1, 80]],
            [self::LASTNAME, STRING, LENGTH => [1, 80]],
            [self::PASSWORD_HASH, STRING, LENGTH => [1, 255]],
            [self::PASSWORD_RESET_TOKEN, STRING, 'max' => 255],
            [[self::PROFILE_ID], 'in', 'range'=>array_keys(Profile::getProfileList())],
            [self::TELEPHONE, STRING, 'max' => 15],
            [[self::USERNAME, self::IPV4_ADDRESS_LAST_LOGIN], STRING, LENGTH => [1, 20]],
            [[self::COMPANY_ID,
              self::PROFILE_ID,
              self::USER_ID], 'integer'],
            [[self::ACTIVE,
              self::EMAIL_IS_VERIFIED], 'boolean'],
            [[self::AUTH_KEY,
              self::EMAIL,
              self::FIRSTNAME,
              self::LASTNAME,
              self::PASSWORD_HASH,
              self::PASSWORD_RESET_TOKEN,
              self::TELEPHONE,
              self::USERNAME], 'trim'],
            [[self::AUTH_KEY,
              self::EMAIL,
              self::FIRSTNAME,
              self::LASTNAME,
              self::PASSWORD_HASH,
              self::PASSWORD_RESET_TOKEN,
              self::TELEPHONE,
              self::USERNAME], function ($attribute) {
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
            self::ACTIVE                  => Yii::t('app', 'Active'),
            self::AUTH_KEY                => Yii::t('app', 'key auth'),
            self::COMPANY_ID              => Yii::t('app', 'Company'),
            self::EMAIL                   => Yii::t('app', 'Email'),
            self::EMAIL_CONFIRMATION_TOKEN => Yii::t('app', 'Email token of confirmation '),
            self::EMAIL_IS_VERIFIED       => Yii::t('app', 'Boolean is email verified '),
            self::FIRSTNAME               => Yii::t('app', 'First name'),
            self::IPV4_ADDRESS_LAST_LOGIN => Yii::t('app', 'Last ipv4 address used'),
            self::LASTNAME                => Yii::t('app', 'Last name'),
            self::PASSWORD_HASH           => Yii::t('app', 'password'),
            self::PASSWORD_RESET_TOKEN    => Yii::t('app', 'password reset token'),
            self::PASSWORD_RESET_TOKEN_DATE => Yii::t('app', 'password reset token date creation'),
            self::PROFILE_ID              => Yii::t('app', 'Profile'),
            self::TELEPHONE               => Yii::t('app', 'Phone number'),
            self::USERNAME                => Yii::t('app', 'User account'),
            self::USER_ID                 => Yii::t('app', 'user'),

        ];
    }

    /**
     * behaviors
     * The fields created_at, updated_at, password_reset_token_date should not
     * be declared as required in rules.
     */
    public function behaviors()
    {

        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at','password_reset_token_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Get array table user
     *
     * @return arrayHelper::map
     */
    public static function getUserList()
    {
        $droptions   = User::find([self::ACTIVE=>1])->asArray()->all();
        return ArrayHelper::map($droptions, self::USER_ID, self::USERNAME);
    }

    /**
     * @return UserQuery custom query class with user scopes
     */

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Find user by AccessToken
     *
     * @param $token string
     *
     * @return Static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([self::AUTH_KEY => $token]);
    }

    /**
     * Find Identity
     *
     * @param  $usu_usuarios_id integer Primary key table
     * @return Static
     */

    public static function findIdentity($userId)
    {
        return static::findOne([self::USER_ID => $userId, self::ACTIVE => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([self::USERNAME => $username, self::ACTIVE =>self::STATUS_ACTIVE]);
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
     * @getAuthKey
     */

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @param bool $save whether to save the record. Default is `false`.
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public function generatePasswordResetToken($save)
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        if ($save) {
            return $this->save();
        }
        return false;
    }

    /**
     * Generates new email confirmation token
     * @param bool $save whether to save the record. Default is `false`.
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public function generateEmailConfirmationToken($save)
    {
        $this->email_confirmation_token = Yii::$app->security->generateRandomString() . '_' . time();
        if ($save) {
            return $this->save();
        }
        return false;
    }

    /**
     * Resets to a new password and deletes the password reset token.
     * @param string $password the new password for this user.
     * @return bool whether the record was updated successfully
     */

    public function resetPassword($password)
    {
        $this->setPassword($password);
        $this->password_reset_token = null;
        return $this->save();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        if (!empty($password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * @validateAuthKey
     */

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(
            Company::className(),
            [self::COMPANY_ID => self::COMPANY_ID]
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
}
