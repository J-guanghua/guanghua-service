<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\modules\kfservice\models\External;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\matterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
\Yii::$app->wechat->appid = \Yii::$app->user->identity->appid;
$array = \Yii::$app->wechat->external==null?[]:\Yii::$app->wechat->external->toArray();
$this->title = '用户对话管理';
?>
<?=\backend\modules\kfservice\widgets\CustomerWidget::widget([
  'config'=>$array,
  'SocketHost'=>\Yii::$app->wechat->externalSection('domain_name')
  //'SocketHost'=>'wss://kefu.huwaishequ.com/wss/'
])?>

