<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use app\helpers\Mail;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    const STATUS_ACTIVE=1;

    public $email;
    const EMAIL = 'email';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [self::EMAIL, FILTER, FILTER => 'trim'],
            [self::EMAIL, 'required'],
            [self::EMAIL, self::EMAIL],
            [self::EMAIL, 'exist',
                'targetClass' => '\app\models\User',
                FILTER => ['active' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $model= User::find()
            ->andWhere([
                'active' => User::STATUS_ACTIVE,
                'email_is_verified' => 1,
                'email' => $this->email
            ])->one();

        if ($model->generatePasswordResetToken(true) &&
            Mail::sendEmail($model, 'reset password', 'user/password-reset-token')) {
            return true;
        }

        $this->addError(self::EMAIL, 'We can not reset the password for this user');
        return false;
    }
}
