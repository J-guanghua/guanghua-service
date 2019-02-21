<?php
/**
 * @link http://www.yii-china.com/
 * @copyright Copyright (c) 2015 Yii中文网
 */

namespace backend\modules\kfservice\widgets\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Xianan Huang <xianan_huang@163.com>
 */
class CustomerAsset extends AssetBundle
{
    public $css = [
        'layui/css/layui.css',
        
    ];
    
    public $js = [
        'layui/layui.js',
        //'layui/jquery.min.js',
        'layui/lay/modules/emoji.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
    
    /**
     * 初始化：sourcePath赋值
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
        $this->sourcePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR . 'statics';
    }
}