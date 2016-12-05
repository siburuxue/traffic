// 定义全局变量
var submit;
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
    /**************************************************************************************************/
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status === 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    window.location.href = msg.url;
                });
            } else {
                layer.alert(msg.info);
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
