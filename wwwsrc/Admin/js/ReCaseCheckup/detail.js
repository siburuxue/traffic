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
        //console.log(data);
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['关闭窗口'],
                    closeBtn: 0
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
// 时间拾取
$(function() {
    // 创建日期拾取器


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
            return false;
        }
        var target_name_text = $(this).children('option:selected').text();
        $('#target_name').val(target_name_text);
        $.post(url.getClientInfo, { 'id': target_client_id }, function(msg) {
            if (msg.status == 1) {
                if (msg.info != "" && msg.info != null) {
                    //姓名
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

        var data = {};
        $('.post-gather-entrust').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        //console.log(data);
        $.post(url.entrustInsert, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['关闭窗口'],
                    closeBtn: 0
                }, function(index) {
                    layer.close(index);
                    //window.location.href = msg.url;
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
$(function() {
    $('#set-submit-finish').on('click', function() {
        var data = {};
        $('.post-gather-edit').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.entrustSetSubmitFinish, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['关闭窗口'],
                    closeBtn: 0
                }, function(index) {
                    //layer.close(index);
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

// 图片加载
$(function() {
    var photoTableInit = function() {
            var case_id = $('#case_id').val();
            var id = $('#case_checkup_id').val();
            var cate = $('#photo_cate').val();
            $('#photoTable').parent().load(url.photoList, { case_id: case_id, id: id, cate: cate });
        }
        //上传图片
    $('#upload').data('end', function() {
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function() {

        var idss = $('#photoTable input:checked').map(function() {
            return $(this).val();
        }).get();
        if (idss == "" || idss == null) {
            layer.alert('未选择任何图片，不能操作！');
            return;
        }


        layer.confirm('是否确定删除图片?', {
            btn: ['是', '否'],
            closeBtn: 0
        }, function(index) {
            var ids = $('#photoTable input:checked').map(function() {
                return $(this).val();
            }).get();
            var case_id = $('#case_id').val();
            $.post(url.delete, { ids: ids, case_id: case_id }, function(msg) {
                layer.msg(msg.info, { shade: 0.1, shadeClose: true }, function(index) {});
                if (msg.status == 1) {
                    $('#photoTable input:checked').parents('.col-xs-2').remove();
                }
            });
        }, function(index) {
            layer.close(index);
        });

    });


});

//鉴定报告数据提交
$(function() {
    $('#report-submit').on('click', function() {
        var data = {};
        $('.post-report').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.reportInsert, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['关闭窗口'],
                    closeBtn: 0
                }, function(index) {
                    //layer.close(index);
                    window.location.reload();
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


// 短信告知
$(function() {
    $('#approval').on('click', function() {
        var the = $(this);
        var id = the.data('id');
        approvalWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['600px', '400px'],
            content: $('#approval-box'),
            zIndex: 1,
            title: '审核人确认',
            end: function() {
                layer.close(index);
                //window.location.reload();
            }
        });
    });
    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked', false);
    });
    //提审提交按钮
    $('#approval-submit').on('click', function() {
        var data = {};
        data.case_client_name = "";
        $('input:checkbox[name="case_client_name"]:checked').each(function() {
            if ($(this).attr('value')) {
                data.case_client_name += $(this).attr('value');
            }

        });

        $('.post-gather-msg').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.msgInsert, data, function(data) {
            layer.alert(data.info, function(index) {
                layer.close(index);
                //parent.layer.close(win_index);
            });
        }, 'json');
    });

});

//检验鉴定结果列表 点击列表单项 读取鉴定结果
$(function() {
    $('.jimmy_report_result').on('click', function() {
        var report_id = $(this).data('reportid');
        $.post(url.getReportDetail, { 'id': report_id }, function(data) {
            if (data.status == 1) {
                $('#read_code').val(data.info.code);
                $('#read_finish_time').val(data.info.finish_time_date);
                $('#read_result').val(data.info.result);
            }
        }, 'json');
    });
});
//页面加载自动清除多余数据  被鉴定人情况
$(function() {

    var obj_type = $('.target_name').val();
    if (obj_type != '' && obj_type != null) {
        $('.jimmy-cancel-entrust').val('');
    }
});
