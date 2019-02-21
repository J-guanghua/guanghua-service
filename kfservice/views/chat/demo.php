<?php
use yii\helpers\Url;
?>

<link rel="stylesheet" href="http://172.18.42.200/admin/layui/css/layui.css">
<script src="http://172.18.42.200/admin/layui/layui.js"></script>
<?php $this->beginBlock('js') ?>
<script type="text/javascript">
layui.use('layim', function(layim){

  //演示自动回复
  var autoReplay = [
    '您好，我现在有事不在，一会再和您联系。', 
    '你没发错吧？face[微笑] ',
    '洗澡中，请勿打扰，偷窥请购票，个体四十，团体八折，订票电话：一般人我不告诉他！face[哈哈] ',
    '你好，我是主人的美女秘书，有什么事就跟我说吧，等他回来我会转告他的。face[心] face[心] face[心] ',
    'face[威武] face[威武] face[威武] face[威武] ',
    '<（@￣︶￣@）>',
    '你要和我说话？你真的要和我说话？你确定自己想说吗？你一定非说不可吗？那你说吧，这是自动回复。',
    'face[黑线]  你慢慢说，别急……',
    '(*^__^*) face[嘻嘻] ，是贤心吗？'
  ];
 
  //基础配置
  layim.config({

    //初始化接口
    init: {
      url: "<?=Url::to(['user-list'])?>" //（返回的数据格式见下文）
      //url: 'http://www.demo.com/admin/json/getList.json'
      ,data: {}
    }
    
    //或采用以下方式初始化接口
    /*
    ,init: {
      mine: {
        "username": "LayIM体验者" //我的昵称
        ,"id": "100000123" //我的ID
        ,"status": "online" //在线状态 online：在线、hide：隐身
        ,"remark": "在深邃的编码世界，做一枚轻盈的纸飞机" //我的签名
        ,"avatar": "a.jpg" //我的头像
      }
      ,friend: []
      ,group: []
    }
    */
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
      ,title: '代码'
      ,icon: '&#xe64e;'
    },
    {
      alias: 'link'
      ,title: '链接'
      ,icon: '&#xe64c;'
    }]
    
    //,brief: true //是否简约模式（若开启则不显示主面板）
    
    //,title: 'WebIM' //自定义主面板最小化时的标题
    //,right: '100px' //主面板相对浏览器右侧距离
    //,minRight: '90px' //聊天面板最小化时相对浏览器右侧距离
    ,initSkin: '5.jpg' //1-5 设置初始背景
    //,skin: ['aaa.jpg'] //新增皮肤
    //,isfriend: false //是否开启好友
    //,isgroup: false //是否开启群组
    //,min: true //是否始终最小化主面板，默认false
    ,notice: true //是否开启桌面消息提醒，默认false
    //,voice: false //声音提醒，默认开启，声音文件为：default.wav
    
    ,msgbox: layui.cache.dir + 'css/modules/layim/html/msgbox.html' //消息盒子页面地址，若不开启，剔除该项即可
    ,find: layui.cache.dir + 'css/modules/layim/html/find.html' //发现页面地址，若不开启，剔除该项即可
    ,chatLog: layui.cache.dir + 'css/modules/layim/html/chatLog.html' //聊天记录页面地址，若不开启，剔除该项即可
    
  });
  var cache = layui.layim.cache().mine.id;
  //console.log(cache);

