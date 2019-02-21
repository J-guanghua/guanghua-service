<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\ReviewMongo;

/**
 * ReviewMongoSearch represents the model behind the search form about `backend\modules\kfservice\models\ReviewMongo`.
 */
class ReviewMongoSearch extends ReviewMongo
{
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'services_id'], 'integer'],
            [['fromusername', 'review_level', 'describe','username','created_at'], 'safe'],
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
        $query = ReviewMongo::find();
        // add conditions that should always apply here
        $query->joinWith(['storage']);//==>Õâ±ß±íÒÑ¾­¹ØÁªÁË
        $query->select("wx_review_mongo.*,wx_customer_mongo.username,wx_customer_mongo.username");
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
            'review_level'=>$this->review_level,
            'wx_customer_mongo.username'=>$this->username,
	    'wx_customer_mongo.appid'=>Yii::$app->wechat->appid
        ]);
        if($this->created_at!=null && $end_time = explode(' - ', $this->created_at)){
            $start_time = strtotime($end_time[0]);
            $end_time = strtotime($end_time[1]);
            $query->andFilterWhere(['between', 'wx_review_mongo.created_at', $start_time, $end_time]);
        }
        $query->andFilterWhere(['like', 'wx_customer_mongo.username', $this->username])
            ->andFilterWhere(['like', 'describe', $this->describe]);

        return $dataProvider;
    }
}
