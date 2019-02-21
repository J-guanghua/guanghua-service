<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<style type="text/css">
<?=$config['wicket']?".layim-tab-content{height: 428px}":".layim-tab-content{height: 548px}"?>

.layim-chat-main{height: 342px} 
.wrap{
  background:url("/<?=mt_rand(2,5)?>.jpg");
  background-size:100% 100%;
  width: 100%;
  height:  100%;
  margin:0px;
  padding: 0px;
  background-repeat:no-repeat;
} 
</style>
<?php $this->beginBlock('js') ?>
<script type="text/javascript">
layui.use('layim', function(layim){
  var ti_index
  ,windowLoad = true
  ,message_card = "<?=$config['message_card']?>"
  ,wicket = "<?=$config['wicket']?>"
  //基础配置
  layim.config({
    //初始化接口
    init: {
      url: "<?=Url::to(['user-list'])?>" //（返回的数据格式见下文）

    }
    //查看群员接口
    ,members: {
      url: 'json/getMembers.json'
      ,data: {}
    }
    //上传图片接口
    ,uploadImage: {
      url: "<?=Url::to(['upload','action'=>'uploadimage'])?>" //（返回的数据格式见下文）
      ,type: 'post' //默认post
    } 
    //上传文件接口
    ,uploadFile: {
      url: '/upload/file' //（返回的数据格式见下文）
      ,type: '' //默认post
    }
    //扩展工具栏
    ,tool: [{
      alias: 'code'
      ,title: '微信消息'
      ,icon: '&#xe641;'
    },
    {
      alias: 'change'
      ,title: '消息转接'
      ,icon: '&#xe64d;'
    }
    ,{
      alias: 'finish'
      ,title: '结束回话'
      ,icon: '&#x1007;'
    },{
      alias: 'shortcut'
      ,title: '快捷语回复'
      ,icon: '&#xe60a;'
    }]
    ,width:276
    ,pattern:parseInt(wicket)
    ,height:parseInt(wicket)?600:document.body.clientHeight-50//document.body.clientHeight-50+'px'
    ,shopHost:"<?=$config['commodity']?>"//"https://jxuan.camel.com.cn/
    //,right: document.body.clientWidth-280+'px' //主面板相对浏览器右侧距离
    ,initSkin: '5.jpg' //1-5 设置初始背景
    ,chatLogs:1
    ,msgbox:"<?=Url::to(['/kfservice/message/index','MessageSearch[to_id]'=>\Yii::$app->user->id])?>" //发现页面地址，若不开启，剔除该项即可
    ,find: layui.cache.dir + 'css/modules/layim/html/find.html' //发现页面地址，若不开启，剔除该项即可
    ,chatLog:"<?=Url::to(['history'])?>" //聊天记录页面地址，若不开启，剔除该项即可
    
  });
  var cache = layui.layim.cache().mine.id;
  var j = layim.cache()
  j.base.linkStatus = false;
  //监听在线状态的切换事件
  layim.on('online', function (data) {
      $.get("<?=Url::to(['online'])?>",{status:data}, function(data){
        layer.msg('切换成功')
      });
  });
  layim.on('tool(storage)', function (data) {
        $.get("<?=Url::to(['/kfservice/menu/list'])?>", function(data){
          $("#shortcut").html(data)
          $("#app-wechats").modal();
        });
  });
  //监听自定义工具栏点击快捷语
  layim.on('tool(shortcut)', function(insert, send, obj){
      layer.close(ti_index)
      ti_index = layer.open({
        type: 2,
        maxmin: !0,
        title: "快捷语回复",
        area: ["420px", document.body.clientHeight-50+'px'],
        shade: !1,
        offset: "rb",
        move:false,
        skin: "layui-box",
        anim: 2,
        id: "layui-layim-chatlogs",
        content: "<?=Url::to(['/kfservice/menu/list'])?>",
        success: function (layero, index) {
        var body = layer.getChildFrame('body', index);//通过该对象可以获取iframe中的dom元素
        body.on('click','.wrap .glyphicon-edit',function(){
          insert($(this).data('data'));
        })
        },
    });
  }); 

  //监听自定义工具栏点击，以添加代码为例
  layim.on('tool(code)', function(insert, send, obj){ 
    if(message_card){

      layer.close(ti_index)
      ti_index = layer.open({
          type: 2,
          maxmin: !0,
          title: "消息卡片",
          area: ["420px", document.body.clientHeight-50+'px'],
          shade: !1,
          offset: "rb",
          move:false,
          skin: "layui-box",
          anim: 2,
          resize: !1,
          id: "layui-layim-chatlogs",
          content: message_card,
          success: function (layero, index) {
            var hasClass = function(e){ 
                var message = JSON.parse(e.data);
                if(confirm("确定发送消息卡片?")){
                  if(message.msgtype == 'miniprogrampage'){
                    var jsondata = JSON.stringify(message.miniprogrampage);
                    var text = 'miniprogrampage['+jsondata+']';
                  }
                  if(message.msgtype == 'link'){
                    var jsondata = JSON.stringify(message.link);
                    var text = 'link['+jsondata+']';
                  }
                  $('.layui-show .layim-chat-textarea textarea').val(text);
                  send()
                  return false;
                };
            }
          if(windowLoad){
            window.addEventListener('message',hasClass,true);
          }
          windowLoad = false;
        },
        end:function(){
          window.removeEventListener("message",hasClass,false);
        }
      });
    }else{
        layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: "520px"
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: ['设置链接', '取消设置']
            ,btnAlign: 'c'
            ,resize: !1
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 15px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;"><h4 style="color:green">微信客服消息设置</h4>支持微信消息类型<br/> (小程序卡片 ：miniprogrampage类型 )<br/> (图文链接 ：link类型 )<br/> (图片消息 ：image )<br/>你只需要在你设置的网页链接 ( 点击元素中设置两个节点 )<br/> 1 , onclick="window.parent.postMessage(微信客服消息接口json格式,"*");"<br/>参考值 ：<div style="color:green">&lt;div onclick="window.parent.postMessage({"msgtype": "link","link": {"title": "Happy Day","description": "Is Really A Happy Day","url": "URL","thumb_url": "THUMB_URL"}},"*");" &gt;....&lt;/div&gt;<div></div>'
            ,success: function(layero){
              var btn = layero.find('.layui-layer-btn');
              btn.find('.layui-layer-btn0').on('click',function(){

                layer.prompt({title: '设置我的url链接', formType: 2}, function(text, index){
                    $.post("<?=Url::to(['/kfservice/external/alter','name'=>'message_card'])?>", {'value':text}, function(res){
                        if(res.errcode == 0){
                          layer.close(index);
                          message_card = res.errmsg
                          layer.msg('设置成功');
                        }else{
                          layer.alert(res.errmsg, {
                            icon: 2,
                            skin: 'layer-ext-moon'
                          })
                        }
                    });
                });
              });
            }
        });
    }    
  }); 
  //监听结束会话
  layim.on('tool(finish)', function(insert, send, obj){ //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）

    //layer.prompt({title: '回复会话结束语，并确认', formType: 2,'value':' '}, function(text, index){
      obj.data.finish = '';
      $.post("<?=Url::to(['finish'])?>", obj.data, function(res){
          if(res){
            layim.removeList({
              type: 'friend' //或者group
              ,id: obj.data.id //好友或者群组ID
            });
	          layim.setPolling();
            $('.layim-friend'+obj.data.id).find('i').click()
          } else {
            layer.msg('操作失败,当前用户不在会话列表！')
          }
       });
      layer.close(index);
    //});
  });
  //监听签名修改
  layim.on('sign', function(value){
   // console.log("修改签名："+value);
  });

  //监听自定义工具栏点击，以添加代码为例
  layim.on('tool(link)', function(insert){
    layer.prompt({
      title: '插入代码'
      ,formType: 2
      ,shade: 0
    }, function(text, index){
      layer.close(index);
      insert('文本内容....<a href="http://www.qq.com" data-miniprogram-appid="wxf8ef6247c7de6285" data-miniprogram-path="pages/index/index">点击跳小程序</a>'); //将内容插入到编辑器
    });
  });
  //监听自定义工具栏点击，以添加代码为例
  layim.on('tool(change)', function(insert, send, obj){
    console.log(obj.data.id)
    $.get("<?=Url::to(['through'])?>", function(data){
        if (data) {
          layim.setFriendGroup({
            type: 'friend'
            ,username: obj.data.username
            ,avatar: obj.data.avatar
            ,group: data //获取好友分组数据
            ,submit: function(group, index){
              // othis.parent().html('已同意');
              //实际部署时，请开启下述注释，并改成你的接口地址
              obj.data.to_id = group;
              $.post("<?=Url::to(['associate'])?>", obj.data, function(res){
                  layer.close(index);
                  if (res.errcode==0) {
                    layer.msg(res.errmsg);
                    layim.removeList({
                      id: obj.data.id
                      ,type: 'friend'
                    });
		    layim.setPolling();
                    $('.layim-friend'+obj.data.id).find('i').click()
                  } else {
                    layer.msg(res.errmsg);
                  }
              });
            }
          });
        }else{
          layer.msg('暂时没有在线')
        }
    });
  });
  layim.on('setSkin', function (filename, src) {
     // console.log(filename); //获得文件名，如：1.jpg
      //console.log(src); //获得背景路径，如：http://res.layui.com/layui/src/css/modules/layim/skin/1.jpg
  });
  //监听layim建立就绪
  layim.on('ready', function(res){

  });

  //监听查看群员
  layim.on('members', function(data){
     // console.log("sdas:" + JSON.stringify(data));
  });
     
  //监听聊天窗口的切换
  layim.on('chatChange', function (res) {
    if(res.data.type === 'friend'){
      layim.getPolling(res.data);
    } 
  });
    //首先连接websocket
     var SocketHost = "<?=$SocketHost?>";
     var socket = new WebSocket(SocketHost);
      socket.onopen= function(res) {

      }
      socket.onerror= function(res) {
          var j = layim.cache()
          j.base.linkStatus = false;
         layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '300px;'
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: ['重新连接', '取消连接']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">抱歉！连接被关闭 , 系统出错,你需要刷新重新连接！(目前系统还属于试用阶段！)</div>'
            ,success: function(layero){
              var btn = layero.find('.layui-layer-btn');
              btn.find('.layui-layer-btn0').attr({
                href: window.location.href
              });
            }
          });
      }
      socket.onclose= function(res) {
          var j = layim.cache()
          j.base.linkStatus = false;
          layer.open({
            type: 1
            ,title: false //不显示标题栏
            ,closeBtn: false
            ,area: '300px;'
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: ['重新连接', '取消连接']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">抱歉！连接被关闭 , 系统出错,你需要刷新重新连接！(目前系统还属于试用阶段！)</div>'
            ,success: function(layero){
              var btn = layero.find('.layui-layer-btn');
              btn.find('.layui-layer-btn0').attr({
                href: window.location.href
              });
            }
          });
      }
      //监听收到的聊天消息，假设你服务端emit的事件名为：chatMessage
      socket.onmessage = function (res) {

          var message = JSON.parse(res.data);
          if (message.action === 'swooleLogin') {
              var urlhost = "<?=Url::to(['message-user'])?>";
              urlhost = urlhost.indexOf('?')==-1 ? urlhost+"?fd="+message.data.fd : urlhost+"&fd="+message.data.fd 
              $.get(urlhost, function(data){
                if(data.errcode==0){
                  var j = layim.cache()
                  j.base.linkStatus = undefined;
                  layer.msg(data.errmsg);
                  var status = $('.layui-layim-status');
                  var index = data.status == 'hide' ? 2 : 1;
                  status.find('span').attr('class','layui-icon layim-status-'+data.status);
                  status.find('ul li').removeClass('layim-this');
                  status.find('ul li:nth-child('+index+')').attr('class','layim-this');
                } else {
                  layer.msg(data.errmsg);
                }
              });
          }
          console.log(message)
          switch (message.action) {
            case 'chatMessage':
              layim.setPolling(message.data);
              break;
            case 'RemoveEvent':
              layim.removeList({
                type: 'group' //或者group
                ,id: message.data.id //好友或者群组ID
              });
              break;
            case 'addMessageEvent':
              layim.addList(message.data);
              break;
            case 'addNoticeEvent':
              layer.msg(message.data, {
                offset: 't',
                anim: 6,
                time:8*1000
              });
              break;
          }
      };
  
     //监听socket发送消息
      layim.on('sendMessage', function (res) {
          $('.layim-friend'+res.to.id).find('.layim-msg-status').remove();
          socket.send(JSON.stringify({
              action: 'chatMessage',//随便定义，用于在服务端区分消息类型
              data: res
          }));
      });
      var local = layui.data('layim')[cache]; //获取当前用户本地数据
      layim.setInterval(4);
});
$(function(){
  var imgReader = function( item ){
    var blob = item.getAsFile(),reader = new FileReader();
    reader.onload = function( e ){
        imgSrc = e.target.result;
        layer.confirm('是否要发送该截图?', {icon: 3, title:'发送截图'}, function(index){
            $.post("<?=Url::to(['img-reader'])?>",{imageBase64Content:imgSrc},function(result){
                if(result.errcode == 0){
                  
                    $('.layui-show .layim-chat-textarea textarea').val('img['+result.imgpath+']');
                    layer.close(index);
                }else{
                    alert(result.msg);
                }
            },'json');
            layer.close(index);
        });
    };
    console.log(blob)
    reader.readAsDataURL( blob );
};
try{
    $('body').unbind('paste',".layim-chat-textarea textarea").bind('paste',".layim-chat-textarea textarea",function(e){
       var clipboardData = event.clipboardData || window.clipboardData || event.originalEvent.clipboardData;
        var   i = 0, items, item, types;
        if( clipboardData ){
            items = clipboardData.items;
            if( !items ){
                return;
            }
            item = items[0];
            types = clipboardData.types || [];
            for(var i = 0; i < types.length; i++ ){
                if( types[i] === 'Files' ){
                    item = items[i];
                    break;
                }
            }
            if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
              var file = item.getAsFile()
              imgReader( item );
            }
        }
    });
}catch (e){console.log(e)}
})
</script>
<?php $this->endBlock()?>
