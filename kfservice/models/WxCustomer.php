<?php
namespace backend\modules\kfservice\models;

use yii\db\ActiveRecord;
use yii\caching\TagDependency;
use yii\behaviors\TimestampBehavior;
use common\behaviors\ActiveRecordHelper;
use backend\modules\kfservice\behaviors\DataBehavior;
use Yii;
class WxCustomer extends \yii\db\ActiveRecord
{
    const IDENTITY_ONE = 1;
    const IDENTITY_TWO = 2;
    const STATUS_HIDE = 'hide';
    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';

    public static $unread = 0;
    public static $last_msg;
    public static $isNew = false;
    public static $identity_map = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_customer_mongo';
    }

    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['fromusername','appid'], 'required'],
            [['fromusername'], 'unique'],
            ['username',function($attribute){
                $this->$attribute = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',  $this->$attribute);
                return true;
            }],
            [['id','created_at','updated_at','identity','fd','genre'], 'integer'],
            [['status'],'default','value'=>WxCustomer::STATUS_ONLINE],
            [['identity'],'default','value'=>WxCustomer::IDENTITY_ONE],
            [['sign','appid','username','fromusername','status','avatar'], 'string', 'max' => 255],
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
    public function attributeLabels()
    {
        return [
            'avatar' => '头像',
            'fd'=>'连接id',
            'fromusername'=>'openID',
            'username' => '昵称',
            'sign' => '备注',
            'status' => '状态',
            'sign' => '备注',
            'appid' => '平台',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'identity' => '身份',
        ];
    } 
    /**
     * @param int $id
     * @return Form|null
     */
    public static function findIdentity($fromusername = null)
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
     * [RelevanceTo 建立客服联系]
     * @param [type] $tousername [description]
     */
    public function relevanceTo($fo_id,$data)
    {   
        if(null === WxStorage::findIdentity($this->fromusername) || isset($data['to_id'])){
            $fo_id = (int) $fo_id;
            if ((null !== $model = $this->assign($fo_id,$data)) 
                && true === $this->allot($fo_id,$model->id)) {
                
                $model->reception_num += static::$isNew ? 1 : 0;
                $model->save();
                return $model;
            }
            return null;
        }
        WxServices::addStorage($data,WxStorage::TYPE_WAIT);
        return null;
    }

    /**
     * 在线客服连接
     */
    public function allot($fo_id,$to_id,$default = 1)
    {   
        $this->service_id = (int) $to_id;
        if(null === $this->relevances) {
            unset($this->relevances);
            $model = new WxRelevance();
            $model->fo_id = $fo_id;
            $model->to_id = $to_id;
            $model->status = $default;
            $model->unread = $default;
            $model->for_time = time();
            self::$isNew = true;
            self::$unread = 1;
            return $model->save() ? true : false;
        }
        $this->relevances->unread = intval($this->relevances->unread) + 1;
        $this->relevances->status == 0 ? (self::$isNew = true) : (self::$isNew = false);
        $this->relevances->status = 1;
        self::$unread = $this->relevances->unread;
        return true;    
    }

    /**
     * [assign 分配在线客服]
     * @return [type] [description]
     */
    public function assign($fo_id,$data)
    {   
        $to_id = isset($data['to_id']) ? (int) $data['to_id'] : null;
        $model = WxServices::find()->where(['and',['appid'=>$this->appid],['>','fd',0]]);
        //分配客服
        if (null !== $relevance = WxRelevance::findOne(['fo_id'=>$fo_id,'status'=>1])) {
            //是否下线
            if(null === $service = $model->andWhere(['and',
                ['id'=>$relevance->to_id],['!=','status',WxCustomer::STATUS_OFFLINE]])->One()){
                
                WxServices::addStorage($data,WxStorage::TYPE_SELECT,$relevance->to_id);
                return null;
            }
            return $service;
        }
        if ($to_id > 0) {
            return $model->andWhere(['and',
                ['id'=>$to_id],['!=','status',WxCustomer::STATUS_OFFLINE]])->One();
        } if (null === $model = $model->andWhere(['status'=>WxCustomer::STATUS_ONLINE])
            ->orderBy(['reception_num'=>SORT_ASC])->One()) {
            WxServices::addStorage($data,WxStorage::TYPE_WAIT);
        }
        return $model;
    }
    /**
     * [sendMsg description]
     * @return [type] [description]
     */
    public function sendMsg($content,$timestamp)
    {
        return [
            'username'=>$this->username,
            'avatar'=>$this->avatar,
            'fromusername'=>$this->fromusername,
            'id'=>$this->id,
            'type'=>'friend',
            'content'=>$content,
            'mine'=>false,
            'appid'=>$this->appid,
            'groupid'=>1,
            'last_msg'=>self::$last_msg,
            'unread'=>self::$unread,
            'isnew'=>self::$isNew,
            'fromid'=>$this->id,
            'timestamp'=>$timestamp
        ];
    }

    /**
     * [sendMsg description]
     * @return [type] [description]
     */
    public function mineSendMsg(array $array,$content)
    {
        return [
            'username'=>$array['username'],
            'avatar'=>$array['avatar'],
            'id'=>$this->id,
            'type'=>'friend',
            'content'=>$content,
            'mine'=>true,
            'appid'=>$this->appid,
            'groupid'=>1,
            'last_msg'=>self::$last_msg,
            'unread'=>self::$unread,
            'isnew'=>self::$isNew,
            'fromid'=>$this->id,
            'timestamp'=>time()
        ];
    }
    /**
     * [receiveWechatMsg 接收微信消息]
     * @return [type] [description]
     */
    public function receiveWechatMsg($to_id,array $array)
    {
        $to_id = (int) $to_id;
        $model = new WxMessage;
        $array['genre'] = WxCustomer::IDENTITY_ONE;
        $model->setAttributes($model->addMessage($this->fromusername,$to_id,$array));
        self::$last_msg = $this->username .' : '. $model->value;
        $this->service_id = $to_id;
        $this->relevances->last_msg = self::$last_msg;
        $model->save();
        //首次消息记录
        if($this->relevances->unread == 1){
            $this->relevances->for_time = time();
            $this->relevances->message_id = $model->id; 
        }
        $this->relevances->save();
        unset($this->relevances);
        return $model;
    }

    /**
     * [receiveWechatMsg 发送微信消息]
     * @return [type] [description]
     */
    public function sendWechatMsg($to_id,array $content)
    {
        $to_id = (int) $to_id;
        $model = new WxMessage;
        $array = $model->wechatArray($content['content']);
        $array['genre'] = WxCustomer::IDENTITY_TWO;
        $model->setAttributes($model->storeMessage($this->fromusername,$to_id,$array));
        $array['touser'] = $this->fromusername;
        $result = $model->sendMessage($array);
        if (($result['errcode']) && $result['errcode'] != 0) {
            $result['errmsg'] = isset(WxMessage::$errors[$result['errcode']]) 
            ? WxMessage::$errors[$result['errcode']] : '错误状态码:'.$result['errcode'];
            
            $model->value = 'err['.$result['errmsg'].']&nbsp;&nbsp;&nbsp;'.$model->value;
        }self::$unread = null;
        $this->service_id = $to_id;
        self::$last_msg = $content['username'] .' : '. $model->value;
        $this->relevances->last_msg = self::$last_msg;
        //是否超时回复
        //$this->relevances->addOvertime($this->fromusername);
        $this->relevances->unread = null;
        $this->relevances->save();
        unset($this->relevances);
        $model->save();
        return $this->mineSendMsg($content,$model->value);
    }

    /**
     * [userLink 客服消息连接]
     * @return [type] [description]
     */
    public function userLink($to_id)
    {   
        $to_id = (int) $to_id;
        if ((null !== $model = WxRelevance::findOne(['fo_id'=>$this->id,
            'status'=>WxCustomer::IDENTITY_ONE])) && $model->to_id != $to_id) {
            
            return ['errcode'=>404,'errmsg'=>"{$model->service->username}【{$model->service->identity}】正在会话中...,你不能发起消息接入！"];
        }
        if (true == $this->allot($this->id,$to_id,0)) {
            return true;
        }
        return ['errcode'=>404,'errmsg'=>'系统繁忙 , 接入失败！'];
    }
}
?>