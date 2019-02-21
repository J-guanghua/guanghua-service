<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "wx_external".
 *
 * @property integer $id
 * @property string $domain_name
 * @property string $message_card
 * @property string $commodity
 * @property string $order
 * @property string $browse
 * @property string $appid
 * @property integer $created_at
 * @property integer $updated_at
 */
class External extends \yii\db\ActiveRecord
{

    public static $identity_map = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_external';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain_name','http_link'], 'required'],
            [['appid'], 'unique'],
            [['created_at', 'updated_at','wicket','access_way','overtime','grade_limit','is_receive'], 'integer'],
            [['domain_name', 'appid'], 'string', 'max' => 32],
            [['appid'],'default','value'=>Yii::$app->wechat->appid],
            [['message_card','commodity','order','browse','userinfo','http_link'], 'url', 'defaultScheme' => 'http'],
            [['message_card', 'commodity', 'order', 'browse','userinfo','grade_msg'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain_name' => '客服连接地址',
            'message_card' => '支持微信消息类型 小程序卡片(miniprogrampage) 图文链接(link) ',
            'commodity' => '商品浏览链接 (对接我的平台商品信息) ',
            'order' => '订单浏览链接 ( 对接我的平台订单信息 ) ',
            'browse' => '浏览记录链接 ( 对接我的浏览记录信息 ) ',
            'http_link'=>' 客服http接口',
            'appid' => '平台',
            'userinfo'=>'用户信息接口',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'grade_msg'=>'发起评分回复文案',
            'wicket'=>'聊天窗口展示',
            'access_way'=>'客服接入方式',
            'is_receive'=>'消息是否接入客服',
            'overtime'=>'客服超时回复记录/分',
            'grade_limit'=>'发起评分限制'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
           TimestampBehavior::className(),
        ];
    }

    /**
     * @param int $id
     * @return Form|null
     */
    public static function findIdentity($appid = null)
    {
        if (null === $fromusername) {
            return null;
        }
        if (!isset(static::$identity_map[$fromusername])) {
            $cacheKey = "WxCustomer:$fromusername";
            if (false === $cache = Yii::$app->cache->get($cacheKey)) {
                if (null === $cache = static::findOne(['fromusername' => $fromusername])) {
                    return null;
                }
                Yii::$app->cache->set(
                    $cacheKey,
                    $cache,
                    0,
                    new TagDependency([
                        'tags' => [
                            ActiveRecordHelper::getObjectTag(static::className(), $fromusername),
                            ActiveRecordHelper::getCommonTag(static::className()),
                        ]
                    ])
                );
            }
            static::$identity_map[$fromusername] = $cache;
        }
        return static::$identity_map[$fromusername];
    }
    
    /**
     * @inheritdoc
     */
    public static function pushReview($fromusername)
    {   
        if (Yii::$app->wechat->externalSection('grade_limit')) {
            
            return Yii::$app->wechat->externalSection('grade_msg');
        
        } elseif(false == Yii::$app->cache->get(External::className()."={$fromusername}")) {

            Yii::$app->cache->set(External::className()
                ."={$fromusername}",1,strtotime(date('Y-m-d 23:59:50'))-time());
            return Yii::$app->wechat->externalSection('grade_msg');
        }
        return false;
    }
}
