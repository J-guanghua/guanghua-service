<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\wechat\search\SceneMobileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('创建', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>

<?php $this->endBlock() ?>
<style type="text/css">
.header-name{font-weight: bold;}
#className{  position: relative;
    overflow-x: hidden;
    overflow-y: scroll;
    width: 100%;
    height: 630px;};
</style>
<div class="article-index" style="height: 100%" id="article-index">
    <div class="row" style="height: 100%;background-color: #fff">
        <div class="col-xs-2" style="padding:0px;height: 100%">
            <div id="user-add-list" class="list-group">
             <div class="panel-heading"><input type="text" class="form-control" placeholder="Search for..."></div>
                <ul class="list-group" id="className">
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                     <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                     <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                     <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                     <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                     <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                </ul>
            </div>
        </div>
        <div class="col-xs-7" style="padding:0px;height: 600px">
            <div class="panel panel-default" style="height: 100%">
                <div class="panel-body" style="height: 10%">
                    <?=Html::img(Yii::$app->user->identity->headimgurl,['width'=>50])?>
                </div>
                <div class="panel-body" style="height: 70%">
                    <div id="messages">
                        <div style="position: relative;
                                    overflow-x: hidden;
                                    overflow-y: scroll;
                                    width: 100%;
                                    height: 450px;">
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                            <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>

                        </div>
                    </div>
                </div>
                <div class="panel-heading" style="height: 25%;">
                <li class="list-group-item row" id="11" data-uid="1"><span class="badge">消息记录</span>图片,链接</li>
                     <div class="input-group row">
                        <textarea name="" id="input-message" placeholder="Press enter to send" v-model="content" v-on:keyup.enter="send" style="height: 65px;width: 100%"></textarea>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="btn-send" style="height: 60px">发送!</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
        </div>
         <div class="col-xs-3" style="padding:0px;height: 100%">
            <div>
                <div>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                    <li class="list-group-item" id="11" data-uid="1"><span class="badge">0</span>dwdw</li>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('js') ?>
<script type="text/javascript">
    var messages = $('#messages');
    var connected = false;
    var uid = "<?=Yii::$app->user->id?>";
    var avatars = "<?=Yii::$app->user->identity->headimgurl?>";
    var nickname = "<?=Yii::$app->user->identity->username?>";
    var to = null;
    var type = 'content';
    var arraymessage = [];
    function showMessage(messages) {
        var message = JSON.parse(messages)
        switch(message['type']){
            case 'open':
                log(messages);
                break;
            case 'close':
                log(message['message']);
                break;
            case 'login':
                showUsetList(message['message']);
                break;
            case 'image':
                var msg = '<img src="'+message['message'] +'" style="width:80px">';
                break;
            case 'content':
                var msg = message['message'];
                showMessageUser(message,msg)
                break;
        }
       
    }
    function showMessageUser(message,msg) {
        console.log(message['message'])
        var touserid = uid==message['uid']?message['to']:message['uid']
        var user_to = uid + '-' + touserid;
        creatediv(user_to,message['uid'])
        var messages = $("#"+user_to)
        if (message['uid']==uid) {
            messages.append('<ul class="list-group"><a href="" style="color:#333">' + message['nickname'] + ' : ' + message['date'] + '</a><li class="list-group-item" style="border:0px;padding:2px 2px">'+ msg + '</li></ul>');
        } else {
            var summeg = $('#user_message_'+message['uid']).find('span').text();
            var number = parseInt(summeg)+1;
            $('#user_message_'+message['uid']).find('span').html(number)
            console.log(number);
            messages.append('<ul class="list-group"><a href="">' + message['nickname'] + ' : ' + message['date'] + '</a><li class="list-group-item" style="border:0px;padding:2px 2px">'+ msg + '</li></ul>');
        }
    }
    function showUsetList(array) {
         // console.log(array)
        to = array[0]['uid']
        array.forEach(function(e){
            $('#user-add-list ul').append('<li class="list-group-item" id="user_message_'+e['uid']+'" data-uid="'+e['uid']+'"><span class="badge">0</span>'+e['nickname']+'</li>');
        });
        $('#user-add-list ul li:first-child').addClass("active");
    }
    function log(message) {
        messages.append('<p class="text-muted">'+message+'</p>');
    }
    alert()
    // 测试时替换为你的 IP
    var socket = new WebSocket('ws://172.18.42.200:9053');
    //var socket = new WebSocket('ws://172.18.42.200:9053');
    log('连接中...');
    //连接成功时触发
    socket.onopen = function () {
        connected = true;
        var fileInfo={
                "type":"login",
                "uid":uid,  
                "nickname":nickname,  
                "avatars":avatars
        };
        var message = JSON.stringify(fileInfo) 
        socket.send(message);
        //log('连接成功');
    };

    socket.onerror = function () {
        log('连接失败');
    };

    socket.onmessage = function (res) {
        // console.log(res)
        showMessage(res.data);
    };

    // 监听Socket的关闭
    socket.onclose = function (event) {
        log('连接已断开！');
    };

    $(document).on('click', '#btn-send', function () {
        message = $('#input-message').val().trim();
        if (message.length == 0) {
            alert('请填写消息');
            return;
        }
        var fileInfo={
                "type":type,
                "uid":uid,  
                "nickname":nickname,  
                "avatars":avatars,  
                "message":message,
                "to":to  
        }; 
        var message = JSON.stringify(fileInfo)
        socket.send(message);
        $('#input-message').val('');
    });
    $(document).on('click', '#user-add-list ul li', function () {
        to = $(this).data('uid')
        var user_to = uid+'-'+to;
        $('ul .list-group-item').removeClass("active")
        $(this).addClass("active");
        creatediv(user_to,uid)
         $('#user_message_'+to).find('span').html(0)
        //console.log(parentdiv)
        $('#messages div').hide()
        $('#'+user_to).show()
    })
    
    function creatediv(user_to,touserid) {
        var parentdiv = $('#'+user_to).data('user-to')
        if ( parentdiv === undefined) {
            var isshow = uid==touserid?'':'display:none'
            var parentdiv = $('<div id="'+user_to+'" data-user-to="'+user_to+'" style="'+isshow+'"></div>');
            $("#messages").append(parentdiv)
            console.log(parentdiv)
        }
    }
</script>
<script type="text/javascript">
alert()
    new Vue({
        el:"#message-index",
        data:{
            list:[]
        },
        filters:{

        },
        mounted:function(){
            this.$nextTick(function(){
                this.createView()
            });
        },
        methods:{
          
        },
        created : function(){
            alert()
            let _this = this;
                  let conn = new WebSocket('ws://127.0.0.1:9501');

            conn.onopen = function(evt){
                _this.showNotice(' 连接成功！','success');
                _this.changeStatus(true);
            }
            conn.onclose = function(evt){
                _this.showNotice(' 已断开连接！','error');
                _this.changeStatus(false);
            }
            conn.onmessage = function(evt){
                let msg = JSON.parse(evt.data);

                switch(msg.type){
                    case 'connect':
                        console.log(msg.data);
                        _this.addUser(msg.data);
                        _this.setCount(msg.data.count);
                        break;
                    case 'disconnect':
                        _this.removeUser(msg.data.id);
                        _this.setCount(msg.data.count);
                        break;
                    case 'self_init':
                        _this.setUser(msg.data);
                        _this.setCount(msg.data.count);
                        break;
                    case 'other_init':
                        _this.addUser(msg.data);
                        break;
                    case 'message':   
                        _this.addMessage(msg.data);

                        break;
                }
            }

            _this.setConn(conn);
        },

    }); 
</script>
<?php $this->endBlock()?>
<!--  -->