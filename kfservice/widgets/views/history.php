<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<style type="text/css">
.navbar-inverse{display: none;}
.wrap > .container {
    padding: 0px 15px 20px;
}
</style>
<div class="row">
    <div data-spy="affix" data-offset="10" style="z-index: 1000" class="btn-group btn-group-justified" role="group" aria-label="...">
        <?php foreach ($httpArray as $key => $value): ?>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default loadfromusername" data-key="<?=$key?>" id="<?=$key?>" data-url="<?=$value?>">连接<?=$key?></button>
            </div>
        <?php endforeach;?>
        <div class="btn-group" role="group">
        <button type="button" id="Message1" class="btn btn-default" onclick="$('#MessageId').show();$('#External').hide();$('#iframe_box').hide()">消息记录</button>
        </div>
    </div>
    <div style="margin-top:20px;display: none;" id="External">
    <div class="panel panel-default">
      <div class="panel-footer">外部网址信息对接</div>
        <div class="panel-body">
            该设置会将当前用户的 <font style="color:red">openid</font> 追加到你设置的 <font style="color:red">url</font> 链接上,<br/>
            已方便你获取查看该用户的信息记录等等<br/>

            举例 : 设置 https://www.baidu.com/?openid= <br/>
            最终访问连接 ：https://www.baidu.com/?openid=OPENID
        </div>
      </div>
      <?= Html::Button('添加设置', ['class' => 'btn btn-flat btn-block bg-maroon','id'=>'external_save']) ?>
    </div>
    <div id="iframe_box" class="content-iframe">
        <iframe width="100%" height="750px" min-height="500px" id="history" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
<link rel="stylesheet" href="<?=Yii::$app->request->hostInfo?>/admin/layui/css/layui.css">
<div class="layim-chat-main" style="height: 100%;display: none;" id="MessageId">
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
<script>
layui.use(['layim', 'laypage'], function(){
  var layim = layui.layim
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,$ = layui.jquery
  ,laypage = layui.laypage;
  layim.shopHost="<?=$hostInfo?>";
  //聊天记录的分页此处不做演示，你可以采用laypage，不了解的同学见文档：http://www.layui.com/doc/modules/laypage.html
  //开始请求聊天记录
  var param =  location.search //获得URL参数。该窗口url会携带会话id和type，他们是你请求聊天记录的重要凭据
  ,page = undefined
  ,key = "browse"
  ,historythttp = "<?=$httpArray['browse']?>"
  ,fromusername = "<?=$fromusername?>"
  ,url = "<?=Url::to(['historys'])?>" 
  //实际使用时，下述的res一般是通过Ajax获得，而此处仅仅只是演示数据格式
    
    if(historythttp!=false){
      $('#history').attr('src',historythttp+fromusername) 
    }else{
      $("#External").show()
    }
    
    $('.loadfromusername').on('click',function(){
      $('#MessageId').hide();
      $('#iframe_box').show();
      key = $(this).data('key')
      historythttp = $(this).data('url')
      if(historythttp==false){
        $('#iframe_box').hide() 
        $("#External").show()
      }else{
        $("#External").hide()
        $('#history').attr('src',historythttp+fromusername) 
      }
    });
    
    $('#external_save').on('click',function(){
        layer.prompt({title: 'url设置', formType: 2}, function(text, index){
          $.post("<?=Url::to(['/kfservice/external/alter'])?>?name="+key, {'value':text}, function(res){
              if(res.errcode == 0){
                historythttp = res.errmsg
                $("#"+key).data('url',res.errmsg)
                $('#history').attr('src',historythttp+fromusername) 
                $("#External").hide()
                $("#"+key).click()
              }else{
                layer.alert(res.errmsg, {
                  icon: 2,
                  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                })
              }
           });
          layer.close(index);
        });
    });
    
    $('#load_message').on('click',function(){
        //接受消息（如果检测到该socket）
        $('#load_message').hide()
        var historyurl = url.indexOf('?')==-1?url + "?fromusername=" + fromusername: url + "&fromusername=" + fromusername
        if(page!==undefined){
          historyurl = historyurl+'&page='+page
        }
        $.get(historyurl, function(data){
            if(data!==false){
              page = data.page
                var html = laytpl(LAY_tpl.value).render({
                    data: data.array
                });
                page--;
                $('#LAY_view').prepend(html);
                $('#load_message').show()
            }
        });
    });
    
    $('#Message1').one('click',function(){
        var historyurl = url.indexOf('?')==-1?url + "?fromusername=" + fromusername: url + "&fromusername=" + fromusername
        $.get(historyurl, function(data){
            if(data!==false){
                page = data.page
                var html = laytpl(LAY_tpl.value).render({
                    data: data.array
                });
                page--;
                $('#LAY_view').prepend(html);
            }
        });
    });
    
    $('.btn-group-justified .btn-default').on('click',function(){
        $('.btn-default').removeClass('active');
        $(this).addClass('active');
    });

  window.loadsearch = function(openid){
    fromusername = openid;
    page = undefined;
    $('#LAY_view').html('')
    if(historythttp==false){
      $('#Message1').click()
    } else {
      $('#history').attr('src',historythttp+fromusername) 
    }
   }
}); 
</script>
<?php $this->endBlock() ?>


