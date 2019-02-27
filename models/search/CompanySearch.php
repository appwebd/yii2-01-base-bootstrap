<?php
/**
  * Company
  *
  * @package     Model Search of Company
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-08-27 16:26:03
  * @version     1.0
*/

namespace app\models\search;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Company;

/**
 * CompanySearch represents the model behind the search form about `app\models\Company`.
 */

class CompanySearch extends Company
{
    const ACTIVE              = 'active';
    const ADDRESS             = 'address';
    const COMPANY_ID          = 'company_id';
    const COMPANY_NAME        = 'company_name';
    const CONTACT_EMAIL       = 'contact_email';
    const CONTACT_PERSON      = 'contact_person';
    const CONTACT_PHONE_1     = 'contact_phone_1';
    const CONTACT_PHONE_2     = 'contact_phone_2';
    const CONTACT_PHONE_3     = 'contact_phone_3';
    const WEBPAGE             = 'webpage';

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
                                                   'contact.company_id',
                                                   'product.company_id',
                                                   'tickets.company_id',
                                                   'user.company_id',
                                                  ]);
    }

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [[self::COMPANY_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], 'safe'],

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
        $query = Company::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
        * Setup your sorting attributes
        */
        $dataProvider->setSort([
            'defaultOrder' => ['company_id'=>SORT_ASC],

        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'company.active'                => $this->active,
            'company.company_id'            => $this->company_id,
            'contact.company_id'            => $this->company_id,
            'product.company_id'            => $this->company_id,
            'tickets.company_id'            => $this->company_id,
            'user.company_id'               => $this->company_id,

        ]);


        $query->andFilterWhere(['like', 'company.address', $this->address])
              ->andFilterWhere(['like', 'company.company_name', $this->company_name])
              ->andFilterWhere(['like', 'company.contact_email', $this->contact_email])
              ->andFilterWhere(['like', 'company.contact_person', $this->contact_person])
              ->andFilterWhere(['like', 'company.contact_phone_1', $this->contact_phone_1])
              ->andFilterWhere(['like', 'company.contact_phone_2', $this->contact_phone_2])
              ->andFilterWhere(['like', 'company.contact_phone_3', $this->contact_phone_3])
              ->andFilterWhere(['like', 'company.webpage', $this->webpage]);

        return $dataProvider;
    }


    /**
     * Get array from Company
     * @param $table string name of table
     * @return array
     */
    public static function getCompanyListSearch($table)
    {
        $sqlcode = "SELECT DISTINCT ". self::COMPANY_ID . ", " . self::COMPANY_NAME . "
                    FROM company
                    WHERE ".self::COMPANY_ID." in (SELECT DISTINCT ".self::COMPANY_ID."
                                                          FROM $table)
                    ORDER BY ".self::COMPANY_NAME;
        $droptions = Company::findBySql($sqlcode)
                     ->orderBy([self::COMPANY_NAME => SORT_ASC])
                     ->asArray()->all();
        return ArrayHelper::map($droptions, self::COMPANY_ID, self::COMPANY_NAME);
    }
}

