<?php

namespace backend\modules\kfservice\models;

use guanghua\wechat\wechatPort;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxMessage extends \yii\mongodb\ActiveRecord//\yii\db\ActiveRecord
{

    public $primaryKey = 0;
    public static $errors = [
        -1 => '系统繁忙，此时请开发者稍候再试',
        0 => '发送成功',
        40001 => 'AppSecret 错误，或者 access_token 无效',
        40002 => '不合法的凭证类型',
        40003 => '不合法的 OpenID',
        45015 => '回复时间超过限制',
        45047 => '客服接口发送条数超过上限',
        48001 => 'api功能未授权，请确认小程序已获得该接口',
        48002 => '用户关闭消息接收'
    ];
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fromusername', 'to_id','genre'], 'required'],
            [['created_at','to_id','genre','id'], 'integer'],
            ['value',function($attribute){
                $this->$attribute = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',  $this->$attribute);
                return true;
            }],
            [['msgtype','fromusername'], 'string', 'max' => 32],
            [['value','key'], 'string', 'max' => 1024],
        ];
    }
    public function transform()
    {
        return ['created_at','to_id','id','genre'];
    }
     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DataBehavior::className(),
        ];
    }
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'wx_message_mongo';
    }
    public function attributes()
    {
        return ['_id','id','fromusername','to_id','created_at','msgtype','value','key','genre'];
    }

    /**
     * [convertMessage 客服格式转换]
     * @param  array  $array [description]
     * @return [type]        [description]
     */
    public static function convertMessage(array $array)
    {   
    	switch ($array['msgtype']) {
    		case 'text':
    			$array['value'] = $array['content'];
    			break;
    		case 'image':
    			$array['value'] = 'img['.static::loadImg($array['picurl']).']';
    			break;
   	   	    case 'location':
    			$array['value'] = 'location[北京市]';
    			break;
            case 'miniprogrampage':
                $format = [
                    'title'=> isset($array['title'])?$array['title']:null,
                    'pagepath'=> isset($array['pagepath'])?$array['pagepath']:null,
                    'thumburl'=> static::loadImg($array['thumburl'])
                ];
            	$array['value'] = 'miniprogrampage['.json_encode($format).']';
                break;
            case 'event': // 事件类型
                // 判断具体的事件类型（关注，取消，点击）
                if ('user_enter_tempsession' == $array['event']) { 
                   $array['value'] = 'user_enter_tempsession[用户进入回话事件]';
                }elseif ('unsubscribe' == $array['event']) { //取消关注
                   $array['value'] = 'unsubscribe[用户关注事件]';
                }
                elseif ('location' == $array['event']) { //连接跳转事件
                    $array['value'] = 'location['.$array['label'].']';
                }
                break;
    	}
        $array['created_at'] = isset($array['createtime'])
         ? intval($array['createtime']) : time(); 
    	return $array;
    }

    /**
     * [addMessage 添加消息]
     * @param array $array [description]
     */
    public function addMessage($fromusername,$to_id,array $array)
    {
    	$array = static::convertMessage($array);
        $array['fromusername'] = $fromusername;
        $array['to_id'] = $to_id;
        return $array;
    }

    /**
     * [storeMessage description]
     * @return [type] [description]
     */
    public function storeMessage($fromusername,$to_id,array $array)
    {
        switch ($array['msgtype']) {
            case 'text':
                $array['value'] = urldecode($array['text']['content']);
                break;
            case 'image':
                $array['value'] = 'img['.$array['picurl'].']';
                break;
            case 'miniprogrampage':
                $format = [
                    'title'=> urldecode($array['miniprogrampage']['title']),
                    'pagepath'=> $array['miniprogrampage']['pagepath'],
                    'thumburl'=>  $array['miniprogrampage']['thumburl']
                ];
                $array['value'] = 'miniprogrampage['.json_encode($format).']';
                break;
        }
        $array['created_at'] = time();
        $array['fromusername'] = $fromusername;
        $array['to_id'] = $to_id;
        return $array;
    }

    /**
     * 发送消息
     */
    public function sendMessage(array $array,$result = false)
    {   
        return wechatPort::find()->magCustom($array)->jsonCode($result);
    }

    /**
     * [wechatArray description]
     * @return [type] [description]
     */
    public function wechatArray($message)
    {   
        if (preg_match('/(miniprogrampage[)\S(])/', $message)) {
            $jsonArray['miniprogrampage'] = json_decode(str_replace(['miniprogrampage[',']'], '', $message),true);
            $jsonArray['msgtype'] = 'miniprogrampage';
            $webUrl = static::loadImg($jsonArray['miniprogrampage']['thumburl'],false);
            $jsonArray['miniprogrampage']['title'] = urlencode($jsonArray['miniprogrampage']['title']);
            $jsonArray['miniprogrampage']['thumb_media_id'] = static::getMediaId($webUrl);
            return $jsonArray;
        };//返回一个匹配结果
        if (preg_match('/(img[)\S(])/', $message)) {
            $jsonArray['msgtype'] = 'image';
            $jsonArray['picurl'] = str_replace(['img[',']'], '', $message);
            $jsonArray['image']['media_id'] = static::getMediaId($jsonArray['picurl']);
            return $jsonArray;
        };//返回一个匹配结果
        $jsonArray['msgtype'] = 'text';
        $jsonArray['content'] = $message;
        $jsonArray['text']['content'] = urlencode($message);
        return $jsonArray;
    }

    /**
     * [getMediaId description]
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function getMediaId($url)
    {   
        $sha1 = sha1($url);
        if (false === $media_id = Yii::$app->cache->get($sha1)) {
            if ($array = wechatPort::find()->Material($url)->jsonarr(false)) {
                $media_id = isset($array['media_id']) ? $array['media_id'] : null;
                Yii::$app->cache->set($sha1,$media_id,30*36000)
                return $media_id;
            };
            return null;
        }
        return $media_id;
    }

    /**
     * 下载图片
     */
    public static function loadImg($url,$http = true)
    {
        $path = '/static/images/'.date('Ymd');
        $webpath = \Yii::getAlias('@root/web').$path;
        if (!is_dir($webpath)) @mkdir($webpath);
        $imgPath = $path.'/'.time().mt_rand(111111111,999999999).'.png';
        $model = new \guanghua\wechat\Wechat;
        if(file_put_contents(\Yii::getAlias('@root/web').$imgPath, $model->request($url),FILE_USE_INCLUDE_PATH))
            return $http?'http://kefu.huwaishequ.com'.$imgPath:$imgPath;
        return null;
    }

}
?>