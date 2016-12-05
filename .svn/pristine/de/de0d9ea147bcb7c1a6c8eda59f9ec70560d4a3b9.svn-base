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

        data.gears_type = $('input:radio[name="gears_type"]:checked').val();
        $('.select_gears_type').each(function() {
            if ($(this).prop('disabled') == false) {
                data.gears = $(this).val();
            }
        });
        data.vehicle_shape_type = $('input:radio[name="vehicle_shape_type"]:checked').val();

        $('.select_vehicle_shape_type').each(function() {
            if ($(this).prop('disabled') == false) {
                data.vehicle_shape = $(this).val();
            }
        });
        data.use_property_type = $('input:radio[name="use_property_type"]:checked').val();
        $('.select_use_property_type').each(function() {
            if ($(this).prop('disabled') == false) {
                data.use_property = $(this).val();
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
//选择车牌号 读取已经添加的数据
$(function() {


    var setData = function() {
        var data = {};
        data.case_id = $('input[name="case_id"]').val();
        data.car_no = $('#car_no').val();
        $('input[name="car_no"]').val(data.car_no);
        $.post(url.getCaseExtCarData, data, function(msg) {
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
                setRadioChecked(target_data, 'gears_type');
                setRadioChecked(target_data, 'vehicle_shape_type');
                setRadioChecked(target_data, 'use_property_type');
                $.each(msg.info, function(i, item) {
                    $.each(item, function(j, vals) {
                        if ($("[name='" + j + "']").attr('type') != 'radio') {
                            $("[name='" + j + "']").val(vals);
                        }
                    });
                });
                $('input:radio[name="gears_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_gears_type').attr('disabled', 'disabled');
                    $("select[name='gears'] option[value='']").attr("selected", true);
                    $('.select_gears_type_' + rank).attr('disabled', false);
                    if (rank == target_data.gears_type) {
                        $("select[name='gears'] option[value='" + target_data.gears + "']").attr("selected", true);
                    }
                });
                $('input:radio[name="vehicle_shape_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_vehicle_shape_type').attr('disabled', 'disabled');
                    $("select[name='vehicle_shape'] option[value='']").attr("selected", true);
                    $('.select_vehicle_shape_type_' + rank).attr('disabled', false);
                    if (rank == target_data.vehicle_shape_type) {
                        $("select[name='vehicle_shape'] option[value='" + target_data.vehicle_shape + "']").attr("selected", true);

                    }

                });

                $('input:radio[name="use_property_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_use_property_type').attr('disabled', 'disabled');
                    $("select[name='use_property'] option[value='']").attr("selected", true);
                    $('.select_use_property_type_' + rank).attr('disabled', false);
                    if (rank == target_data.use_property) {
                        $("select[name='use_property'] option[value='" + target_data.use_property + "']").attr("selected", true);

                    }
                });

            } else {
                $('.post-gather').each(function() {
                    if ($(this).attr('name') != "car_no") {
                        $(this).val('');
                    }

                });
                //$('input:radio').attr('checked', false);
                $('input:radio:checked').attr('checked', false);
                //$("input[name='gears_type'][value=1]").prop("checked",true); 
                $('input:radio').each(function() {
                    if ($(this).val() == 1) {
                        $(this).prop('checked', true);
                    }
                });
                $("select[name='gears'] option[value='']").attr("selected", true);
                $('.select_gears_type').attr('disabled', true);
                $('.select_gears_type_1').attr('disabled', false);
                $("select[name='vehicle_shape'] option[value='']").attr("selected", true);
                $('.select_vehicle_shape_type').attr('disabled', true);
                $('.select_vehicle_shape_type_1').attr('disabled', false);
                $("select[name='use_property'] option[value='']").attr("selected", true);
                $('.select_use_property_type').attr('disabled', true);
                $('.select_use_property_type_1').attr('disabled', false);

                var defualt_case_obj = JSON.parse(default_caseData);
                $('input[name="case_id"]').val(defualt_case_obj.id);
                $('input:radio[name="gears_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_gears_type').attr('disabled', 'disabled');
                    $("select[name='gears'] option[value='']").attr("selected", true);
                    $('.select_gears_type_' + rank).attr('disabled', false);
                });
                $('input:radio[name="vehicle_shape_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_vehicle_shape_type').attr('disabled', 'disabled');
                    $("select[name='vehicle_shape'] option[value='']").attr("selected", true);
                    $('.select_vehicle_shape_type_' + rank).attr('disabled', false);
                });

                $('input:radio[name="use_property_type"]').on('change', function() {
                    var rank = $(this).val();
                    $('.select_use_property_type').attr('disabled', 'disabled');
                    $("select[name='use_property'] option[value='']").attr("selected", true);
                    $('.select_use_property_type_' + rank).attr('disabled', false);
                });


            }
            $('input').attr('disabled', 'disabled');
            $('select').each(function() {
                if ($(this).attr('name') != 'car_no') {
                    $(this).attr('disabled', 'disabled');
                }
            });
        });
    }

    $('#car_no').on('change', function() {

        setData();
    });


    //下移
    $('#select_down').on('click', function() {

        if (null == $('#car_no').val()) {
            alert('请选择一项');
            return false;
        }
        //索引的长度,从1开始 
        var optionLength = $('#car_no')[0].options.length;
        //选中的索引,从0开始 
        var optionIndex = $('#car_no').get(0).selectedIndex;
        //如果选择的不在最下面,表示可以向下 
        if (optionIndex < (optionLength - 1)) {
            var new_order = optionIndex + 1;
            var new_value = $('#car_no')[0].options[new_order].value;
            $('#car_no').val(new_value);
            //$('#client_id option:selected').insertAfter($('#client_id option:selected').next('option')); 
        }
        setData();

    });


    //上移
    $('#select_up').on('click', function() {
        if (null == $('#car_no').val()) {
            alert('请选择一项');
            return false;
        }
        //选中的索引,从0开始 
        var optionIndex = $('#car_no').get(0).selectedIndex;
        //如果选中的不在最上面,表示可以移动 
        if (optionIndex > 0) {
            //$('#client_id option:selected').insertBefore($('#client_id option:selected').prev('option')); 
            var new_order = optionIndex - 1;
            var new_value = $('#car_no')[0].options[new_order].value;
            $('#car_no').val(new_value);
        }
        setData();
    });

});

