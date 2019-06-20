<?php
/**
 * Users
 *
 * @package     Model of Users
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 16:49:58
 * @version     1.0
 */

namespace app\models\forms;

use app\controllers\BaseController;
use app\models\User;
use Exception;
use Yii;
use yii\base\Model;
use app\models\queries\Bitacora;

/**
 * User
 *
 * @property integer active                      Active
 * @property string password_hash               password
 * @property integer user_id                     User
 *
 */
class PasswordResetForm extends Model
{
    const PASSW0RD = 'passw0rd';
    const USER_ID = 'user_id';
    const TITLE = 'Users';

    public $passw0rd;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[
                self::PASSW0RD
            ], 'required'],

            [[self::PASSW0RD], STRING, LENGTH => [8, 255]],
            [[self::USER_ID], STRING],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            self::PASSW0RD => Yii::t('app', 'password'),
            self::USER_ID => Yii::t('app', 'user'),

        ];
    }

    /**
     * @param @app\models\forms\PasswordResetForm $modelForm
     * @return bool
     * @throws Exception
     */
    public function passwordUpdate($modelForm)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $userId  = BaseController::stringEncode($modelForm->user_id);
            $model = User::findOne($userId);
            if ($model !== null) {
                $model->setPassword($modelForm->passw0rd);
                $model->password_reset_token = null;
                if ($model->save()) {
                    $transaction->commit();
                    return true;
                }
            }

        } catch (Exception $exception) {
            $event = Yii::t('app', 'Error, updating password {error}', ['error' => $exception]);
            $bitacora = new Bitacora();
            $bitacora->register($event, 'passwordUpdate', MSG_ERROR);
            $transaction->rollBack();
        }

        return false;
    }
}
