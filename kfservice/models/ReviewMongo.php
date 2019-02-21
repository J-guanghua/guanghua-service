<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\modules\kfservice\behaviors\DataBehavior;
/**
 * This is the model class for table "wx_review_mongo".
 *
 * @property integer $id
 * @property string $fromusername
 * @property integer $services_id
 * @property string $review_level
 * @property string $describe
 * @property integer $cerated_at
 */
class ReviewMongo extends \yii\db\ActiveRecord
{
    public static $reviewLevel = [
        '差评'=>'差评',
        '中评'=>'中评',
        '好评'=>'好评'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_review_mongo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['services_id','created_at'], 
                'unique', 
                'targetAttribute' => ['services_id','created_at'], 
                'message' => '该评论已经提交过了'
            ],
            [['fromusername', 'services_id','created_at'], 'required'],
            [['services_id', 'created_at'], 'integer'],
            [['review_level'], 'string'],
            [['fromusername'], 'string', 'max' => 32],
            [['describe'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fromusername' => 'openID',
            'services_id' => '客服',
            'review_level' => '评分',
            'describe' => '评论',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DataBehavior::className(),
           // [
           //      'class'=>TimestampBehavior::className(),
           //      'updatedAtAttribute'=>false
           // ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function pushReview($fromusername)
    {   
        if (Yii::$app->wechat->externalSection('grade_limit')) {
            
            return Yii::$app->wechat->externalSection('grade_msg');
        
        } elseif(false == Yii::$app->cache->get(ReviewMongo::className()."={$fromusername}")) {

            Yii::$app->cache->set(ReviewMongo::className()
                ."={$fromusername}",1,strtotime(date('Y-m-d 23:59:50'))-time());
            return Yii::$app->wechat->externalSection('grade_msg');
        }
        return false;
    }
}
