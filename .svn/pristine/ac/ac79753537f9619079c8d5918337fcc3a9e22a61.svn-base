// 定义全局变量
var submit;
var handleWin;
// 网页加载完毕
$(function() {
    /**************************************************************************************************/
    // 插入肇事车辆
    var insertAccidentCar = function(data) {
        var template = $($('#accident-car-template').html());
        if (data) {
            template.find('input[name="car_no"]').val(data.car_no);
            template.find('select[name="car_type"]').val(data.car_type);
            template.find('input[name="is_danger"]').prop('checked', data.is_danger == 1);
            template.find('input[name="danger_info"]').val(data.danger_info);
            template.find('input[name="is_bus"]').prop('checked', data.is_bus == 1);
            template.find('input[name="is_school"]').prop('checked', data.is_school == 1);
            template.find('input[name="remark"]').val(data.remark);
        }
        $('#accident-car-list').append(template);
    };
    //添加肇事车辆
    $(document).on('click', '#add-accident-car', function() {
        insertAccidentCar();
    });
    //删除肇事车辆
    $(document).on('click', '.del-accident-car', function() {
        $(this).closest('tr').remove();
    });
    // 初始化肇事车辆界面
    $.each(alarmAccidentCar, function(i, v) {
        insertAccidentCar(v);
    });
    /**************************************************************************************************/
    // 插入逃逸车辆
    var insertEscapeCar = function(data) {
        var template = $($('#escape-car-template').html());
        if (data) {
            template.find('input[name="car_no"]').val(data.car_no);
            template.find('select[name="car_type"]').val(data.car_type);
            template.find('input[name="color"]').val(data.color);
            template.find('input[name="direction"]').val(data.direction);
            template.find('input[name="other"]').val(data.other);
            template.find('input[name="body_des"]').val(data.body_des);
        }
        $('#escape-car-list').append(template);
    };
    //添加逃逸车辆
    $(document).on('click', '#add-escape-car', function() {
        insertEscapeCar();
    });
    //删除逃逸车辆
    $(document).on('click', '.del-escape-car', function() {
        $(this).closest('tr').remove();
    });
    // 初始化逃逸车辆界面
    $.each(alarmEscapeCar, function(i, v) {
        insertEscapeCar(v);
    });
    /**************************************************************************************************/
    // 插入处警信息
    var insertProcess = function(data) {
        var template = $($('#process-template').html());
        if (data) {
            template.find('input[name="process_time"]').val(data.process_time);
            template.find('input[name="content"]').val(data.content);
        }
        template.find('.form-datetime').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            clearBtn: true,
            todayBtn: true,
            autoclose: true,
            minuteStep: 1,
            minView: 0,
            language: 'zh-CN'
        });
        $('#process-list').append(template);
    };
    //添加处警信息
    $(document).on('click', '#add-process', function() {
        insertProcess();
    });
    //删除处警信息
    $(document).on('click', '.del-process', function() {
        $(this).closest('tr').remove();
    });
    // 初始化逃逸车辆界面
    $.each(alarmProcess, function(i, v) {
        insertProcess(v);
    });
    /**************************************************************************************************/
    // 创建提交对象
    submit = $.vmcSubmit();
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
    // 注册肇事车辆
    submit.reg({
        group: 'gather',
        name: 'accidentCar',
        value: alarmAccidentCar,
        get: function(name) {
            return $('#accident-car-list tr').map(function() {
                return {
                    'car_no': $(this).find('input[name="car_no"]').val(),
                    'car_type': $(this).find('select[name="car_type"]').val(),
                    'is_danger': $(this).find('input[name="is_danger"]:checked').length > 0 ? 1 : 0,
                    'danger_info': $(this).find('input[name="danger_info"]').val(),
                    'is_bus': $(this).find('input[name="is_bus"]:checked').length > 0 ? 1 : 0,
                    'is_school': $(this).find('input[name="is_school"]:checked').length > 0 ? 1 : 0,
                    'remark': $(this).find('input[name="remark"]').val()
                };
            }).get();
        },
        set: function(name, value, data) {
            $('#accident-car-list').empty();
            $.each(value, function(i, item) {
                insertAccidentCar(item);
            });
        }
    });
    // 注册逃逸车辆
    submit.reg({
        group: 'gather',
        name: 'escapeCar',
        value: alarmEscapeCar,
        get: function(name) {
            return $('#escape-car-list tr').map(function() {
                return {
                    'car_no': $(this).find('input[name="car_no"]').val(),
                    'car_type': $(this).find('select[name="car_type"]').val(),
                    'color': $(this).find('input[name="color"]').val(),
                    'direction': $(this).find('input[name="direction"]').val(),
                    'other': $(this).find('input[name="other"]').val(),
                    'body_des': $(this).find('input[name="body_des"]').val()
                };
            }).get();
        },
        set: function(name, value, data) {
            $('#escape-car-list').empty();
            $.each(value, function(i, item) {
                insertEscapeCar(item);
            });
        }
    });
    // 注册处警信息
    submit.reg({
        group: 'gather',
        name: 'process',
        value: alarmProcess,
        get: function(name) {
            return $('#process-list tr').map(function() {
                return {
                    'process_time': $(this).find('input[name="process_time"]').val(),
                    'content': $(this).find('input[name="content"]').val()
                };
            }).get();
        },
        set: function(name, value, data) {
            $('#process-list').empty();
            $.each(value, function(i, item) {
                insertProcess(item);
            });
        }
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 打开处理界面
    $('#handle').on('click', function() {
        handleWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['600px', '240px'],
            content: $('#handle-box'),
            zIndex: 10,
            title: '处理'
        });
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    /**************************************************************************************************/
    // 处理提交
    $('.handle-type').on('click', function() {
        var the = $(this);
        $.post(url.handleType, {
            'id': $('input[name="id"]').val(),
            'scene_end_time': $('input[name=scene_end_time]').val(),
            "handle_type": $(this).data('type')
        }, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    layer.close(handleWin);
                    parent.layer.close(win_index);
                }
            });
        });
    });
    $('#handle-close').on('click', function() {
        layer.close(handleWin);
    });
    /**************************************************************************************************/
    // 创建日期拾取器
    /**************************************************************************************************/
});
