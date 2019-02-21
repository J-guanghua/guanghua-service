<?php

namespace backend\modules\kfservice\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\kfservice\models\WxRelevance;
use backend\modules\kfservice\models\WxCustomer;
use backend\modules\kfservice\models\WxServices;


/**
 * ForumPostSearch represents the model behind the search form about `backend\models\discuz\ForumPost`.
 */
class RelevanceSearch extends WxRelevance
{
    public $username;
    public $fromusername;
    public $nickname;
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['to_id','fo_id','status','created_at','groupid','unread'], 'integer'],
            [['username', 'fromusername', 'nickname','updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    // public function attributes()
    // {
    //     return ['id','to_id','fo_id','status','created_at','updated_at','groupid','unread','last_msg','username','fromusername','nickname'];
    // }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WxRelevance::find();
        $model = WxCustomer::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }if ($this->nickname != null || $this->fromusername != null) {
            $model = $model->andFilterWhere([
                    'username' => $this->nickname,
                    'fromusername' => $this->fromusername
                ])->One();
            $model === null ? $this->fo_id = 0 : $this->fo_id = $model->id; 
        }
        $query->andFilterWhere([
            'fo_id' => $this->fo_id,
            'to_id' => $this->to_id==null?null:(int) $this->to_id
        ]);
        if($this->updated_at!=null && $end_time = explode(' - ', $this->updated_at)){
            $start_time = strtotime($end_time[0]);
            $end_time = strtotime($end_time[1]);
            $query->andFilterWhere(['between', 'updated_at', $start_time, $end_time]);
        }
        return $dataProvider;
    }

    /**
     * [services description]
     * @return [type] [description]
     */
    public static function services()
    {
        foreach (WxServices::find()->where(['appid'=>Yii::$app->wechat->appid])->asArray()->all() as $key => $value) {
            $array[$value['id']] = $value['username'];
        }
        return isset($array) ? $array : [];
    }
}
