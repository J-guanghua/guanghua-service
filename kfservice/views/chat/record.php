<?php
use yii\helpers\Url;
$searchModel = new \backend\modules\kfservice\models\Goods();
?>
<style type="text/css">
.wrap > .container {
    padding: 0px 15px;
}
#w0{display: none;}
</style>
<link rel="stylesheet" href="<?=Yii::$app->request->hostInfo?>/admin/layui/css/layui.css">
<div class="layim-chat-main" style="height: 100%">
 	<button id="load_message" style="margin-left:40%">加载消息记录</button>
  	<ul id="LAY_view"></ul>
</div>
<div id="LAY_page" style="margin: 0 10px;" ></div>

<textarea title="消息模版" id="LAY_tpl" style="display:none;">
{{# layui.each(d.data, function(index, item){
  if(item.id == parent.layui.layim.cache().mine.id){ }}
    <li class="layim-chat-mine"><div class="layim-chat-user"><img src="{{ item.avatar }}"><cite><i>{{ layui.data.date(item.timestamp) }}</i>{{ item.username }}</cite></div><div class="layim-chat-text">{{ layui.layim.content(item.content) }}</div></li>
  {{# } else { }}
    <li><div class="layim-chat-user"><img src="{{ item.avatar }}"><cite>{{ item.username }}<i>{{ layui.data.date(item.timestamp) }}</i></cite></div><div class="layim-chat-text">{{ layui.layim.content(item.content) }}</div></li>
  {{# }
}); }}
</textarea>
<?php $this->beginBlock('js') ?>
<script src="<?=Yii::$app->request->hostInfo?>/admin/layui/layui.js"></script>
<script src="<?=Yii::$app->request->hostInfo?>/admin/layui/lay/modules/emoji.js"></script>
<script>
layui.use(['layim', 'laypage'], function(){
  var layim = layui.layim
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,$ = layui.jquery
  ,laypage = layui.laypage;
  layim.shopHost="<?=$searchModel->commodity()?>";
  
  //聊天记录的分页此处不做演示，你可以采用laypage，不了解的同学见文档：http://www.layui.com/doc/modules/laypage.html
 
  //开始请求聊天记录
  var param =  location.search //获得URL参数。该窗口url会携带会话id和type，他们是你请求聊天记录的重要凭据
  ,page = "<?=$page?>"
  //实际使用时，下述的res一般是通过Ajax获得，而此处仅仅只是演示数据格式
  ,res 
	$('#load_message').on('click',function(){
		//接受消息（如果检测到该socket）
	 $.get("<?=Url::to(['historys','to_id'=>\Yii::$app->request->get('to_id'),'fromusername'=>\Yii::$app->request->get('fromusername')])?>&page="+page, function(data){
	    if(data!==false){
	    	var html = laytpl(LAY_tpl.value).render({
			    data: data
			});
			page--;
			$('#LAY_view').prepend(html);
	    }
		});
	});
  //接受消息（如果检测到该socket）
  $.get("<?=Url::to(['historys','to_id'=>\Yii::$app->request->get('to_id'),'fromusername'=>\Yii::$app->request->get('fromusername')])?>&page="+page, function(data){
    if(data!==false){
      var html = laytpl(LAY_tpl.value).render({
        data: data
    });
    page--;
    $('#LAY_view').prepend(html);
    }
  });
  //console.log(param)
});
</script>
<?php $this->endBlock() ?>