//车辆变速器档位 相关代码
$(function() {
    //禁用 车辆变速器档位 下拉选框
    $('.select_gears_type').attr('disabled', 'disabled');
    //获取选中状态的车辆变速器档位 激活选项下拉选框
    var valid_radio_gears_type = $('input:radio[name="gears_type"]:checked').val();
    $('.select_gears_type_' + valid_radio_gears_type).attr('disabled', false);
    $('input:radio[name="gears_type"]').on('change', function() {
        var rank = $(this).val();
        $('.select_gears_type').attr('disabled', 'disabled');
        $("select[name='gears'] option[value='']").attr("selected", true);
        $('.select_gears_type_' + rank).attr('disabled', false);
    });
});
//车辆形状 相关代码
$(function() {
    //禁用 车辆形状 下拉选框
    $('.select_vehicle_shape_type').attr('disabled', 'disabled');
    //获取选中状态的车辆形状 激活选项下拉选框
    var valid_radio_vehicle_shape_type = $('input:radio[name="vehicle_shape_type"]:checked').val();
    $('.select_vehicle_shape_type_' + valid_radio_vehicle_shape_type).attr('disabled', false);
    $('input:radio[name="vehicle_shape_type"]').on('change', function() {
        var rank = $(this).val();
        $('.select_vehicle_shape_type').attr('disabled', 'disabled');
        $("select[name='vehicle_shape'] option[value='']").attr("selected", true);
        $('.select_vehicle_shape_type_' + rank).attr('disabled', false);
    });
});

//车辆使用性质 相关代码
$(function() {
    //禁用 车辆使用性质 下拉选框
    $('.select_use_property_type').attr('disabled', 'disabled');
    //获取选中状态的车辆使用性质 激活选项下拉选框
    var valid_radio_use_property_type = $('input:radio[name="use_property_type"]:checked').val();
    $('.select_use_property_type_' + valid_radio_use_property_type).attr('disabled', false);
    $('input:radio[name="use_property_type"]').on('change', function() {
        var rank = $(this).val();
        $('.select_use_property_type').attr('disabled', 'disabled');
        $("select[name='use_property'] option[value='']").attr("selected", true);
        $('.select_use_property_type_' + rank).attr('disabled', false);
    });
});
$(function() {

    $('input').attr('disabled', 'disabled');
    $('select').each(function() {
        if ($(this).attr('name') != 'car_no') {
            $(this).attr('disabled', 'disabled');
        }
    });
});
$(function(){
     $('input[type=radio]').hide();
});