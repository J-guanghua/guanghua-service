<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\modules\kfservice\behaviors\DataBehavior;
/**
 * This is the model class for table "wx_config".
 *
 * @property integer $wechat_id
 * @property string $appid
 * @property string $appsecret
 * @property string $Token
 * @property string $wechat_url
 * @property string $user_str
 */
class WxConfig extends \yii\db\ActiveRecord
{   

    public $isWechat = true;
    public $isLoad = false;
    public $cacheToken = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_config';
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
    public function rules()
    {
        return [
            [['appid','wechat_id'], 'required'],
            [['scope','send_name','appsecret','name','mch_id','key','Token','wechat_img','scope','platform'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function againLoad($appid)
    {
        if(($config['WxConfig'] = static::find()->where(['appid'=>$appid])->asArray()->One())){
          
             return $this->isLoad = $this->load($config);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getExternal()
    {
        return External::findOne(['appid'=>$this->appid]);
    }

    /**
     * @inheritdoc
     */
    public function externalSection($key,$openid = null)
    {
        if($this->external===null){
            return false;
        };
        if ($this->external->isAttributeActive($key)) {
            if($openid===null) return $this->external->$key;
            return sprintf($this->external->$key,$openid);
        }
        return false;
    }
    
}
