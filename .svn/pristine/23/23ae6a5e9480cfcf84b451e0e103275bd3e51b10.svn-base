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
        var get_pass = 1;
        /////////////////////
        data.road_type_pid = $('input:radio[name="road_type_pid"]:checked').val();
        $('.select_road_type').each(function() {
            if ($(this).prop('disabled') == false) {
                data.road_type = $(this).val();
            }
        });
        data.intersection_type_pid = $('input:radio[name="intersection_type_pid"]:checked').val();
        $('.select_intersection_type').each(function() {
            if ($(this).prop('disabled') == false) {
                data.intersection_type = $(this).val();
            }
        });

        ////获取人的原因字符串//////////////////////////////////////////////////////////////////////////////////////////////////
        var all_reason_man = new Array();
        $('.reason_man_pid').each(function() {
            var reason_man = new Array();
            var target_pid = $(this).val();
            var rank = $(this).data('rank');
            if (target_pid) {
                $('.reason_man_cid').each(function() {
                    if ($(this).data('rank') == rank) {
                        var target_cid = $(this).val();
                        if (target_cid) {
                            reason_man.push(target_pid, target_cid);
                            all_reason_man.push(reason_man);
                        } else {
                            layer.alert('请选择人的原因子选项');
                            get_pass = 2;
                        }
                    }
                });
            }
        });
        //var all_reason_man_stri = all_reason_man.toString();
        //all_reason_man_stri = JSON.stringify(all_reason_man);
        all_reason_man_stri = all_reason_man;
        data.reason_man = all_reason_man_stri;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////获取车辆原因字符串/////////////////////////////////////////////////////////////////////////////////////////////////
        var all_reason_car = new Array();
        $('.reason_car_pid').each(function() {
            var reason_car = new Array();
            var target_pid = $(this).val();
            var rank = $(this).data('rank');
            if (target_pid) {
                $('.reason_car_cid').each(function() {
                    if ($(this).data('rank') == rank) {
                        var target_cid = $(this).val();
                        if (target_cid) {

                            $('.reason_car_id').each(function() {
                                if ($(this).data('rank') == rank) {
                                    var target_id = $(this).val();
                                    if (target_id) {

                                        reason_car.push(target_pid, target_cid, target_id);
                                        all_reason_car.push(reason_car);
                                    } else {
                                        layer.alert('请选择车辆原因子选项');
                                        get_pass = 2;
                                    }
                                }
                            });
                        } else {
                            layer.alert('请选择车辆原因子选项');
                            get_pass = 2;
                        }
                    }
                });
            }
        });
        //var all_reason_car_stri = all_reason_car.toString();
        //all_reason_car_stri = JSON.stringify(all_reason_car);
        all_reason_car_stri = all_reason_car;
        data.reason_car = all_reason_car_stri;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////获取道路及环境原因字符串////////////////////////////////////////////////////////////////////////////////////////////////
        var all_reason_road = new Array();
        $('.reason_road_pid').each(function() {
            var reason_road = new Array();
            var target_pid = $(this).val();
            var rank = $(this).data('rank');
            if (target_pid) {
                $('.reason_road_cid').each(function() {
                    if ($(this).data('rank') == rank) {
                        var target_cid = $(this).val();
                        if (target_cid) {

                            $('.reason_road_id').each(function() {
                                if ($(this).data('rank') == rank) {
                                    var target_id = $(this).val();
                                    if (target_id) {

                                        reason_road.push(target_pid, target_cid, target_id);
                                        all_reason_road.push(reason_road);
                                    } else {
                                        layer.alert('请选择道路及环境原因子选项');
                                        get_pass = 2;
                                    }
                                }
                            });
                        } else {
                            layer.alert('请选择道路及环境原因子选项');
                            get_pass = 2;
                        }
                    }
                });
            }
        });

        all_reason_road_stri = all_reason_road;
        //console.log(all_reason_road_stri);
        data.reason_road = all_reason_road_stri;
        //////检验是否有未选择的子选项
        if (get_pass == 2) {
            return false;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $.post(url.insert, data, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                layer.msg('操作成功', {
                    shade: 0.1,
                    shadeClose: true
                }, function() {
                    //window.location.reload();
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
        var default_client_id = $('#client_id').val();
        $('#client_id').val(default_client_id);
        $('#client_id').trigger('change');
    });
});