/*
  layim.chat({
    name: '在线客服-小苍'
    ,type: 'kefu'
    ,avatar: 'http://tva3.sinaimg.cn/crop.0.0.180.180.180/7f5f6861jw1e8qgp5bmzyj2050050aa8.jpg'
    ,id: -1
  });
  layim.chat({
    name: '在线客服-心心'
    ,type: 'kefu'
    ,avatar: 'http://tva1.sinaimg.cn/crop.219.144.555.555.180/0068iARejw8esk724mra6j30rs0rstap.jpg'
    ,id: -2
  });
  layim.setChatMin();
  */

    //临时性的聊天组
    /*
  layim.chat({
      name: 'LayIM畅聊'
    , type: 'group' //群组类型
    , avatar: 'http://tp2.sinaimg.cn/5488749285/50/5719808192/1'
    , id: 10000000 //定义唯一的id方便你处理信息
    , members: 123 //成员数，不好获取的话，可以设置为0
  });
  */
    //初始化最小聊天化界面
 // layim.setChatMin();
  //监听在线状态的切换事件
  layim.on('online', function (data) {
   // console.log("在线状态："+data);
  });
  //监听自定义工具栏点击，以添加代码为例
  layim.on('tool(code)', function(insert, send, obj){ //事件中的tool为固定字符，而code则为过滤器，对应的是工具别名（alias）
    layer.prompt({
      title: '插入代码'
      ,formType: 2
      ,shade: 0
    }, function(text, index){
      layer.close(index);
      insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器，主要由insert完成
      //send(); //自动发送
    });
    console.log(this); //获取当前工具的DOM对象
    console.log(obj); //获得当前会话窗口的DOM对象、基础信息
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
      insert('文本内容....<a href="http://www.qq.com" data-miniprogram-appid="appid" data-miniprogram-path="pages/index/index">点击跳小程序</a>'); //将内容插入到编辑器
    });
  });

  layim.on('setSkin', function (filename, src) {
     // console.log(filename); //获得文件名，如：1.jpg
      //console.log(src); //获得背景路径，如：http://res.layui.com/layui/src/css/modules/layim/skin/1.jpg
  });
  
  //监听layim建立就绪
  layim.on('ready', function(res){

    //console.log(res.mine);
    
    layim.msgbox(5); //模拟消息盒子有新消息，实际使用时，一般是动态获得
  
    //添加好友（如果检测到该socket）
    layim.addList({
      type: 'group'
      ,avatar: "http://tva3.sinaimg.cn/crop.64.106.361.361.50/7181dbb3jw8evfbtem8edj20ci0dpq3a.jpg"
      ,groupname: 'Angular开发'
      ,id: "12333333"
      ,members: 0
    });
    layim.addList({
      type: 'friend'
      ,avatar: "http://tp2.sinaimg.cn/2386568184/180/40050524279/0"
      ,username: '冲田杏梨'
      ,groupid: 2
      ,id: "1233333312121212"
      ,remark: "本人冲田杏梨将结束AV女优的工作"
    });
    

//    layim.add({
//        type: 'friend' //friend：申请加好友、group：申请加群
//, username: 'ff' //好友昵称，若申请加群，参数为：groupname
//, avatar: 'http://tp2.sinaimg.cn/2386568184/180/40050524279/0' //头像
//, submit: function (group, remark, index) { //一般在此执行Ajax和WS，以通知对方
//    console.log("1:"+group); //获取选择的好友分组ID，若为添加群，则不返回值
//    console.log("2:"+remark); //获取附加信息
//    layer.close("3:"+index); //关闭改面板
//}
//    });

    setTimeout(function(red){
      //console.log(red);
      //接受消息（如果检测到该socket）
      layim.getMessage({
        username: "Hi"
        ,avatar: "http://qzapp.qlogo.cn/qzapp/100280987/56ADC83E78CEC046F8DF2C5D0DD63CDE/100"
        ,id: "10000111"
        ,type: "friend"
        ,content: "临时："+ new Date().getTime()
      });
      
      /*layim.getMessage({
        username: "贤心"
        ,avatar: "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
        ,id: "100001"
        ,type: "friend"
        ,content: "嗨，你好！欢迎体验LayIM。演示标记："+ new Date().getTime()
      });*/
      
    }, 3000);
  });

  //监听发送消息
