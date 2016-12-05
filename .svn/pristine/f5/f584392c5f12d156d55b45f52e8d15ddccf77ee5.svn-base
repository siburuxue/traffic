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
        var cookie_data = {};
        $('.cookie-data').each(function() {
            cookie_data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.setCookie, cookie_data, function(msgs) {
            if (msgs.status == 1) {
                $.post(url.update, data, function(msg) {
                    if (msg.status == 1) {
                        layer.msg(msg.info, {
                            shade: 0.1,
                            shadeClose: true
                        }, function(index) {
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
            } else {
                layer.alert('更新失败');
                return;
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
    $('#error-notice').on('click', function() {
        layer.alert('暂不能操作');
    });
    $('.js-end-refresh').data('end', function() {
        window.location.reload();
    });

});


$(function() {

    // $('#reset').on('click', function() {
    //     parent.layer.close(win_index);
    //     //window.location.reload();
    // });
    $('.re-check-refresh').on('click', function() {
        //alert();
        //layer.close(index);
        history.back();
    });


    //提请重新检验鉴定 
    $('#re-checkup').on('click', function() {
        var the = $(this);
        var id = the.data('id');
        approvalWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#approval-box'),
            zIndex: 1,
            title: false,
            end: function() {
                layer.close(approvalWin);
                //window.location.reload();
            }
        });
    });
    $('.reload-window').on('click', function() {
        layer.close(approvalWin);
        //window.location.reload();
    });



    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked', false);
    });


    //选择审核人
    $('#approval-submit-2').on('click', function() {
        var the = $(this);
        var id = the.data('id');
        approvalWin2 = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#approval-box-2'),
            zIndex: 1,
            title: false,
            end: function() {
                layer.close(approvalWin2);
                //window.location.reload();
            }
        });
    });

    $('.reload-window-2').on('click', function() {
        layer.close(approvalWin2);
        //window.location.reload();
    });


    //提审提交按钮
    $('#approval-submit').on('click', function() {
        var to_user_id = $('input[name=to_user_id]:checked').val();
        var data = {
            'to_user_id': to_user_id,
            'check_user_id': to_user_id
        };
        $('.post-gather-top').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
         data.from_user_name = $('select[name=from_user_id] option:selected').text();
       
        // console.log(data);
        //  return false;

        $.post(url.againUpdate, data, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                layer.msg(msg.info, { shade: 0.1, shadeClose: true }, function(index) {
                    window.location.href = msg.url;
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }

        }, 'json');
    });

});



$(function() {
        //console.log(entrust_cookie_data);
        var cookieData = JSON.parse(entrust_cookie_data);
        $.each(cookieData, function(i, item) {
            $.each(item, function(j, vals) {
                //$("input[name="+j+"]").val(val);
                $("[name='" + j + "']").val(vals);
            });
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
                            //allChildren[i].case_client_car_no = "未填写";
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

// 鉴定对象联动 自动加载
$(function() {
    //获取部门id
    var type_id = $('#car-people-other').children('option:selected').val();
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
                        //allChildren[i].case_client_car_no = "未填写";
                        allChildren[i].case_client_car_no = "";
                    }
                    if (allChildren[i].case_client_car_no != "") {
                        obj.options.add(new Option(allChildren[i].case_client_car_no, allChildren[i].case_client_id));
                    }
                }
            }
            //自动选择当前人员 或车辆
            var showLabel = document.getElementById("car-people-other-child");
            for (var i = 0; i < showLabel.length; i++) {
                if (showLabel[i].value == $('#target_case_client_id').val()) {
                    //showLabel[i].selected = "selected";
                    $("select[name='target_case_client_id'] option[value=" + $('#target_case_client_id').val() + "]").attr("selected", true);
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
            //自动选择当前鉴定类型
            var showLabelAgain = document.getElementById("checktpye_child");
            for (var i = 0; i < showLabelAgain.length; i++) {
                if (showLabelAgain[i].value == $('#checkup_org_item_id').val()) {
                    //showLabelAgain[i].selected = "selected";
                    $("select[name='checkup_org_item_id'] option[value=" + $('#checkup_org_item_id').val() + "]").attr("selected", true);
                }
            }
            // 鉴定机构名称联动 自动加载
            var org_type = $("select[name='checkup_org_item_id']").val();
            $.post(url.getOrgChild, { 'org_type': org_type }, function(msg) {
                if (msg.status == 1) {
                    //鉴定对象子选项
                    var obj = $('#org_type')[0];
                    var allChildren = msg.info;
                    obj.options.length = 1;
                    for (var m = 0; m < allChildren.length; m++) {
                        obj.options.add(new Option(allChildren[m].checkuporg_name, allChildren[m].checkuporg_id));
                    }
                    //自动选择当前鉴定机构名称
                    var showLabelOrg_type = document.getElementById("org_type");
                    for (var i = 0; i < showLabelOrg_type.length; i++) {
                        if (showLabelOrg_type[i].value == $('#checkup_org_id').val()) {
                            //showLabelAgain[i].selected = "selected";
                            $("select[name='checkup_org_id'] option[value=" + $('#checkup_org_id').val() + "]").attr("selected", true);
                        }
                    }
                }
            });
        }
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
//加载并处理entrust表单部分的数据
$(function() {

    $('#target_client_id_select').on('change', function() {
        var target_client_id = $(this).children('option:selected').val();
        if (target_client_id == "") {
            layer.alert('请重新选择!');
            $('#target_name_hidden').val('');
            $('#target_sex').val('');
            $('#sex_read').val('');
            $('#target_age').val('');
            $('#target_tel').val('');
            $('#target_address').val('');
            return false;
        }

        var target_name_text = $(this).children('option:selected').text();
        $('#target_name').val(target_name_text);
        $.post(url.getClientInfo, { 'id': target_client_id }, function(msg) {
            if (msg.status == 1) {
                if (msg.info != "" && msg.info != null) {
                    $('#target_name_hidden').val(msg.info.name);
                    //性别
                    $('#target_sex').val(msg.info.sex);
                    if (msg.info.sex == '0') {
                        $('#sex_read').val('女');
                    }
                    if (msg.info.sex == '1') {
                        $('#sex_read').val('男');
                    }
                    //年龄
                    $('#target_age').val(msg.info.age);
                    //电话
                    $('#target_tel').val(msg.info.tel);
                    //现住址
                    $('#target_address').val(msg.info.address);
                }
            }
        });
    });
    $('#entrust-submit').on('click', function() {
        /*判断检验鉴定信息是否有更改*/
        var verify_pass = 0;
        if ($('#car-people-other-default').val() != $('#car-people-other').val()) {
            verify_pass = 1;
        }
        if ($('#car-people-other').val() != 3) {
            if ($('#car-people-other-child-default').val() != $('#car-people-other-child').val()) {
                verify_pass = 1;
            }

        } else {

            if ($('#type_other-default').val() != $('#type_other').val()) {
                verify_pass = 1;
            }

        }

        if ($('#checktpye_child-default').val() != $('#checktpye_child').val()) {
            verify_pass = 1;
        }
        if ($('#org_type-default').val() != $('#org_type').val()) {
            verify_pass = 1;
        }
        if ($('#time-one-default').val() != $('#time-one').val()) {
            verify_pass = 1;
        }

        if (verify_pass == 1) {
            layer.confirm('检验鉴定信息有更改，是否继续提交', {
                btn: ['是', '否'],
                closeBtn: 0
            }, function(index) {
                var data = {};
                $('.post-gather-entrust').each(function() {
                    data[$(this).attr('name')] = $(this).val();
                });
                //console.log(data);
                $.post(url.entrustInsert, data, function(msg) {
                    if (msg.status == 1) {
                        layer.msg(msg.info, {
                            shade: 0.1,
                            shadeClose: true
                        }, function(index) {
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
            }, function(index) {
                layer.alert('检验鉴定信息有更新，请先保存检验鉴定信息');
                return;
            });
            return;
        }
        var data = {};
        $('.post-gather-entrust').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        //console.log(data);
        $.post(url.entrustInsert, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
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
    });
});

//页面加载自动清除多余数据  被鉴定人情况
$(function() {

    var obj_type = $('.checkup_org_item_pid').val();
    if (obj_type != 1) {
        $('.jimmy-cancel-entrust').val('');
    }
});