//人的原因
$(function() {
    $(document).on('click', '.reason_man_add', function() {
        var rank = $(this).data('rank');
        var new_rank = $('.reason_man').length;
        var getHtml = '';
        getHtml = $('.reason_man_box_hidden').html();
        var reg = new RegExp("default_rank", "g");
        var newHtml = getHtml.replace(reg, new_rank);
        $('.reason_man_box').append(newHtml);
    });
    $(document).on('click', '.reason_man_delete', function() {
        var rank = $(this).data('rank');
        $('.reason_man').each(function() {
            if ($(this).data('rank') == rank) {
                $(this).remove();
            }
        });
    });
    $(document).on('change', '.reason_man_pid', function() {
        var rank = $(this).data('rank');
        var pid = $(this).val();
        var code = $(this).data('code');
        var obj = '';
        $('.reason_man_cid').each(function() {
            if ($(this).data('rank') == rank) {
                obj = $(this)[0];
            }
        });
        $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                if (msg.info[0]) {
                    var allId = msg.info;
                    obj.options.length = 1;
                    $.each(allId, function(k, val) {
                        $.each(val, function(m, n) {
                            obj.options.add(new Option(n, m));
                        });
                    });
                }
            }
        });
    });
});

//车辆原因
$(function() {
    $(document).on('click', '.reason_car_add', function() {
        var rank = $(this).data('rank');
        var new_rank = $('.reason_car').length;
        var getHtml = '';
        getHtml = $('.reason_car_box_hidden').html();
        var reg = new RegExp("default_rank", "g");
        var newHtml = getHtml.replace(reg, new_rank);
        $('.reason_car_box').append(newHtml);
    });
    $(document).on('click', '.reason_car_delete', function() {
        var rank = $(this).data('rank');
        $('.reason_car').each(function() {
            if ($(this).data('rank') == rank) {
                $(this).remove();
            }
        });
    });
    $(document).on('change', '.reason_car_pid', function() {
        var rank = $(this).data('rank');
        var pid = $(this).val();
        var code = $(this).data('code');
        var obj = '';
        $('.reason_car_cid').each(function() {
            if ($(this).data('rank') == rank) {
                $(this).attr('data-pid', pid);
                obj = $(this)[0];
            }

        });
        $('.reason_car_id').each(function() {
            if ($(this).data('rank') == rank) {
                objj = $(this)[0];
            }

        });
        $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                if (msg.info[0]) {
                    var allId = msg.info;
                    obj.options.length = 1;
                    objj.options.length = 1;
                    $.each(allId, function(k, val) {
                        $.each(val, function(m, n) {
                            obj.options.add(new Option(n['name'], m));
                        });
                    });
                }
            }
        });
    });
    $(document).on('change', '.reason_car_cid', function() {
        var rank = $(this).data('rank');
        var pid = $(this).data('pid');
        var cid = $(this).val();
        var code = $(this).data('code');
        var obj = '';
        $('.reason_car_id').each(function() {
            if ($(this).data('rank') == rank) {
                obj = $(this)[0];
            }
        });
        $.post(url.getReasonTypeGrandChildren, { 'pid': pid, 'code': code, 'cid': cid }, function(msg) {
            if (msg.status == 1) {
                if (msg.info[0]) {
                    console.log(msg.info);
                    var allId = msg.info;
                    obj.options.length = 1;
                    $.each(allId, function(k, val) {
                        $.each(val, function(m, n) {
                            obj.options.add(new Option(n, m));
                        });
                    });
                }
            }
        });
    });
});


