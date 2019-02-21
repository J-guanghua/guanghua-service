<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\web\IdentityInterface;
use guanghua\wechat\wechatPort;
use backend\modules\kfservice\models\WxCustomer;
use backend\modules\kfservice\behaviors\DataBehavior;
/**
 * This is the model class for table "wx_services".
 *
 * @property integer $id
 * @property string $username
 * @property string $avatar
 * @property string $status
 * @property string $sign
 * @property string $appid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $reception_num
 */
use yii\behaviors\TimestampBehavior;

class WxServices extends \yii\db\ActiveRecord  implements IdentityInterface
{
    public $_user = null;
    public $rememberMe = true;
    //public $auth_key = null;
    public static $identity = [
        '售前','售后'
    ];
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_services_mongo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'avatar'], 'required'],
            [['username'], 'unique'],
            ['appid','default','value'=>Yii::$app->wechat->appid],
            ['status','default','value'=>WxCustomer::STATUS_OFFLINE],
            [['id', 'created_at', 'updated_at', 'reception_num','fd'], 'integer'],
            [['username','status','sign','appid','identity'], 'string', 'max' => 64],
            //[['avatar'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->avatar = 'https://kefu.huwaishequ.com'.$this->avatar;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fd'=>'聊天id',
            'username' => '昵称',
            'avatar' => '客服头像',
            'status' => '在线状态',
            'sign' => '备注',
            'appid' => '平台',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'reception_num' => '在线接待数量',
        ];
    }
     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            DataBehavior::className()
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
       return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function userStatus()
    {
        return [
            WxCustomer::STATUS_ONLINE => '在线',
            WxCustomer::STATUS_OFFLINE => '离线',
            WxCustomer::STATUS_HIDE => '挂起',
        ];
    }

    /**
     * @inheritdoc
     */
    public function appid($appid)
    {
        return \guanghua\wechat\WxConfig::findOne(['appid'=>$appid])->name;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function loginAuth($auth_key)
    {   
        if($this->_user = static::findOne(['auth_key'=>$auth_key])){
            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);  
        }
        return false;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {   
        if($this->getUser()){
            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);  
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = static::findOne(['username'=>$this->username]);
        }

        return $this->_user;
    }
    /**
     * @inheritdoc
     */
    public function closeEvent($fd)
    {
        if($fd > 0){
            $array['action'] = 'CloseEvent';
            $array['fd'] = $fd;
            return $this->webSocketHttp($array); 
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function through($fromusername,$string)
    {
        $model = new WxMessage;
        $array = $model->wechatArray($string);
        $array['touser'] = $fromusername;
        $array['genre'] = WxCustomer::IDENTITY_TWO;
        $model->setAttributes($model->storeMessage($fromusername,Yii::$app->user->id,$array));
        $result = $model->sendMessage($array);
        if (($result['errcode']) && $result['errcode'] != 0) {
            $result['errmsg'] = isset(WxMessage::$errors[$result['errcode']]) 
                ? WxMessage::$errors[$result['errcode']] : '错误状态码:'.$result['errcode'];
            
            $model->value = 'err['.$result['errmsg'].']&nbsp;&nbsp;&nbsp;'.$model->value;
        }
        return $model->save();
    }

    /**
     * @inheritdoc
     */
    public static function serviceStatus($fo_id,$to_id)
    {   
        $fo_id = (int) $fo_id;$to_id = (int) $to_id;
        if(null !== $model = WxCustomer::findOne(['id'=>$fo_id])){
            $fromusername = $model->fromusername;
            if(null !== $model = WxRelevance::findOne(['fo_id' => $fo_id,'to_id'=>Yii::$app->user->id,'status'=>1])){
                $model->status = 0;
                //是否超时回复
                $model->addOvertime($fromusername,0);
                $model->unread = null;
                $model->save();
                return static::findOne(['id'=> Yii::$app->user->id]);
            };
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function webSocketHttp(array $array,$type = true)
    {
        Yii::$app->wechat->againLoad(Yii::$app->user->identity->appid);
        if(null != $http_link = Yii::$app->wechat->externalSection('http_link')){

            $data = wechatPort::find()->request(
                $http_link,true,'post',http_build_query($array));
            
            if(false !== $data = json_decode($data,true)){
                return $type === true? $data['errcode']===0 : $data;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function addStorage(array $data,$type,$to_id = null)
    {
        $model = new WxStorage;
        $model->to_id = $to_id;
        $model->fromusername = $data['fromusername'];
        $model->appid = $data['appid'];
        $model->type = $type;
        $model->data = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',\yii\helpers\Json::encode($data));
        return $model->insert();
    }
    /**
     * @inheritdoc
     */
    public function offlineMessage($to_id = null)
    {
       if([] !== $model = WxStorage::find()
        ->where(['type'=>WxStorage::TYPE_SELECT])->andFilterWhere(['to_id'=>(int) $to_id])->all()){
            foreach ($model as $key => $value) {
                $array = \yii\helpers\Json::decode($value->data);
                if (is_array($array) && $this->webSocketHttp($array)) {
                    $value->delete();
                };
            }
            return true;
       };
       return false; 
    }
    /**
     * @inheritdoc
     */
    public function offlineMessageMongo($model)
    {
        if([] !== $model = $model->find()->where(['appid'=>
            Yii::$app->user->identity->appid,'type'=>'wait'])->all()){
            foreach ($model as $key => $val) {
                if($val->storage!==null){
                    $array[$val->fromusername] = $val->storage->toArray();
                }
            } 
       };
       return isset($array) ? array_values($array) : [];
    }
    /**
     * @inheritdoc
     */
    public function waitMessage()
    {
        $model = new WxStorage;
        if ($model instanceof \yii\mongodb\ActiveRecord) {
            
            $array = $this->offlineMessageMongo($model);
        } else {
            if([] !== $model = $model->find()->where(['appid'=>
                Yii::$app->user->identity->appid,'type'=>'wait'])->groupBy(['fromusername'])->all()){
                $i=0;
                foreach ($model as $key => $val) {
                    if($val->storage!==null){
                        $array[$i] = $val->storage->toArray();
                        $array[$i]['timestamp'] = $array[$i]['updated_at'];
                        $i++;
                    }
                } 
           };
        }
       return isset($array) ? $array : []; 
    }

    /**
     * @inheritdoc
     */
    public function joinUpUser($fromusername)
    {
        if(null !== $model = WxCustomer::find()->where(['fromusername'=>$fromusername])->One()){
            
            if(true === $array = $model->userLink(Yii::$app->user->id)){
                foreach ($model->storages as $key => $value) {
                    $array = \yii\helpers\Json::decode($value->data);
                    $array['to_id'] = Yii::$app->user->id;
                    if (is_array($array) && $this->webSocketHttp($array)) {
                        $value->delete();
                    };
                }
                return $this->webSocketHttp(['action'=>'RemoveEvent','type'=>'group'
                    ,'fromusername'=>$fromusername,'appid'=>$array['appid']],false);
            }
            return $array;
        }
       return ['errcode'=>404,'errmsg'=>'用户信息异常！']; 
    }

    /**
     * @inheritdoc
     */
    public function setChatStatus($status = null)
    {
        if($status !== null){
          Yii::$app->cache->set('setChatStatus-'.Yii::$app->user->id,$status);  
        }
        return Yii::$app->cache->get('setChatStatus-'.Yii::$app->user->id);
    }
    
    /**
     * @inheritdoc
     */
    public static function onOfflineAll($appid)
    {
        return static::find()
        ->where(['appid'=>$appid])
        ->andWhere(['>','fd',0])->asArray()->all();
    }
}
