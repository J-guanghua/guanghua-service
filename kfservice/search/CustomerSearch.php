<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\WxCustomer as Customer;

/**
 * ForumPostSearch represents the model behind the search form about `backend\models\discuz\ForumPost`.
 */
class CustomerSearch extends Customer
{
    
    public function rules()
    {
        return [
            [['fromusername','username','sign','status','avatar','appid'], 'string'],
            [['identity', 'updated_at', 'updated_at'], 'safe'],
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
        $query = Customer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => [
            //     'pagesize' => 30,
            // ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'appid'=>Yii::$app->wechat->appid,
            'fromusername' => $this->fromusername,
            'status' => $this->status,
        ]);
 	$query->andFilterWhere(['like', 'username', $this->username]);
        return $dataProvider;
    }
}
