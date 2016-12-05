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
        return;
        data.car_no = $('#car_no').val();

        data.traffic_type_pid = $('input:radio[name="traffic_type_pid"]:checked').val();
        data.sex = $('input:radio[name="sex"]:checked').val();
        $('.select_traffic_type_pid').each(function() {
            if ($(this).prop('disabled') == false) {
                data.traffic_type = $(this).val();
            }
        });
        if (data.id) {
            url.real = url.update;
        } else {
            url.real = url.insert;
        }
        $.post(url.real, data, function(msg) {
            if (msg.status == 1) {
                layer.msg('操作成功', {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    // $('input[name="id"]').val(msg.info.id);
                    // $('input[name="case_id"]').val(msg.info.case_id);
                    window.location.reload();
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
        window.location.reload();
    });
});
//选择当事人 读取已经添加的数据
$(function() {

    var setData = function() {
        var data = {};
        data.client_id = $('#client_id').val();
        data.case_id = $('input[name="case_id"]').val();
        var idno = $('#idno_' + data.client_id).val();
        var sex = $('#idno_' + data.client_id).data('sex');
        var gender = "";
        if (sex == 1) {
            gender = "男";
        } else {
            gender = "女";
        }
        var age = $('#idno_' + data.client_id).data('age');
        var company = $('#idno_' + data.client_id).data('company');
        $('input[name="driver_license_no"]').val(idno);
        $('input[name="sex"]').val(sex);
        $('input[name="gender"]').val(gender);
        $('input[name="age"]').val(age);
        $('input[name="company"]').val(company);
        $.post(url.getCaseExtClientData, data, function(msg) {
            if (msg.status == 1) {

                var target_data = msg.info[0];
                var setRadioChecked = function(target_data, target_colum) {
                    var target = target_data[target_colum];
                    $('.select_' + target_colum).attr('disabled', 'disabled');
                    $('.select_' + target_colum + '_' + target).attr('disabled', false);
                    //$('input:radio[name="' + target_colum + '"]').attr('checked', false);
                    //$('input:radio').attr('checked', false);
                    $('input:radio[name="' + target_colum + '"]').each(function() {

                        if ($(this).val() == target) {
                            $(this).prop('checked', true);
                        };

                    });
                }
                setRadioChecked(target_data, 'traffic_type_pid');

                $.each(msg.info, function(i, item) {
                    $.each(item, function(j, vals) {
                        if ($("[name='" + j + "']").attr('type') != 'radio') {
                            if (vals != null && vals != 0 && vals != false && vals != "0") {
                                $("[name='" + j + "']").val(vals);
                            }
                        }
                    });
                });
                if ($('input[name="sex"]').val() == 1) {
                    gender = "男";
                } else {
                    gender = "女";
                }
                $('input[name="gender"]').val(gender);
                var pid = $('.law_pid').val();
                if (pid != null) {
                    $.post(url.getLawChildren, { 'pid': pid }, function(msg) {
                        //console.log(msg);
                        if (msg.status == 1) {
                            if (msg.info[0]) {
                                var allId = msg.info;
                                var obj = $('.law_id')[0];
                                obj.options.length = 1;
                                $.each(allId, function(k, val) {
                                    for (var i = 0; i < val.length; i++) {
                                        obj.options.add(new Option(val[i].title, val[i].id));

                                    }
                                });
                                for (var q = 0; q <= obj.options.length - 1; q++) {
                                    if (obj.options[q].value == target_data['law_id']) {
                                        obj.options[q].selected = true;
                                    }
                                }
                            }
                        }
                    });

                }
                ///////设置“请选择”
                target_traffic_type_pid = $('input:radio[name="traffic_type_pid"]:checked').val();
                var target_select_traffic_type_pid = $('.select_traffic_type_pid_' + target_traffic_type_pid).val();
                $("select[name='traffic_type'] option[value='']").attr("selected", true);
                $('.select_traffic_type_pid_' + target_traffic_type_pid).val(target_select_traffic_type_pid);
                //////////////////////////////////////////////
                $('input:radio[name="traffic_type_pid"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_traffic_type_pid').attr('disabled', 'disabled');
                    $("select[name='traffic_type'] option[value='']").attr("selected", true);
                    $('.select_traffic_type_pid_' + rank).attr('disabled', false);
                    if (rank == target_data.traffic_type_pid) {
                        $("select[name='traffic_type'] option[value='" + target_data.traffic_type + "']").attr("selected", true);
                    }
                });


            } else {
                $('.post-gather').each(function() {
                    if ($(this).attr('name') != "client_id" && $(this).attr('name') != "driver_license_no") {
                        $(this).val('');
                    }

                });
                $('input:radio:checked').attr('checked', false);
                //$("input[name='traffic_type'][value=1]").prop("checked",true); 
                $('input:radio').each(function() {
                    if ($(this).val() == 1) {
                        $(this).prop('checked', true);
                    }
                });
                $("select[name='traffic_type'] option[value='']").attr("selected", true);
                $('.select_traffic_type').attr('disabled', true);
                $('.select_traffic_type_1').attr('disabled', false);

                var defualt_case_obj = JSON.parse(default_caseData);
                $('input[name="case_id"]').val(defualt_case_obj.id);
                $('input:radio[name="traffic_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_traffic_type').attr('disabled', 'disabled');
                    $("select[name='traffic_type'] option[value='']").attr("selected", true);
                    $('.select_traffic_type_pid_' + rank).attr('disabled', false);
                });

            }
            $('input').attr('disabled', 'disabled');
            $('select').each(function() {
                if ($(this).attr('name') != 'client_id') {
                    $(this).attr('disabled', 'disabled');
                }
            });
        });

    }




    $('#client_id').on('change', function() {
        setData();
    });

    //下移
    $('#select_down').on('click', function() {

        if (null == $('#client_id').val()) {
            layer.alert('请选择当事人');
            return false;
        }
        //索引的长度,从1开始 
        var optionLength = $('#client_id')[0].options.length;
        //选中的索引,从0开始 
        var optionIndex = $('#client_id').get(0).selectedIndex;
        //如果选择的不在最下面,表示可以向下 
        if (optionIndex < (optionLength - 1)) {
            var new_order = optionIndex + 1;
            var new_value = $('#client_id')[0].options[new_order].value;
            $('#client_id').val(new_value);
            //$('#client_id option:selected').insertAfter($('#client_id option:selected').next('option')); 
        }
        setData();

    });

    //上移
    $('#select_up').on('click', function() {
        if (null == $('#client_id').val()) {
            layer.alert('请选择当事人');
            return false;
        }
        //选中的索引,从0开始 
        var optionIndex = $('#client_id').get(0).selectedIndex;
        //如果选中的不在最上面,表示可以移动 
        if (optionIndex > 0) {
            //$('#client_id option:selected').insertBefore($('#client_id option:selected').prev('option')); 
            var new_order = optionIndex - 1;
            var new_value = $('#client_id')[0].options[new_order].value;
            $('#client_id').val(new_value);
        }
        setData();
    });
});