//道路环境原因原因
$(function() {
    $(document).on('click', '.reason_road_add', function() {
        var rank = $(this).data('rank');
        var new_rank = $('.reason_road').length;
        var getHtml = '';
        getHtml = $('.reason_road_box_hidden').html();
        var reg = new RegExp("default_rank", "g");
        var newHtml = getHtml.replace(reg, new_rank);
        $('.reason_road_box').append(newHtml);
    });
    $(document).on('click', '.reason_road_delete', function() {
        var rank = $(this).data('rank');
        $('.reason_road').each(function() {
            if ($(this).data('rank') == rank) {
                $(this).remove();
            }
        });
    });
    $(document).on('change', '.reason_road_pid', function() {
        var rank = $(this).data('rank');
        var pid = $(this).val();
        var code = $(this).data('code');
        var obj = '';
        $('.reason_road_cid').each(function() {
            if ($(this).data('rank') == rank) {
                $(this).attr('data-pid', pid);
                obj = $(this)[0];
            }

        });
        $('.reason_road_id').each(function() {
            if ($(this).data('rank') == rank) {
                objj = $(this)[0];
            }

        });

        $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                if (msg.info[0]) {
                    var allId = msg.info;
                    objj.options.length = 1;
                    obj.options.length = 1;
                    $.each(allId, function(k, val) {
                        $.each(val, function(m, n) {
                            obj.options.add(new Option(n['name'], m));
                        });
                    });
                }
            }
        });
    });
    $(document).on('change', '.reason_road_cid', function() {
        var rank = $(this).data('rank');
        var pid = $(this).data('pid');
        var cid = $(this).val();
        var code = $(this).data('code');
        var obj = '';
        $('.reason_road_id').each(function() {
            if ($(this).data('rank') == rank) {
                obj = $(this)[0];
            }
        });
        $.post(url.getReasonTypeGrandChildren, { 'pid': pid, 'code': code, 'cid': cid }, function(msg) {
            if (msg.status == 1) {
                if (msg.info[0]) {
                    var allId = msg.info;
                    obj.options.length = 1;
                    $.each(allId, function(k, val) {
                        $.each(val, function(m, n) {
                            obj.options.add(new Option(n, m));
                        });
                    });
                }
            }
        });
    });
});


$(function() {
    $('select[name="alarm_man_type"]').on('click', function() {

        if ($(this).val() == 9) {
            $('input[name="alarm_man_other"]').show(100);
        } else {
            $('input[name="alarm_man_other"]').val('');
            $('input[name="alarm_man_other"]').hide();

        }
    });
});


