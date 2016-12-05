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
        $.post(url.insert, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{
                    shade:0.1,
                    shadeClose:true
                },function(index) {
                    layer.close(index);
                    window.location.href = msg.url;
                    //submit.reset();
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
// 鉴定对象联动
$(function() {
    $('#car-people-other').on('change', function() {
        //获取部门id
        var type_id = $(this).children('option:selected').val();
        var case_id = $('#case_id').val();
        if (type_id == 3) {
            // $('#car-people-other-child').removeClass('post-gather');
            $('#car-people-other-child').hide(100);
            // $('#type_other').addClass('post-gather');
            $('#type_other').show();
        } else {
            // $('#car-people-other-child').addClass('post-gather');
            $('#car-people-other-child').show();
            // $('#type_other').removeClass('post-gather');
            $('#type_other').hide();
        }
        $.post(url.getTypeChild, { 'type_id': type_id, 'case_id': case_id }, function(msg) {
            if (msg.status == 1) {
                //鉴定对象子选项
                var obj = $('#car-people-other-child')[0];
                var allChildren = msg.info.objc;
                obj.options.length = 1;
                for (var i = 0; i < allChildren.length; i++) {
                    if (type_id == 1) {
                        obj.options.add(new Option(allChildren[i].case_client_name, allChildren[i].case_client_id));
                    }
                    if (type_id == 2) {
                        if (allChildren[i].case_client_car_no == "") {
                            // allChildren[i].case_client_car_no = "未填写";
                            allChildren[i].case_client_car_no = "";
                        }
                        if (allChildren[i].case_client_car_no != "") {
                        obj.options.add(new Option(allChildren[i].case_client_car_no, allChildren[i].case_client_id));
                       }
                    }
                }
                //鉴定类型
                var obj_type = $('#checktpye_child')[0];
                var checktpyeChild = msg.info.checktype;
                obj_type.options.length = 1;
                for (var n = 0; n < checktpyeChild.length; n++) {
                    if (checktpyeChild[n].name == "") {
                        //checktpyeChild[n].name = "未填写";
                        checktpyeChild[n].name = "";
                    }
                    if (checktpyeChild[n].name != "") {
                    obj_type.options.add(new Option(checktpyeChild[n].name, checktpyeChild[n].id));
                    }
                }
            } else {
                layer.alert('无更多子选项', function(index) {
                    layer.close(index);
                });
            }
        });
    });
});
// 鉴定机构名称联动
$(function() {
    $('#checktpye_child').on('change', function() {
        var org_type = $('#checktpye_child').val();
        $.post(url.getOrgChild, { 'org_type': org_type }, function(msg) {
            if (msg.status == 1) {
                //鉴定对象子选项
                var obj = $('#org_type')[0];
                var allChildren = msg.info;
                obj.options.length = 1;
                for (var m = 0; m < allChildren.length; m++) {
                    obj.options.add(new Option(allChildren[m].checkuporg_name, allChildren[m].checkuporg_id));
                }
            }
        });
    });
});
