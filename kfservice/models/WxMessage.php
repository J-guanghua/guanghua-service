<?php

namespace backend\modules\kfservice\models;

use guanghua\wechat\wechatPort;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxMessage extends \yii\db\ActiveRecord//\yii\db\ActiveRecord
{
    public static $picurl;
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
    public static function tableName()
    {
        return 'wx_message_mongo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fromusername', 'to_id','genre'], 'required'],
            [['created_at','to_id','genre'], 'integer'],
            ['value',function($attribute){
                $this->$attribute = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '',  $this->$attribute);
                return true;
            }],
            [['msgtype','fromusername'], 'string', 'max' => 32],
            [['value','key'], 'string', 'max' => 1024],
        ];
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
     * [convertMessage 客服格式转换]
     * @param  array  $array [description]
     * @return [type]        [description]
     */
    public static function convertMessage(array $array)
    {   
        switch ($array['msgtype']) {
            case 'text'://文本消息
                $array['value'] = $array['content'];
                break;
            case 'image'://图片消息
                $array['value'] = 'img['.static::loadImg($array['mediaid'],true).']';
                break;
            case 'location'://地理位置消息
                $array['value'] = 'location['.$array['label'].']';
                break;
            case 'miniprogrampage'://小程序卡片
                $format = [
                    'title'=> isset($array['title'])?$array['title']:null,
                    'pagepath'=> isset($array['pagepath'])?$array['pagepath']:null,
                    'thumburl'=> static::loadImg($array['thumbmediaid'],true)
                ];
                $array['value'] = 'miniprogrampage['.json_encode($format).']';
                break;
            case 'link'://链接消息
                $format = [
                    'title'=> isset($array['title'])?$array['title']:null,
                    'description'=> isset($array['description'])?$array['description']:null,
                    'url'=> isset($array['url'])?$array['url']:null
                ];
                $array['value'] = 'link['.json_encode($format).']';
                break;
            case 'voice'://语音消息
                $array['value'] = 'voice['.$array['mediaid'].']';
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
            default:
                $array['value'] = '不支持的消息类型';
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
            case 'link':
                $format = [
                    'title'=> urldecode($array['link']['title']),
                    'description'=> urldecode($array['link']['description']),
                    'url'=> $array['link']['url'],
                    'thumb_url'=>isset($array['link']['thumb_url'])?$array['link']['thumb_url']:null
                ];
                $array['value'] = 'link['.json_encode($format).']';
                break;
            case 'voice':
                $format = [
                    'media_id'=> urldecode($array['voice']['media_id']),
                ];
                $array['value'] = 'voice['.json_encode($format).']';
                break;
            default:
                $array['value'] = '不支持的消息类型';
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
            $jsonArray['miniprogrampage']['thumburl'] = static::loadImg($jsonArray['miniprogrampage']['thumb_media_id'],false);
            $jsonArray['miniprogrampage']['title'] = urlencode($jsonArray['miniprogrampage']['title']);
            $jsonArray['miniprogrampage']['thumb_media_id'] = static::getMediaId($jsonArray['miniprogrampage']['thumburl']);
            return $jsonArray;
        };//返回一个小程序卡片匹配结果
        if (preg_match('/(img[)\S(])/', $message)) {
            $jsonArray['msgtype'] = 'image';
            $jsonArray['picurl'] = str_replace(['img[',']'], '', $message);
            //下载图片
            if (!preg_match('/^^((https)?:\/\/)[^\s]+$/', $jsonArray['picurl'])) {
              $jsonArray['picurl'] = static::loadImg($jsonArray['picurl']);  
            } 
            $jsonArray['image']['media_id'] = static::getMediaId($jsonArray['picurl']);
            return $jsonArray;
        };//返回一个图片匹配结果
        if (preg_match('/(link[)\S(])/', $message)) {
            $jsonArray['link'] = json_decode(str_replace(['link[',']'], '', $message),true);
            $jsonArray['msgtype'] = 'link';
            $jsonArray['link']['title'] = urlencode($jsonArray['link']['title']);
            $jsonArray['link']['description'] = urlencode($jsonArray['link']['description']);
            return $jsonArray;
        };//返回一个图文链接匹配结果
        if (preg_match('/(voice[)\S(])/', $message)) {
            $jsonArray['voice']['media_id'] = json_decode(str_replace(['voice[',']'], '', $message),true);
            $jsonArray['msgtype'] = 'voice';
            return $jsonArray;
        };//返回一个语言匹配结果
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
        if ($array = wechatPort::find()->Material($url)->jsonarr(false)) {
            return isset($array['media_id'])?$array['media_id']:null;
        };
        return null;
    }

    /**
     * 下载图片
     */
    public static function loadImg($url,$receive = false)
    {
        $path = '/static/images/'.date('Ymd');
        $webpath = \Yii::getAlias('@root/web').$path;
        if (!is_dir($webpath)){
            $oldumask=umask(0); mkdir($webpath,0777); umask($oldumask);
        }
        $imgPath = $path.'/'.time().mt_rand(111111111,999999999).'.png';
        if ($receive === true) {
            $fileImg = \guanghua\wechat\wechatPort::find()->mediaDownload($url)->json;
        } else {
            $fileImg = \guanghua\wechat\Wechat::find()->request($url);
        }
        if(file_put_contents(\Yii::getAlias('@root/web').$imgPath, $fileImg,
            FILE_USE_INCLUDE_PATH)) return $receive ? 'https://kefu.huwaishequ.com'.$imgPath:$imgPath;
        return $url;
        // \Swoole\Async::writeFile(\Yii::getAlias('@root/web').$imgPath, $model->request($url));
        // return $http?'https://kefu.huwaishequ.com'.$imgPath:$imgPath;
    }

}
?>

