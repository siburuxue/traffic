// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    /**************************************************************************************************/
    // 创建提交对象
    submit = $.vmcSubmit();
    // 注册提交字段
    $('.auto-gather').each(function() {
        var the = $(this);
        submit.reg({
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
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    window.location.reload();
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    /**************************************************************************************************/
    // 保存
    $('#save').on('click', function() {
        submit.execute();
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    // 打印
    $('#print').on('click', function() {
        alert('打印');
    });
    /**************************************************************************************************/
    // 提请审批
    var confirmWin;
    $('#submit').on('click', function() {
        confirmWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#confirm-box'),
            zIndex: 999,
            title: 0
        });
    });
    // 关闭
    $('#confirm-close').on('click', function() {
        layer.close(confirmWin);
    });
    // 提交
    $('#submit-check').on('click', function() {
        var dom = $('#confirm-box').find('input:radio[name="checkUserId"]:enabled:checked');
        if (dom.length <= 0) {
            layer.alert('请选择审核人');
            return false;
        }
        $.post(url.applyCheck, {
            case_id: $('input[name="case_id"]').val(),
            case_review_check_id: $('input[name="id"]').val(),
            check_user_id: dom.val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    layer.close(confirmWin);
                    window.location.reload();
                });
            } else {
                layer.alert(msg.info);
            }
        });
    });
    /**************************************************************************************************/
});
