<?php

namespace backend\modules\kfservice\models;

use yii\behaviors\TimestampBehavior;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxRelevance extends \yii\db\ActiveRecord//\yii\db\ActiveRecord
{
    const IDENTITY_ONE = 1;
    const IDENTITY_TWO = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_relevance_mongo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['to_id','fo_id'], 'required'],
            [['to_id','fo_id','status','created_at','updated_at','groupid','unread','for_time','message_id'], 'integer'],
            [['groupid'],'default','value'=>WxRelevance::IDENTITY_ONE],
            ['last_msg',function($attribute){
                $this->$attribute = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',  $this->$attribute);
                return true;
            }],
            [['last_msg'], 'string', 'max' => 556],
            [
                ['to_id', 'fo_id'], 
                'unique', 
                'targetAttribute' => ['to_id', 'fo_id'], 
                'message' => 'to_id和fo_id已经被占用！'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '客服昵称',
            'avatar' => '客服头像',
            'status' => '会话状态',
            'to_id'=>'客服',
            'last_msg'=>'最后消息记录',
            'fromusername' => '用户openID',
            'nickname' => '微信昵称',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DataBehavior::className(),
           TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function addOvertime($fromusername,$default = 1)
    {
        if($this->id == null || $this->unread===null) return false;
        if ((0 < $overtime = (\Yii::$app->wechat->externalSection('overtime')*60)) 
            && $overtime <= (time()-$this->for_time)) {
            $array[0] = [
                'fromusername' => $fromusername,
                'services_id' => $this->to_id,
                'overtime' => time()-$this->for_time,
                'created_at'=>time(),
                'message_id'=>$this->message_id,
            ];
            $db = \Yii::$app->db;
            $sql = $db->queryBuilder->batchInsert('wx_register_overtime',array_keys($array[0]), $array);
            //超时回复公告
            if ($db->createCommand(str_replace("INSERT INTO ","INSERT IGNORE INTO ",$sql))->execute()) {
                $time = (new \backend\modules\kfservice\search\ServicesSearch)->sec2time($array[0]['overtime']);
                $message = "<div><font style='color:red'>{$this->service->username}</font><img> 超时({$time})回复 <img src='{$this->user->avatar}' style='width:30px;height:30px;border-radius:15px'> 已记录系统<div>";
                            //超时回复公告
                $array['action'] = 'addNoticeEvent';
                $array['data'] = $message;
                foreach (\Yii::$app->websocket->server->connections as $fd) {
                    \Yii::$app->websocket->server->push($fd,json_encode($array));
                }
                return true;
            }
       }
        return false;
    }
}
?>