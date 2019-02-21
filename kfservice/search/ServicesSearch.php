<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\WxServices;

/**
 * ServicesSearch represents the model behind the search form about `backend\modules\kfservice\models\WxServices`.
 */
class ServicesSearch extends WxServices
{
    public $online_time;
    public $offline_time;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'reception_num'], 'integer'],
            [['username', 'avatar', 'status', 'sign', 'appid','online_time','offline_time'], 'safe'],
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
        $query = WxServices::find();

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
            'appid'=>Yii::$app->wechat->appid,
            'id' =>$this->id == null? null : (int) $this->id,
            'updated_at' => $this->updated_at,
            'reception_num' => $this->reception_num,
        ]);

        $query->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'sign', $this->sign])
            ->andFilterWhere(['like', 'appid', $this->appid]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function getRegister($services_id)
    {   
        $start_time = $this->online_time?strtotime($this->online_time):null;
        $end_time = $this->offline_time?strtotime($this->offline_time):null;
        return \backend\modules\kfservice\models\ServicesRegister::find()
        ->select(['sum(offline_time-online_time) as sum_time','min(online_time) as min_time,max(offline_time) as end_time'])
        ->where(['services_id'=>$services_id])
        ->andFilterWhere(['>=','online_time',$start_time])
        ->andFilterWhere(['<=','offline_time',$end_time])
        ->asArray()->One();
    }

    /**
     * @inheritdoc
     */
    public function getMessage($services_id)
    {   
        $start_time  = $this->online_time?strtotime($this->online_time):null;
        $end_time = $this->offline_time?strtotime($this->offline_time):null;
        return MessageSearch::find()
        ->where(['to_id'=>$services_id])
        ->andFilterWhere(['>=','created_at',$start_time])
        ->andFilterWhere(['<=','created_at',$end_time])
        ->count("distinct(fromusername)");
    }

    public function sec2time($sec){
        $sec = round($sec/60);
        if ($sec >= 60){
            $hour = floor($sec/60);
            $min = $sec%60;
            $res = $hour.' 小时 ';
            $min != 0  &&  $res .= $min.' 分';
        } else {
            $res = $sec.' 分钟';
        }
        return $res;
    }
}
