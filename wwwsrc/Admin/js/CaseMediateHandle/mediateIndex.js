// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.saveNotice, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function() {
                    $('.client-list-head[id=' + $('#client_id').val() + ']').click();
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    // 注册提交字段
    $('.post-gather').each(function() {
        var the = $(this);
        submit.reg({
            group: 'gather',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        $('.client-list-head:eq(0)').click();
    });
    //点击当事人获取信息
    $('.client-list-head').on('click', function() {
         var clientId = $(this).attr('id')
        //清空数据
        $('input[type=text],select,textarea').val('');
        $('#agent option:gt(0)').remove();
        $('#client_id').val(clientId);
        var data = {
            cate: 55,
            case_id: $('#case_id').val(),
            client_id: clientId,
            case_mediate_accept_id: $('#case_mediate_accept_id').val()
        };
        $(this).find('ul').collapse('show');
        $.post(url.getClientInfo, data, function(msg) {
            var info = msg.info.info;
            var relaterList = msg.info.relaterList;
            if(relaterList != null){
                //当事人相关人列表
                $('#agent').append(function(){
                    return relaterList.map(function(x){ return '<option value="' + x['id'] + '">' + x['name'] + '</option>'; }).join('');
                });
            }
            $('#case_client_id').val(clientId);
            if (info != null) {
                $('#agent').val(info['agent']);
                $('#content').val(info['content']);
                $('#handle_1').val(info['handle_1']);
                $('#handle_2').val(info['handle_2']);
                $('#contact').val(info['contact']);
                $('#id').val(info['id']);
                $('#print').prop('disabled',false);
            } else {
                $('#handle_1').val($('#userId').val());
                $('#id').val('');
                $('#print').prop('disabled',true);
            }

        }, 'json');
    });
    $('.client-list-head:eq(0)').click();
    /**************************************************************************************************/
});
