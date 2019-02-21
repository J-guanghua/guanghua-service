<?php
/**
 * @see Yii中文网  http://www.yii-china.com
 * @author Xianan Huang <Xianan_huang@163.com>
 * 图片上传组件
 * 如何配置请到官网（Yii中文网）查看相关文章
 */
namespace backend\modules\kfservice\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use backend\modules\kfservice\widgets\assets\CustomerAsset;

class CustomerWidget extends Widget
{
    public $config = [];
    
    public $SocketHost;
    
    public function init()
    {
        $_config = [
            'shopHost' => Yii::$app->request->hostInfo,
            'commodity' => null,
            'message_card' => null,
            'wicket' => 0 
        ];
        $this->config = ArrayHelper::merge($_config, $this->config?:[]);
    }
    public function run()
    {
        $this->registerClientScript();        
        return $this->render('index',[
            'config'=>$this->config,
            'SocketHost'=>$this->SocketHost
        ]);
    }
    public function registerClientScript()
    {
        CustomerAsset::register($this->view);
    }
}