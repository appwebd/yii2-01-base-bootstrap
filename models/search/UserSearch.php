<?php
/**
  * User Search
  *
  * @package     Model Search of User
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-14 23:18:42
  * @version     1.0
*/

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */

class UserSearch extends User
{
    const STR_LABEL = 'label';
    const ACTIVE                        = 'active';
    const AUTH_KEY                      = 'auth_key';
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

    public function attributes()
    {
        return array_merge(parent::attributes(), [
                                                   'profile.profile_id',
                                                   'logs.user_id',

                                                  ]);
    }

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[
              self::IPV4_ADDRESS_LAST_LOGIN,
              self::PROFILE_ID,
              self::USER_ID], 'integer'],
            [[self::ACTIVE,
              self::EMAIL_IS_VERIFIED], 'boolean'],
            [[self::EMAIL,
              self::FIRSTNAME,
              self::LASTNAME,
              self::TELEPHONE,
              self::USERNAME], 'safe'],
         ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
      // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $query->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort'=>['attributes' => ['user_id desc']]
        ]);

        /**
        * Setup your sorting attributes
        */
        $dataProvider->setSort([
            'defaultOrder' => ['user_id'=>SORT_DESC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user.user_id'           => $this->user_id,
            'user.email_is_verified' => $this->email_is_verified,
            'user.active'            => $this->active,
            'user.profile_id'        => $this->profile_id,
            'profile.profile_id'     => $this->profile_id

        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'firstName', $this->firstName])
              ->andFilterWhere(['like', 'lastName', $this->lastName])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'telephone', $this->telephone])
              ->andFilterWhere(['like', 'ipv4_address_last_login', $this->ipv4_address_last_login]);

        return $dataProvider;
    }

    /**
     * Get array from Sub categories
     * @return array
     */
    public static function getUserListSearch($table)
    {
        $sqlcode = "SELECT DISTINCT ". self::USER_ID . ", " . self::USERNAME . "
                    FROM user
                    WHERE ".self::USER_ID." in (SELECT DISTINCT ".self::USER_ID."
                                                          FROM $table)
                    ORDER BY ".self::USERNAME;
        $droptions = User::findBySql($sqlcode)
                     ->orderBy([self::USERNAME => SORT_ASC])
                     ->asArray()->all();
        return ArrayHelper::map($droptions, self::USER_ID, self::USERNAME);
    }
}
