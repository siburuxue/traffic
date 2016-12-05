// 定义全局变量
var submit;
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $('#table-content').load(url.table, data, function(response, status, xhr) {
            var $table = $(this).find('#table');
            $table.find('.js-end-refresh').data('end', function() {
                submit.execute('page');
            });
        });
    };
    // 注册字典项编号
    submit.reg({
        name: 'dict_id',
        get: function(name) {
            return $('#dict_id').val();
        },
        set: function(name, value, data) {
            $('#dict_id').val(value);
        }
    });
    // 刷新事件
    $('.js-end-refresh').data('end', function() {
        submit.execute('page');
    });
    // 排序
    $('#table-content').on('blur', 'input[name="train"]', function() {
        var the = $(this);
        if (the.val() == '') {
            the.val(the.attr('value'));
            return;
        }
        if (the.val() == the.attr('value')) {
            return;
        }
        $.post(url.train, {
            id: the.closest('tr').data('id'),
            train: the.val()
        }, function(msg) {
            if (msg.status == 1) {
                the.attr('value', the.val());
                submit.execute();
            } else {
                the.val(the.attr('value'));
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    });
    // 初始化
    submit.execute();
});
