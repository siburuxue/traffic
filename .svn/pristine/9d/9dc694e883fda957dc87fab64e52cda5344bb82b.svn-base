// 定义全局变量
var submit;
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
    // 注册其他字段
    $('.post-other').each(function() {
        var the = $(this);
        submit.reg({
            group: 'other',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 特殊字段手工注册
    submit.reg({
        group: 'gather',
        name: 'post',
        get: function(name) {
            var data = new Array();
            $('input:checkbox[name="post"]:checked').each(function() {
                data.push($(this).attr('value'));
            });
            return data;
        },
        set: function(name, value, data) {
            $('input:checkbox[name="post"]').each(function() {
                var val = $(this).attr('value');
                $(this).prop('checked', $.inArray(val, value) >= 0);
            });
        }
    });
    submit.reg({
        group: 'gather',
        name: 'role',
        get: function(name) {
            var data = new Array();
            $('input:checkbox[name="role"]:checked').each(function() {
                data.push($(this).attr('value'));
            });
            return data;
        },
        set: function(name, value, data) {
            $('input:checkbox[name="role"]').each(function() {
                var val = $(this).attr('value');
                $(this).prop('checked', $.inArray(val, value) >= 0);
            });
        }
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['留在本页', '关闭窗口']
                }, function(index) {
                    layer.close(index);
                    window.location.reload();
                }, function(index) {
                    layer.close(index);
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
});
