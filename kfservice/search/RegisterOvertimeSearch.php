<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\RegisterOvertime;

/**
 * RegisterOvertimeSearch represents the model behind the search form about `backend\modules\kfservice\models\RegisterOvertime`.
 */
class RegisterOvertimeSearch extends RegisterOvertime
{
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'services_id', 'message_id', 'overtime'], 'integer'],
            [['fromusername','username','created_at'], 'safe'],
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
        $query = RegisterOvertime::find();
        $query->joinWith(['storage']);//==>Õâ±ß±íÒÑ¾­¹ØÁªÁË
        $query->select("wx_register_overtime.*,wx_customer_mongo.username");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'services_id' => $this->services_id,
            'fromusername' => $this->fromusername,
            'overtime' => $this->overtime,
        ]);
        if($this->created_at!=null && $end_time = explode(' - ', $this->created_at)){
            $start_time = strtotime($end_time[0]);
            $end_time = strtotime($end_time[1]);
            $query->andFilterWhere(['between', 'wx_register_overtime.created_at', $start_time, $end_time]);
        }
        $query->andFilterWhere(['like', 'wx_customer_mongo.username', $this->username]);

        return $dataProvider;
    }
}
