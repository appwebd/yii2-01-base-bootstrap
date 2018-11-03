<?php
namespace app\models\forms;

use Yii;
use app\controllers\BaseController;
use yii\base\Model;
use app\helpers\Mail;
use app\models\queries\Common;
use app\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    const EMAIL = 'email';
    const STATUS_ACTIVE = 1;
    const RANGE_MINUTES_TOKEN = 60;

    /**
     * @var string user email that exists in table User
     */

    public $email;
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
                'message' => Yii::t('app', 'There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     * @param email string Email of Password Reset Request form
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $model = User::find()
            ->andWhere([
                'active' => User::STATUS_ACTIVE,
                'email_is_verified' => User::EMAIL_IS_VERIFIES_VALUE,
                'email' => $email
            ])->one();

        if ($model !== null && $model->generatePasswordResetToken(true) &&
            Mail::sendEmail($model, 'reset password', 'user/password-reset-token')) {
            return true;
        }

        $this->addError(self::EMAIL, Yii::t('app', 'We can not reset the password for this user'));
        return false;
    }

    /**
     * @param $passwordresettoken String password reset token saved in model User
     * @param $userId integer primary key of table User
     * @return string  String encoded with infomation password Reset Token | datetime | User_id
     */
    public static function generateToken($passwordresettoken, $userId)
    {

        $token = $passwordresettoken . '|'. date('Y-m-d H:i:s') . '|' . $userId;
        return BaseController::stringEncode($token);
    }

    /**
     * @param $tokendecode string format of generateToken
     * @return bool
     * @throws \yii\db\Exception
     */
    public function tokenIsValid($tokendecode)
    {


        $valid = true;
        if ($tokendecode === false || !isset($tokendecode{1})) {
            $valid = false;
        }

        $tokenarray = explode('|', $tokendecode);

        if (count($tokenarray) <> 3) {
            $valid = false;
        }

        // We need to verity date time validity.
        if (Common::getDateDiffNow($tokenarray[1])>PasswordResetRequestForm::RANGE_MINUTES_TOKEN) {
            $valid = false;
        }

        $model = User::findOne(['user_id' => $tokenarray[2]]);
        if ($model ==null) {
            $valid = false;
        } else {
            if ($model->password_reset_token !== $tokenarray[0]) {
                $valid = false;
            }
        }

        if (!$valid) {
            return false;
        }

        return true;
    }

    /**
     * @param $tokendecode string token decoded
     * @return integer primary key user_id of table user
     */
    public function getUserid($tokendecode)
    {
        $tokenarray = explode('|', $tokendecode);
        return $tokenarray[2];
    }
}
