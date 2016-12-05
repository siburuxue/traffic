// 定义全局变量
var submit;
// 选中用户标识
var userMark = '';
// 选中的用户对象
var userSelected = {};
var userMultiple = false;
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
                 window.location.href=msg.url;
            } else {
                layer.alert(msg.info);
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

$(function() {
    // 选择用户
    $('#select').on('click', function() {
        var the = $(this);
        var mark = 'member';
        userMark = mark;
        userSelected[mark] = $.extend({}, the.data('user'));
        userMultiple = false;
        layer.open({
            type: 2,
            title: 0,
            closeBtn: 0,
            shadeClose: false,
            shade: 0.8,
            scrollbar: false,
            move: false,
            area: ['100%', '100%'],
            content: url.selectUser,
            end: function() {
                var data = userSelected[mark] || {};
                the.data('user', data);
                var names = [];
                var ids = [];
                $.each(data, function(id, name) {
                    names.push(name);
                    ids.push(id);
                });
                $('#select').val(names.join());
                $('input[name="user"]').val(ids.join());
            }
        });
    });       
});

