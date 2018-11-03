<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\queries\Common;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $userPrivateLocalClass = false;

    const USERNAME = 'username';
    const PASSWORD =  'password';
    const REMEMBER_ME = 'rememberMe';
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [[self::USERNAME, self::PASSWORD], 'required'],
            // rememberMe must be a boolean value
            [self::REMEMBER_ME, 'boolean'],
            // password is validated by validatePassword()
            [self::PASSWORD, 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            self::USERNAME   => Yii::t('app', 'Username'),
            self::PASSWORD   => Yii::t('app', 'Password'),
            self::REMEMBER_ME => Yii::t('app', 'Remember me'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function loginAdmin()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        } else {
            throw new NotFoundHttpException('Something is wrong with your user/pass');
        }
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->userPrivateLocalClass === false) {
            $this->userPrivateLocalClass = User::findByUsername($this->username);
        }

        return $this->userPrivateLocalClass;
    }
    /**
     * Removes email confirmation token and sets is_email_verified to true
     * @param bool $save whether to save the record. Default is `false`.
     * @return bool|null whether the save was successful or null if $save was false.
     */
    public static function removeTokenEmail($userId)
    {
        $model = User::findIdentity($userId);
        if ($model !==null) {
            $model->email_confirmation_token = null;
            $model->email_is_verified = 1;
            if (Common::transaction($model, 'save')) {
                return true;
            }
        }
        return false;
    }
}
