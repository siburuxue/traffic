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

// 时间拾取
$(function() {
    // 创建日期拾取器
    
    
});

// duty_advance  高级全新操作人员 选择大队 读取大队所属值班人选 联动菜单
$(function() {
    $('#select-department').on('change', function() {
        //获取部门id
        var dep_id = $(this).children('option:selected').val();

        $.post(url.ajaxAllUsers,{'uid':dep_id}, function(msg) {

            //console.log(msg);
            if (msg.status==1) {
                $('#select-allUsers').val('');
                var obj=$('#select-allUsers')[0];
                var userData = msg.info;
                obj.options.length=1;
                for(var i=0;i<userData.length;i++){
                    obj.options.add(new Option(userData[i].true_name,userData[i].id));
                }
            } else {
                layer.alert('无可选值班人员', function(index) {
                    layer.close(index);
                });
            }
        });

    });

});
