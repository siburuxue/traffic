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

    // 特殊字段手工注册
    submit.reg({
        group: 'gather',
        name: 'code',
        get: function(name) {
            var data = new Array();
            $("input[name='code']").each(function() {
                data.push($(this).val());
            });
            return data;
        },
        set: function(name, value, data) {
            $("input[name='code']").each(function() {
                //var val = $(this).val();
                //$(this).val(val);
            });
        }
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增'],
                    closeBtn: 0
                }, function(index) {
                    layer.close(index);
                    submit.reset();
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

// 时间拾取
$(function() {
    // 创建日期拾取器
    
    
});


