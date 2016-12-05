// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
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
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            layer.alert(msg.info, function(index) {
                if (msg.status == 1) {
                    parent.layer.close(win_index);
                }
                layer.close(index);
            });
        });
    };
    submit.reg({
        group: 'gather',
        name: 'receiver',
        get: function(name) {
            var data = new Array();
            $('input:checkbox[name="receiver"]:checked').each(function() {
                var the = $(this);
                var item = {
                    case_client_id: the.data('client'),
                    case_client_relater_id: the.data('relater'),
                    msg_mobile: the.data('mobile'),
                    msg_name: the.data('name')
                }
                data.push(item);
            });
            return data;
        },
        set: function(name, value, data) {
            $('input:checkbox[name="receiver"]').each(function() {
                var the = $(this);
                var checked = false;
                $.each(value, function(i, n) {
                    if (the.data('client') == n.case_client_id && the.data('relater') == n.case_client_relater_id && the.data('mobile') == n.msg_mobile && the.data('name') == n.msg_name) {
                        checked = true;
                    }
                });
                $(this).prop('checked', checked);
            });
        }
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
});
