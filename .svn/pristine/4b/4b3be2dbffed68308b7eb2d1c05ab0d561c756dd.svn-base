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
                    btn: ['继续新增', '关闭窗口'],
                    closeBtn:0
                }, function(index) {
                    layer.close(index);
                    submit.reset();
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
    //笔录类型
    submit.reg({
        group: 'gather',
        name: 'record_type',
        get: function(name) {
            return $('input[type=radio]:checked').val();
        },
        set: function(name, value, data) {}
    });
    //被询问人姓名
    submit.reg({
        group: 'gather',
        name: 'name',
        get: function(name) {
            return $('select[name=name] option:selected').text();
        },
        set: function(name, value, data) {}
    });
    submit.reg({
        group: 'gather',
        name: 'is_client',
        get: function(name) {
            return $('select[name=name]').val().indexOf('client') != -1 ? 1 : 0;
        },
        set: function(name, value, data) {}
    });
    submit.reg({
        group: 'gather',
        name: 'user_type',
        get: function(name) {
            if($('#name').val().indexOf('clientRelater') != -1){
                return 1;
            }else if($('#name').val().indexOf('client') != -1){
                return 0;
            }else{
                return 2;
            }
        }
        ,
        set: function(name, value, data) {}
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    // 创建日期拾取器
    /**************************************************************************************************/
    $('input[type=radio]').on('change', function() {
        switch ($(this).val()) {
            case "0":
                $('.change-span').text('询问');
                break;
            case "1":
                $('.change-span').text('讯问');
                break;
            case "2":
                $('.change-span').text('谈话');
                break;
        }
    });
    $('select[name=name]').on('change', function() {
        var id = $(this).val();
        if (id != "") {
            $('input[name=witness_name]').val('').prop('disabled', true);
            $.post(url.info, { id: id, case_id: case_id }, function(data) {
                $('select[name=sex]').val(data['sex']);
                $('select[name=id_type]').val(data['id_type']);
                $('input[name=idno]').val(data['idno']);
                $('input[name=tel]').val(data['tel']);
                $('input[name=address]').val(data['address']);
                $('input[name=home_place]').val(data['home_place']);
                $('input[name=date_of_birth]').val(data['brithday']);
                if (id.indexOf('witness') == -1) {
                    $('.info').prop('disabled', true);
                    if (id.indexOf('clientRelater') != -1) {
                        $('select[name=id_type]').val(1);
                    }
                    $('input[name=home_place]').prop('disabled', false);
                    $('input[name=date_of_birth]').prop('disabled', false);
                } else {
                    $('.info').prop('disabled', false);
                }
            }, 'json');
        } else {
            $('input[name=witness_name]').val('').prop('disabled', false);
            $('.info').prop('disabled', false).val('');
        }
    });
});