//交通方式 相关代码
$(function() {
    //禁用 交通方式 下拉选框
    $('.select_traffic_type_pid').attr('disabled', 'disabled');
    //获取选中状态的车辆变速器档位 激活选项下拉选框
    var valid_radio_traffic_type = $('input:radio[name="traffic_type_pid"]:checked').val();
    $('.select_traffic_type_pid_' + valid_radio_traffic_type).attr('disabled', false);
    $('input:radio[name="traffic_type_pid"]').on('change', function() {
        var rank = $(this).val();
        $('.select_traffic_type_pid').attr('disabled', 'disabled');
        $("select[name='traffic_type'] option[value='']").attr("selected", true);
        $('.select_traffic_type_pid_' + rank).attr('disabled', false);
    });
});

//法律条款
$(function() {
    $('.law_pid').on('click', function() {
        var pid = $(this).val();
        $.post(url.getLawChildren, { 'pid': pid }, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                if (msg.info[0]) {
                    var allId = msg.info;
                    var obj = $('.law_id')[0];
                    obj.options.length = 1;
                    $.each(allId, function(k, val) {
                        for (var i = 0; i < val.length; i++) {
                            obj.options.add(new Option(val[i].title, val[i].id));
                        }
                    });
                } else {
                    var obj = $('.law_id')[0];
                    obj.options.length = 1;
                }
            } else {
                var obj = $('.law_id')[0];
                obj.options.length = 1;
            }
        });
    });
})

$(function() {
    //下移
    $('#next').on('click', function() {

        if (null == $('#client_id').val()) {
            layer.alert('请选择当事人');
            return false;
        }
        $('#next').attr('disabled', 'disabled');
        $('#pre').attr('disabled', 'disabled');
        //索引的长度,从1开始 
        var optionLength = $('#client_id')[0].options.length;
        //选中的索引,从0开始 
        var optionIndex = $('#client_id').get(0).selectedIndex;
        //如果选择的不在最下面,表示可以向下 
        if (optionIndex < (optionLength - 1)) {
            //var new_order = optionIndex + 1;
            //var new_value = $('#car_no')[0].options[new_order].value;
            //$('#car_no').val(new_value);
            $('#client_id option:selected').insertAfter($('#client_id option:selected').next('option'));
        }
        ///////////////////////////////////////////////////////////
        var obj = $('#client_id')[0];
        var allTrain = new Array();
        for (var i = 0; i < optionLength; i++) {
            var train = new Array();
            var dataId = obj.options[i].value;
            train.push(i, dataId);
            allTrain.push(train);
        }
        //console.log(allTrain);
        setNewTrain(allTrain);



    });


    //上移
    $('#pre').on('click', function() {
        if (null == $('#client_id').val()) {
            layer.alert('请选择当事人');
            return false;
        }
        $('#next').attr('disabled', 'disabled');
        $('#pre').attr('disabled', 'disabled');
        //选中的索引,从0开始 
        var optionIndex = $('#client_id').get(0).selectedIndex;
        //如果选中的不在最上面,表示可以移动 
        if (optionIndex > 0) {
            $('#client_id option:selected').insertBefore($('#client_id option:selected').prev('option'));
            // var new_order = optionIndex - 1;
            // var new_value = $('#car_no')[0].options[new_order].value;
            // $('#car_no').val(new_value);
        }
        //////////////////////////////////////////////////////////////////////////
        var obj = $('#client_id')[0];
        var optionLength = $('#client_id')[0].options.length;
        var allTrain = new Array();
        for (var i = 0; i < optionLength; i++) {
            var train = new Array();
            var dataId = obj.options[i].value;
            train.push(i, dataId);
            allTrain.push(train);
        }
        //console.log(allTrain);
        setNewTrain(allTrain);



    });

    var setNewTrain = function(allTrain) {
        var case_id = $('input[name="case_id"]').val();
        $.post(url.setTrain, { 'train': allTrain, 'case_id': case_id }, function(msg) {

            if (msg.status != 1) {
                layer.alert('操作过快,请耐心等待');

            }
            $('#pre').attr('disabled', false);
            $('#next').attr('disabled', false);
        });

    }



});

$(function() {

    $('input').attr('disabled', 'disabled');
    $('select').each(function() {
        if ($(this).attr('name') != 'client_id') {
            $(this).attr('disabled', 'disabled');
        }
    });
});
$(function(){
     $('input[type=radio]').hide();
});