//选择人员 读取已经添加的数据
$(function() {
    var setData = function() {

        var data = {};
        data.case_id = $('input[name="case_id"]').val();
        data.client_id = $('#client_id').val();
        //$('input[name="car_no"]').val(data.car_no);

        $.post(url.getCaseExtReasonData, data, function(msg) {
            if (msg.status == 1 && msg.info) {
                $.each(msg.info, function(i, item) {
                    $.each(item, function(j, vals) {
                        if (vals != "" && $("[name='" + j + "']").attr('type') != 'radio') {
                            $("[name='" + j + "']").val(vals);
                        }

                        //读取人的原因数据///////////////////////////////////////////////////////////////////////////////
                        if (j == 'reason_man') {
                            if (vals) {
                                //格式化原有标签
                                $('.reason_man_box').html('');
                                var reason_man_html = $('.reason_man_box_hidden').html();
                                for (var i = 0; i < vals.length; i++) {
                                    var reg = new RegExp("default_rank", "g");
                                    var newHtml = reason_man_html.replace(reg, i + 1);
                                    $('.reason_man_box').append(newHtml);
                                    $('.reason_man_pid').each(function() {
                                        if ($(this).data('rank') == i + 1) {
                                            $(this).val(vals[i][0]);
                                            var pid = vals[i][0];
                                            var cid = vals[i][1];
                                            var code = $(this).data('code');
                                            $('.reason_man_cid').each(function() {
                                                if ($(this).data('rank') == i + 1) {
                                                    var the = $(this);
                                                    var obj = $(this)[0];
                                                    $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
                                                        //console.log(msg);
                                                        if (msg.status == 1) {
                                                            if (msg.info[0]) {
                                                                var allId = msg.info;
                                                                obj.options.length = 1;
                                                                $.each(allId, function(k, val) {
                                                                    $.each(val, function(m, n) {
                                                                        obj.options.add(new Option(n, m));
                                                                    });
                                                                });
                                                                for (var q = 0; q <= obj.options.length - 1; q++) {
                                                                    if (obj.options[q].value == cid) {
                                                                        obj.options[q].selected = true;
                                                                    }
                                                                }

                                                            }
                                                        }
                                                    });
                                                }

                                            });
                                        }
                                    });
                                }

                            } else {
                                //格式化原有标签
                                $('.reason_man_box').html('');
                                var reason_man_html = $('.reason_man_box_hidden').html();
                                var reg = new RegExp("default_rank", "g");
                                var newHtml = reason_man_html.replace(reg, 1);
                                $('.reason_man_box').append(newHtml);

                            }
                            //第一条取消删除功能
                            $('.reason_man_delete').each(function() {
                                if ($(this).data('rank') == 1) {
                                    $(this).attr('reason_man_delete');
                                    $(this).attr('disabled', 'disabled');

                                }
                            });
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////

                        //读取车辆原因数据///////////////////////////////////////////////////////////////////////////////
                        if (j == 'reason_car') {
                            if (vals) {
                                //格式化原有标签
                                $('.reason_car_box').html('');
                                var reason_car_html = $('.reason_car_box_hidden').html();
                                for (var len = 0; len < vals.length; len++) {

                                    var reg = new RegExp("default_rank", "g");
                                    var newHtml = reason_car_html.replace(reg, len + 1);
                                    $('.reason_car_box').append(newHtml);
                                    $('.reason_car_pid').each(function() {
                                        if ($(this).data('rank') == len + 1) {

                                            $(this).val(vals[len][0]);
                                            var pid = vals[len][0];
                                            var cid = vals[len][1];
                                            var id = vals[len][2];
                                            var code = $(this).data('code');
                                            $('.reason_car_cid').each(function() {
                                                if ($(this).data('rank') == len + 1) {
                                                    var this_rank = $(this).data('rank');
                                                    $(this).attr('data-pid', vals[len][0]);
                                                    var the = $(this);
                                                    var obj = $(this)[0];
                                                    $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
                                                        //console.log(msg);
                                                        if (msg.status == 1) {
                                                            if (msg.info[0]) {
                                                                var allId = msg.info;
                                                                obj.options.length = 1;
                                                                $.each(allId, function(k, val) {
                                                                    $.each(val, function(m, n) {
                                                                        obj.options.add(new Option(n['name'], m));
                                                                    });
                                                                });
                                                                for (var q = 0; q <= obj.options.length - 1; q++) {
                                                                    if (obj.options[q].value == cid) {
                                                                        obj.options[q].selected = true;
                                                                        $('.reason_car_id').each(function() {

                                                                            if ($(this).data('rank') == this_rank) {
                                                                                var objj = $(this)[0];
                                                                                $.post(url.getReasonTypeGrandChildren, { 'pid': pid, 'code': code, 'cid': cid }, function(msg) {
                                                                                    //console.log(msg.info);
                                                                                    if (msg.status == 1) {
                                                                                        if (msg.info[0]) {

                                                                                            var allId = msg.info;
                                                                                            objj.options.length = 1;
                                                                                            $.each(allId, function(k, val) {
                                                                                                $.each(val, function(m, n) {
                                                                                                    objj.options.add(new Option(n, m));
                                                                                                });
                                                                                            });
                                                                                            for (var q = 0; q <= objj.options.length - 1; q++) {
                                                                                                if (objj.options[q].value == id) {
                                                                                                    objj.options[q].selected = true;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                });

                                                                            }
                                                                        });


                                                                    }
                                                                }

                                                            }
                                                        }
                                                    });
                                                }

                                            });
                                        }
                                    });
                                }

                            } else {
                                //格式化原有标签
                                $('.reason_car_box').html('');
                                var reason_car_html = $('.reason_car_box_hidden').html();
                                var reg = new RegExp("default_rank", "g");
                                var newHtml = reason_car_html.replace(reg, 1);
                                $('.reason_car_box').append(newHtml);


                            }
                            //第一条取消删除功能
                            $('.reason_car_delete').each(function() {
                                if ($(this).data('rank') == 1) {
                                    $(this).removeClass('reason_car_delete');
                                    $(this).attr('disabled', 'disabled');
                                }
                            });
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                        //读取道路及环境原因数据///////////////////////////////////////////////////////////////////////////////
                        if (j == 'reason_road') {
                            if (vals) {
                                //格式化原有标签
                                $('.reason_road_box').html('');
                                var reason_road_html = $('.reason_road_box_hidden').html();
                                for (var len = 0; len < vals.length; len++) {

                                    var reg = new RegExp("default_rank", "g");
                                    var newHtml = reason_road_html.replace(reg, len + 1);
                                    $('.reason_road_box').append(newHtml);
                                    $('.reason_road_pid').each(function() {
                                        if ($(this).data('rank') == len + 1) {

                                            $(this).val(vals[len][0]);
                                            var pid = vals[len][0];
                                            var cid = vals[len][1];
                                            var id = vals[len][2];
                                            var code = $(this).data('code');
                                            $('.reason_road_cid').each(function() {
                                                if ($(this).data('rank') == len + 1) {
                                                    var this_rank = $(this).data('rank');
                                                    $(this).attr('data-pid', vals[len][0]);
                                                    var the = $(this);
                                                    var obj = $(this)[0];
                                                    $.post(url.getReasonTypeChildren, { 'pid': pid, 'code': code }, function(msg) {
                                                        //console.log(msg);
                                                        if (msg.status == 1) {
                                                            if (msg.info[0]) {
                                                                var allId = msg.info;
                                                                obj.options.length = 1;
                                                                $.each(allId, function(k, val) {
                                                                    $.each(val, function(m, n) {
                                                                        obj.options.add(new Option(n['name'], m));
                                                                    });
                                                                });
                                                                for (var q = 0; q <= obj.options.length - 1; q++) {
                                                                    if (obj.options[q].value == cid) {
                                                                        obj.options[q].selected = true;
                                                                        $('.reason_road_id').each(function() {

                                                                            if ($(this).data('rank') == this_rank) {
                                                                                var objj = $(this)[0];
                                                                                $.post(url.getReasonTypeGrandChildren, { 'pid': pid, 'code': code, 'cid': cid }, function(msg) {
                                                                                    //console.log(msg.info);
                                                                                    if (msg.status == 1) {
                                                                                        if (msg.info[0]) {

                                                                                            var allId = msg.info;
                                                                                            objj.options.length = 1;
                                                                                            $.each(allId, function(k, val) {
                                                                                                $.each(val, function(m, n) {
                                                                                                    objj.options.add(new Option(n, m));
                                                                                                });
                                                                                            });
                                                                                            for (var q = 0; q <= objj.options.length - 1; q++) {
                                                                                                if (objj.options[q].value == id) {
                                                                                                    objj.options[q].selected = true;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                });

                                                                            }
                                                                        });


                                                                    }
                                                                }

                                                            }
                                                        }
                                                    });
                                                }

                                            });
                                        }
                                    });
                                }

                            } else {
                                //格式化原有标签
                                $('.reason_road_box').html('');
                                var reason_road_html = $('.reason_road_box_hidden').html();
                                var reg = new RegExp("default_rank", "g");
                                var newHtml = reason_road_html.replace(reg, 1);
                                $('.reason_road_box').append(newHtml);


                            }
                            //第一条取消删除功能
                            $('.reason_road_delete').each(function() {
                                if ($(this).data('rank') == 1) {
                                    $(this).removeClass('reason_road_delete');
                                    $(this).attr('disabled', 'disabled');
                                }
                            });
                            //清空id
                            $('input[name="id"]').val('');
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////





                    });

                });
                if ($("[name='alarm_man_type']").val() == 9) {
                    $("[name='alarm_man_other']").show(100);
                } else {
                    $("[name='alarm_man_other']").val('');
                    $("[name='alarm_man_other']").hide(100);

                }
            }
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
$(function(){
     $('input[type=radio]').hide();
});