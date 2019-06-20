<?php


namespace app\models\queries;

use app\models\User;
use Yii;

class UserMethods extends User
{
    /**
     * @inheritdoc
     */
    /* modified */
    /**
     * Finds user by password reset token
     *
     * @param  string $token password reset token
     * @return object|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Get profile_id of user
     *
     * @param int $userId
     * @return bool|int
     */
    public static function getProfileUser($userId)
    {
        $model = static::findOne($userId);
        if ($model !== null) {
            return $model->profile_id;
        }

        return false;
    }

    /**
     * Get model user given username
     *
     * @param int $userId primary key of table User
     * @return User|null
     */
    public static function getUsername($userId)
    {
        return static::findOne([self::USER_ID => $userId]);
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
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Get the user_id of table user
     * @return int user_id primary key of table user
     */
    public static function getIdentityUserId()
    {
        return Yii::$app->user->isGuest ? User::USER_ID_VISIT : Yii::$app->user->identity->getId();
    }
}