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
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增']
                    closeBtn:0
                }, function(index) {
                    layer.close(index);
                    
                    window.location.reload(true);
                    submit.reset();
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                    //window.location.reload(true);
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
    	window.location.reload(true);
        submit.reset();
    });
});

// 时间拾取
$(function() {
    // 创建日期拾取器
    
    
});