//  layim.on('sendMessage', function(data){
//    var To = data.to;//对方的信息
//      // console.log("sadhj:" + JSON.stringify(data));
//      //data格式如下：
//      /*
//      {
//    "mine": {
//        "username": "纸飞机1",
//        "avatar": "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg",
//        "id": "100000",
//        "mine": true,
//        "content": "222"
//    },
//    "to": {
//        "username": "贤心",
//        "id": "100001",
//        "avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1",
//        "sign": "这些都是测试数据，实际使用请严格按照该格式返回",
//        "name": "贤心",
//        "type": "friend"
//    }
//   }
//   */
//    
//    if(To.type === 'friend'){
//      layim.setChatStatus('<span style="color:#FF5722;">对方正在输入。。。</span>');
//    }
//    
//    //演示自动回复
//    setTimeout(function(){
//      var obj = {};
//      if(To.type === 'group'){
//        obj = {
//          username: '模拟群员'+(Math.random()*100|0)
//          ,avatar: layui.cache.dir + 'images/face/'+ (Math.random()*72|0) + '.gif'
//          ,id: To.id
//          ,type: To.type
//          ,content: autoReplay[Math.random()*9|0]
//        }
//      } else {
//        obj = {
//          username: To.name
//          ,avatar: To.avatar
//          ,id: To.id
//          ,type: To.type
//          ,content: autoReplay[Math.random()*9|0]
//        }
//        layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
//      }
//
//      layim.getMessage(obj);
//    }, 1000);
    //  });


    



    //查看群成员得到的数据
    /*
    {
    "owner": {
        "username": "贤心",
        "id": "100001",
        "avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1",
        "sign": "这些都是测试数据，实际使用请严格按照该格式返回"
    },
    "members": 12,
    "list": [
        {
            "username": "贤心",
            "id": "100001",
            "avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1",
            "sign": "这些都是测试数据，实际使用请严格按照该格式返回"
        },
        {
            "username": "Z_子晴",
            "id": "108101",
            "avatar": "http://tva3.sinaimg.cn/crop.0.0.512.512.180/8693225ajw8f2rt20ptykj20e80e8weu.jpg",
            "sign": "微电商达人"
        },
        {
            "username": "Lemon_CC",
            "id": "102101",
            "avatar": "http://tp2.sinaimg.cn/1833062053/180/5643591594/0",
            "sign": ""
        },
        {
            "username": "马小云",
            "id": "168168",
            "avatar": "http://tp4.sinaimg.cn/2145291155/180/5601307179/1",
            "sign": "让天下没有难写的代码"
        },
        {
            "username": "徐小峥",
            "id": "666666",
            "avatar": "http://tp2.sinaimg.cn/1783286485/180/5677568891/1",
            "sign": "代码在囧途，也要写到底"
        },
        {
            "username": "罗玉凤",
            "id": "121286",
            "avatar": "http://tp1.sinaimg.cn/1241679004/180/5743814375/0",
            "sign": "在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱"
        },
        {
            "username": "长泽梓Azusa",
            "id": "100001222",
            "avatar": "http://tva1.sinaimg.cn/crop.0.0.180.180.180/86b15b6cjw1e8qgp5bmzyj2050050aa8.jpg",
            "sign": "我是日本女艺人长泽あずさ"
        },
        {
            "username": "大鱼_MsYuyu",
            "id": "12123454",
            "avatar": "http://tp1.sinaimg.cn/5286730964/50/5745125631/0",
            "sign": "我瘋了！這也太準了吧  超級笑點低"
        },
        {
            "username": "谢楠",
            "id": "10034001",
            "avatar": "http://tp4.sinaimg.cn/1665074831/180/5617130952/0",
            "sign": ""
        },
        {
            "username": "柏雪近在它香",
            "id": "3435343",
            "avatar": "http://tp2.sinaimg.cn/2518326245/180/5636099025/0",
            "sign": ""
        },
        {
            "username": "林心如",
            "id": "76543",
            "avatar": "http://tp3.sinaimg.cn/1223762662/180/5741707953/0",
            "sign": "我爱贤心"
        },
        {
            "username": "佟丽娅",
            "id": "4803920",
            "avatar": "http://tp4.sinaimg.cn/1345566427/180/5730976522/0",
            "sign": "我也爱贤心吖吖啊"
        }
    ]
}
    */

  //监听查看群员
  layim.on('members', function(data){
     // console.log("sdas:" + JSON.stringify(data));
  });
  
