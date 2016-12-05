// 定义全局变量
var submit;
// 选中用户标识
var userMark = '';
// 选中的用户对象
var userSelected = {};
var userMultiple = true;
// 页面加载完毕
$(function() {
    /***********************************************************/
    // 创建提交对象
    submit = $.vmcSubmit();
    // 注册提交字段
    $('.post-gather').each(function() {
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
                layer.confirm(msg.info, {
                    btn: ['继续新增', '关闭窗口']
                }, function(index) {
                    layer.close(index);
                    // submit.reset();
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
    submit.reg({
        name: 'member',
        get: function(name) {
            return $('#select').data('user') || {};
        },
        set: function(name, value, data) {
            $('#select').data('user', value);
           // $('input[name="member"]').val('');
        }
    });
    submit.reg({
        name: 'reporter',
        get: function(name) {
            return $('#select1').data('user') || {};
        },
        set: function(name, value, data) {
            $('#select1').data('user', value);
            //$('input[name="reporter"]').val('');
        }
    });
    submit.reg({
        name: 'recorder',
        get: function(name) {
            return $('#select2').data('user') || {};
        },
        set: function(name, value, data) {
            $('#select2').data('user', value);
          //  $('input[name="recorder"]').val('');
        }
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute();
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    // 选择用户
    $('#select').on('click', function() {
        var the = $(this);
        var mark = 'member';
        userMark = mark;
        userSelected[mark] = $.extend({}, the.data('user'));
        userMultiple = true;
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
                $.each(data, function(id, name) {
                    names.push(name);
                });
                $('input[name="member"]').val(names.join());
            }
        });
    });
    /***********************************************************/
    // 选择用户
    $('#select1').on('click', function() {
        var the = $(this);
        var mark = 'reporter';
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
                $.each(data, function(id, name) {
                    names.push(name);
                });
                $('input[name="reporter"]').val(names.join());
            }
        });
    });
    /***********************************************************/
    /***********************************************************/
    // 选择用户
    $('#select2').on('click', function() {
        var the = $(this);
        var mark = 'recorder';
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
                $.each(data, function(id, name) {
                    names.push(name);
                });
                $('input[name="recorder"]').val(names.join());
            }
        });
    });
});
