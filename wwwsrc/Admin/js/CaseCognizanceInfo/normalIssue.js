// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.save, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function() {
                    $('.client-list-head[id=' + $('#client-id').val() + ']').click();
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
    //注册受送达人字段
    submit.reg({
        group: 'gather',
        name: "target_user_name",
        get: function(name) {
            return $('#client-relater-list option:selected').text();
        },
        set: function(name, value, data) {
            $("#client-relater-list option:contains('" + value + "')").prop('selected', true);
        }
    });
    //注册当事人ID
    submit.reg({
        group: 'gather',
        name: "clientIds",
        get: function(name) {
            return $('.client').map(function() {
                return $(this).attr('id');
            }).get()
        },
        set: function(name, value, data) {}
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    //点击获取当事人数据
    $('.client-list-head').on('click', function() {
        //清空数据
        $('input[type=text],select').val('');
        $('#client-id').val($(this).attr('id'));
        var data = {
            cate: $('#cate').val(),
            case_id: $('#case_id').val(),
            client_id: $(this).attr('id'),
            case_cognizance_id: $('#case_cognizance_id').val(),
        };
        $(this).find('ul').collapse('show');
        $.post(url.getClientInfo, data, function(msg) {
            if(msg.status == 1){
                var info = msg.info;
                $('#client-relater-list option:gt(0)').remove();
                if (info != null) {
                    $('#client-relater-list').append(function(){
                        return info.relaterList.map(function(x){ return '<option value="' + x['id'] + '">' + x['name'] + '</option>'; }).join('');
                    });
                    //文书名称、文号
                    $('#code').val(info['code']);
                    //送达时间
                    $('#post_time').val(info['post_time']);
                    //送达地点
                    $('#post_place').val(info['post_place']);
                    //送达方式
                    $('#post_method').val(info['post_method']);
                    //受送达人
                    $('#client-relater-list option:contains(' + info['target_user_name'] + ')').prop('selected', true);
                    //代收人
                    $('#receiver').val(info['receiver']);
                    //代收原因
                    $('#receiver_reason').val(info['receiver_reason']);
                    //与受送达人关系
                    $('#receiver_relation').val(info['receiver_relation']);
                    //送达人拒收原因
                    $('#rejection_reason').val(info['rejection_reason']);
                    //见证人
                    $('#witness').val(info['witness']);
                    //送达人
                    $('#sender').val(info['sender']);
                    //备注
                    $('#remark').val(info['remark']);
                    //主键
                    $('#id').val(info['id']);
                    $('#print').prop('disabled', false);
                    $('#btn-list').show();
                } else {
                    $('#print').prop('disabled', true);
                    $('#id').val('');
                    $('#btn-list').hide();
                }
            }
        }, 'json');
    })
    //画面加载获取当事人数据
    $('.client-list-head:eq(0)').click();
    $('#case-photo-upload,#case-photo-delete').remove();
    /**************************************************************************************************/
});
