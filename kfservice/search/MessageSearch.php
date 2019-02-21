<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\WxMessage;

/**
 * ForumPostSearch represents the model behind the search form about `backend\models\discuz\ForumPost`.
 */
class MessageSearch extends WxMessage
{
    public $end_time;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fromusername', 'to_id','genre','created_at','end_time','value'], 'safe']
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
        $query = WxMessage::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15,
            ]
        ]);
        $this->load($params);
        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }
        $query->andFilterWhere($params);
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchs($params)
    {   
        $query = WxMessage::find()->groupBy(["fromusername"]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
                'to_id'=>Yii::$app->user->id,
                'genre'=>1,
            ]);
        $query->andFilterWhere(['>=','created_at',$this->created_at?strtotime($this->created_at):null]);
        $query->andFilterWhere(['<=','created_at',$this->end_time?strtotime($this->end_time):null]);
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchlist($params)
    {   
        $query = WxMessage::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
                'to_id'=>$this->to_id,
                'fromusername'=>$this->fromusername,
            ]);
        $query->andFilterWhere(['like', 'value', $this->value])
        ->andFilterWhere(['>=','created_at',$this->created_at?strtotime($this->created_at):null])
        ->andFilterWhere(['<=','created_at',$this->end_time?strtotime($this->end_time):null]);
        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function setPages($fromusername)
    {   
        if(null === $page = $_GET['page']){
            $count = WxMessage::find()->where(['fromusername'=>$fromusername])->count();
            $_GET['page'] = ceil($count/15);
        };
        return Yii::$app->request->get('page');
    }
}