//切换窗口得到的信息
    /*
    {
    "elem": {
        "0": {},
        "length": 1,
        "prevObject": {
            "0": {},
            "length": 1,
            "prevObject": {
                "0": {},
                "length": 1,
                "context": {
                    "jQuery1123042241309879200917": 6
                },
                "selector": "#layui-layer3"
            },
            "context": {
                "jQuery1123042241309879200917": 6
            },
            "selector": "#layui-layer3 .layim-chat"
        },
        "context": {
            "jQuery1123042241309879200917": 6
        }
    },
    "data": {
        "username": "贤心",
        "id": "100001",
        "avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1",
        "sign": "这些都是测试数据，实际使用请严格按照该格式返回",
        "name": "贤心",
        "type": "friend"
    },
    "textarea": {
        "0": {
            "jQuery1123042241309879200917": 36
        },
        "length": 1,
        "prevObject": {
            "0": {},
            "length": 1,
            "prevObject": {
                "0": {},
                "length": 1,
                "prevObject": {
                    "0": {},
                    "length": 1,
                    "context": {
                        "jQuery1123042241309879200917": 6
                    },
                    "selector": "#layui-layer3"
                },
                "context": {
                    "jQuery1123042241309879200917": 6
                },
                "selector": "#layui-layer3 .layim-chat"
            },
            "context": {
                "jQuery1123042241309879200917": 6
            }
        },
        "context": {
            "jQuery1123042241309879200917": 6
        },
        "selector": "textarea"
    }
}
    */
  //监听聊天窗口的切换
  layim.on('chatChange', function (res) {
   //   console.log("sdas:" + JSON.stringify(res));
    var type = res.data.type;
   // console.log(res.data.id)
    if(type === 'friend'){
      //模拟标注好友状态
        //layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
        //只要开窗，我就先问1句好
        // var obj = {};
        // obj = {
        //     username: res.data.name
        //  , avatar: res.data.avatar
        //  , id: res.data.id
        //  , type: res.data.type
        //  , content: "你好啊！"
        // }
        // layim.getMessage(obj);
    } else if(type === 'group'){
      //模拟系统消息
      layim.getMessage({
        system: true
        ,id: res.data.id
        ,type: "group"
        ,content: '模拟群员'+(Math.random()*100|0) + '加入群聊'
      });
    }
  });
    //首先连接websocket
     var socket = new WebSocket('ws://172.18.42.200:9053');
    
      //  socket.send("hi server,i am layim!");
      socket.onopen= function() {
      //alert()  
        //console.log(layim.cache().mine);
        socket.send(JSON.stringify({
            action: 'login',//随便定义，用于在服务端区分消息类型
            data: {id:"<?=Yii::$app->request->get('id')?>"}
        }));
      }
      //监听收到的聊天消息，假设你服务端emit的事件名为：chatMessage
      socket.onmessage = function (res) {
          // console.log("ggas:"+res);
          console.log(res.data);
          // res = eval('('+res.data+')');
          // console.log(res['data']); 
          var message = JSON.parse(res.data);
          console.log(message.data.id);
          // console.log("sa:" + res.data);
          if (message.action === 'login') {
              //layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
              layer.msg(message.data.errmsg);
          }
          if (message.action === 'wechat') {
              //layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
              layer.msg(message.data.errmsg);
          }
          if (message.action === 'chatMessage') {
            //layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
            // layim.getMessage({
            //   username: message.data.username //消息来源用户名
            //   ,avatar: message.data.avatar //消息来源用户头像
            //   ,id: message.data.id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            //   ,type: "friend" //聊天窗口来源类型，从发送消息传递的to里面获取
            //   ,content: message.data.content //消息内容
            //   ,cid: 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
            //   ,mine: false //是否我发送的消息，如果为true，则会显示在右方
            //   ,fromid: message.data.id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
            //   ,timestamp: message.data.timestamp //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
            // });
            // res.data.mine = false;
            console.log(message.data.content);
            // message.data.content = parent.layui.layim.content(message.data.content)
            layim.getMessage(message.data);
          }
      };
  
     //监听socket发送消息
      layim.on('sendMessage', function (res) {
        //console.log(layim.cache().mine);
        // layim.getMessage({
        //   username: "纸飞机" //消息来源用户名
        //   ,avatar: "http://tp1.sinaimg.cn/1571889140/180/40030060651/1" //消息来源用户头像
        //   ,id: "100000" //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
        //   ,type: "friend" //聊天窗口来源类型，从发送消息传递的to里面获取
        //   ,content: "嗨，你好！本消息系离线消息。" //消息内容
        //   ,cid: 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
        //   ,mine: false //是否我发送的消息，如果为true，则会显示在右方
        //   ,fromid: "100000" //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
        //   ,timestamp: 1467475443306 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
        // });
          //console.log(res.to.id);
          // var mine = res.mine; //包含我发送的消息及我的信息
          //var to = res.to; //对方的信息
          //发送一个消息
          //socket.send(JSON.stringify({ "commandId": 3, "content": "{\"userName\": \"ipsa100OZEgBrKkCLmi7F7R1pUF8Q\",\"userPassword\":\"test\"}", "serviceId": 1 }));
          //socket.send(JSON.stringify({ "action": "chatMessage",content: res.mine.content, toUserId: res.to.id, fromUserId: res.mine.id}));
          res.mine.content = parent.layui.layim.content(res.mine.content)
          socket.send(JSON.stringify({
              action: 'chatMessage',//随便定义，用于在服务端区分消息类型
              data: res
          }));
      });
    
   
      //var local = layui.data('layim')[cache.mine.id]; //获取当前用户本地数据
      //console.log(local);
});

</script>
<?php $this->endBlock()?>
