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

class HistoryWidget extends Widget
{
    public $httpArray = [];
    public $hostInfo;
    public $page;
    public $fromusername;

    public function init()
    {
        $httpArray = [];
        $this->hostInfo = $this->hostInfo?:Yii::$app->request->hostInfo;
        $this->httpArray = ArrayHelper::merge($httpArray, $this->httpArray);
    }
    
    public function run()
    {
        $this->registerClientScript();        
        return $this->render('history',[
            'httpArray'=>$this->httpArray,
            'hostInfo'=>$this->hostInfo,
            'fromusername'=>$this->fromusername,
        ]);
    }
    
    public function registerClientScript()
    {
        CustomerAsset::register($this->view);
    }
}