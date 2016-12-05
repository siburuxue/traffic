// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增', '关闭窗口']
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
    // 注册可接受委托大队
    submit.reg({
        group: 'gather',
        name: 'department_id',
        get: function(name) {
            return $('input[name=department]:checked').map(function(){
                return {
                    'department_id':$(this).val()
                }
            }).get();
        },
        set: function(name, value, data) {

        }
    });
    // 注册可进行检验鉴定项目
    submit.reg({
        group: 'gather',
        name: 'checkup_org_item_id',
        get: function(name) {
            return $('input[name=checkup_org_item]:checked').map(function(){
                return {
                    'checkup_org_item_id':$(this).val()
                }
            }).get();
        },
        set: function(name, value, data) {
            
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
    /**************************************************************************************************/
});
