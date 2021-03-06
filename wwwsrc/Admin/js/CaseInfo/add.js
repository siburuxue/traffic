// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    /**************************************************************************************************/
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
    // 特殊字段手工注册
    submit.reg({
        group: 'gather',
        name: 'first_cognizance',
        get: function(name) {
            var data = new Array();
            $('input:checkbox[name="first_cognizance"]:checked').each(function() {
                data.push($(this).attr('value'));
            });
            return data;
        },
        set: function(name, value, data) {
            $('input:checkbox[name="first_cognizance"]').each(function() {
                var val = $(this).attr('value');
                $(this).prop('checked', $.inArray(val, value) >= 0);
            });
        }
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    window.location.href = msg.url;
                }
            });
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
    /**************************************************************************************************/
    // 创建日期拾取器
    
    
    /**************************************************************************************************/
});
