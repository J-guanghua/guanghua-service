<?php

namespace backend\modules\kfservice\models;

use yii\behaviors\TimestampBehavior;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxRelevance extends \yii\mongodb\ActiveRecord//\yii\db\ActiveRecord
{
    const IDENTITY_ONE = 1;
    const IDENTITY_TWO = 2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['to_id','fo_id'], 'required'],
            [['to_id','fo_id','status','created_at','updated_at','groupid','unread','id'], 'integer'],
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
    public function transform()
    {
        return ['to_id','fo_id','id','status','created_at','updated_at','groupid','unread'];
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
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'wx_relevance_mongo';
    }
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ['_id','id','to_id','fo_id','status','created_at','updated_at','groupid','unread','last_msg'];
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

}
?>