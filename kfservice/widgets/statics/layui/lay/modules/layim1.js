    var u = function (i) {
        console.log(i)
        var a = {
            friend: "该分组下暂无好友",
            group: "暂无群组",
            history: "暂无历史会话",
            wechat:"暂无任何公众号"
        };
        return i = i || {},
        i.item = i.item || "d." + i.type,
        ["{{# var length = 0; layui.each(" + i.item + ", function(i, data){ length++; }}", '<li layim-event="'+ ("group" === i.type ?'':'chat') +'" data-type="' + i.type + '" data-index="{{ ' + (i.index || "i") + ' }}" class="layim-' + ("history" === i.type ? "{{i}}" : i.type + "{{data.id}}") + ' {{ data.status === "offline" ? "layim-list-gray" : "" }}"><img src="{{ data.avatar }}"><span>{{ data.username||data.groupname||data.name||"佚名" }}</span><cite><i style="position:absolute;right:25px;font-size:3px;color:#ccc">{{layui.data.dateday(data.updated_at*1000) }}</i></cite><p id="message-{{data.id}}">{{ data.remark||data.sign||"" }}</p>'+("group" === i.type ? '<span layim-event="fromusername" data-fromusername="{{data.fromusername}}" class="layim-msg-status fromusername_to" style="display:block">接入会话</span>' :'<span id="tips-{{data.id}}" class="layim-msg-status" style="display:{{data.unread?"block":"none"}}">{{data.unread}}</span>' )+'</li>', "{{# }); if(length === 0){ }}", '<li class="layim-null">' + (a[i.type] || "暂无数据") + "</li>", "{{# } }}"].join("")
    },
            fromusername:function(i){
            var fromusername = i.data('fromusername')
            e.ajax({
                url: i.url,
                type: "post",
                data: {fromusername:fromusername},
                dataType:"json",
                cache: !1,
                success: function (res) {
                    layui.layim.removeList({
                      type: 'group' //或者group
                      ,id: res //好友或者群组ID
                    });
                },
                error: function (i, a) {
                    window.console && console.log && console.error("LAYIM_DATE_ERROR：" + a)
                }
            })
        },