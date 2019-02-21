<?php
use yii\helpers\Url;
use backend\modules\kfservice\models\External;

\Yii::$app->wechat->appid = Yii::$app->id == 'app-backend'?\Yii::$app->wechat->appid:\Yii::$app->user->identity->appid;
$array = \Yii::$app->wechat->external==null?[]:\Yii::$app->wechat->external->toArray();
?>
<?=\backend\modules\kfservice\widgets\HistoryWidget::widget([
	'hostInfo'=>Yii::$app->wechat->externalSection('commodity'),
	'httpArray'=>[
		'browse'=>Yii::$app->wechat->externalSection('browse'),
		'order'=>Yii::$app->wechat->externalSection('order'),
	],
	'fromusername'=>$model->fromusername,
])?>