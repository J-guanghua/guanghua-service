<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\web\NotFoundHttpException;
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
class WxConfig extends \yii\mongodb\ActiveRecord//\yii\db\ActiveRecord
{   

    public $isWechat = true;
    public $isLoad = false;
    public $cacheToken = true;
    public $wechat_id;
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
    public static function collectionName()
    {
        return 'wx_config_mongo';
    }
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ['_id','appid','appsecret','wechat_id', 'scope','send_name','name','mch_id','key','Token','wechat_img','scope'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appid', 'appsecret','wechat_id'], 'required'],
            [['scope','send_name','name','mch_id','key','Token','wechat_img','scope'], 'safe'],
        ];
    }
    public function transform()
    {
        return ['wechat_id','mch_id'];
    }
    /**
     * @inheritdoc
     */
    public function againLoad($appid)
    {
        if(($config['WxConfig'] = $this->find()->where(['appid'=>$appid])->asArray()->One())){
          
             return $this->isLoad = $this->load($config);
        }
        return false;
    }

    /**
     * [Wechat description]
     * @param [type] $id [description]
     */
    public function loadWechat($id = null)
    {  
        $this->setWechatId();

        if(($config['WxConfig'] = $this->find()->where(['wechat_id'=>$this->wechat_id])->asArray()->One())!==null)
        { 
            return $this->isLoad = $this->load($config);
        }
        return $this->isLoad = false;
    }
    
}
