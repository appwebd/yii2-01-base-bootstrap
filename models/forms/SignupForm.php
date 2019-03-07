<?php
/**
  * Singup Form
  *
  * @package     Singup Form
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-06-16 23:03:06
  * @version     1.0
*/

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\helpers\Mail;
use app\components\UiComponent;

/**
 * User signup form
/**
 * User
 *
 * @property integer    active                      Active
 * @property string     auth_key                    key auth
 * @property integer    company_id                  Company associated to user
 * @property string     email                       Email
 * @property string     email_confirmation_token    Email token of confirmation
 * @property integer    email_is_verified           Boolean is email verified
 * @property string     firstName                   First Name
 * @property string     lastName                    Last Name
 * @property string     password_hash               password
 * @property string     password_reset_token        password reset token
 * @property string     password_reset_token_date   password reset token date creation
 * @property integer    profile_id                  Profile
 * @property string     telephone                   Phone number 12 digits
 * @property integer    user_id                     User
 * @property string     username                    User account
 * @property string     ipv4_address_last_login     Ipv4 address of last login
 *
 */
class SignupForm extends Model
{
    const EMAIL      = 'email';
    const FIRST_NAME = 'firstName';
    const LAST_NAME  = 'lastName';
    const STRING     = 'string';
    const USERNAME   = 'username';
    const PASSWORD   = 'password';
    const NEW_PASSWORD = 'new-password';
    const COMPANY_EMPTY = 0;
    const USER_ACTIVE = 1;
    const PROFILE_USER = 20;
    const EMAIL_IS_VERIFIED_FALSE= 0;

    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $password;
    /**
     * @var
     */
    public $firstName;
    /**
     * @var
     */
    public $lastName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[self::EMAIL, self::FIRST_NAME, self::LAST_NAME, self::USERNAME, self::PASSWORD], 'required'],
            [[self::EMAIL, self::FIRST_NAME, self::LAST_NAME, self::USERNAME], 'trim'],

            [self::USERNAME, 'unique', 'targetClass' => User::class],
            [self::USERNAME, self::STRING, 'min' => 2, 'max' => 255],

            [self::EMAIL, 'email'],
            [self::EMAIL, 'unique', 'targetClass' => User::class],

            [self::FIRST_NAME, STR_DEFAULT, VALUE => ''],
            [self::LAST_NAME, STR_DEFAULT, VALUE => ''],
            [self::FIRST_NAME, self::STRING, LENGTH => [1, 80]],
            [self::LAST_NAME, self::STRING, LENGTH => [1, 80]],

            [self::PASSWORD, self::STRING, LENGTH => [5, 254]],
        ];
    }

    /**
     * Signs up new user
     *
     * @return mixed app\model\User|null the saved user model or null if saving fails
     */
    public function singup()
    {
        if (!$this->checkFromEmail() || !$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username                 = $this->username;
        $user->email                    = $this->email;
        $user->email_is_verified        = SignupForm::EMAIL_IS_VERIFIED_FALSE;
        $user->email_confirmation_token = null;
        $user->firstName                = $this->firstName;
        $user->lastName                 = $this->lastName;
        $user->telephone                = '';
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailConfirmationToken(true);
        $user->profile_id               = SignupForm::PROFILE_USER; // 20: Usuario comun
        $user->ipv4_address_last_login  = Yii::$app->getRequest()->getUserIP();
        $user->active                   = SignupForm::USER_ACTIVE;
        $user->company_id               = SignupForm::COMPANY_EMPTY;
        $user->generatePasswordResetToken(true);

        if ($user->validate() && $user->save()) {
            Yii::info("OK your account was saved.", __METHOD__);
            $subject = Yii::t('app', 'Signup email of confirmation');
            if (!Mail::sendEmail($user, $subject, 'user/confirm-email')) { // app/mail/user/confirm-email-html.php
                Yii::warning(Yii::t('app', 'Failed to send confirmation email to new user.'), __METHOD__);
            }
            return $user;
        }

        UiComponent::warning('Could not save new user:', $user->errors);
        return null;
    }

    /**
     * Check if was defined params adminEmail in the file @app/config/params.php variable adminEmail.
     *
     * @return true|false
     */
    private function checkFromEmail()
    {
        $from   = Yii::$app->params['adminEmail'];
        if ($from == "email@example.com") {
            Yii::warning(
                Yii::t(
                    'app',
                    'The file config/params.php is incomplete (the variable adminEmail is missing)'
                ),
                __METHOD__
            );
            return false;
        }

        return true;
    }
}
