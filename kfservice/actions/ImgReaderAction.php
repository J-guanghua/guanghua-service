<?php
namespace backend\modules\kfservice\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
class ImgReaderAction extends Action
{

    public $imagePath="/image/{yyyy}{mm}{dd}/{time}{rand:6}";
    public $uploadFilePath;
    public $fullName;
    public $filePath;
    /**
     * @var \yii\db\ActiveRecord Region Model
     */

    public function init()
    {   
        Yii::$app->request->enableCsrfValidation = false;
        parent::init();
    }

    public function run()
    {   
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(null !== $image_base = Yii::$app->request->post('imageBase64Content')){
            $img = str_replace('data:image/png;base64','',$image_base); 
            $img = str_replace('','+',$img);
            $data_img = base64_decode($img);
            $this->fullName = $this->getFullName();
            $this->filePath = $this->getFilePath();
            $dirname = dirname($this->filePath);
            //创建目录失败
            if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
                return ['errcode'=>500,'errmsg'=>'目录创建失败！'];
            } else if (!is_writeable($dirname)) {
                return ['errcode'=>500,'errmsg'=>'目录创建失败！'];
            }
            if(file_put_contents($this->filePath,$data_img)){
                return ['errcode'=>0,'errmsg'=>'ok','imgpath'=>$this->fullName];
            };
            return ['errcode'=>500,'errmsg'=>'上传失败'];
        };
        return ['errcode'=>404,'errmsg'=>'imageBase64Content 参数值不能为空'];
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName()
    {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->imagePath;
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }
        $ext = $this->getFileExt();
        return $format . $ext;
    }
    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower('.png');
    }

    /**
     * 获取文件完整路径
     * @return string
     */
    private function getFilePath()
    {
        $fullname = $this->fullName;       
        $rootPath = isset($this->uploadFilePath)&&!empty($this->uploadFilePath)?$this->uploadFilePath:$_SERVER['DOCUMENT_ROOT'];
        if (substr($fullname, 0, 1) != '/') {
            $fullname = '/' . $fullname;
        }
        return $rootPath . $fullname;
    }
}