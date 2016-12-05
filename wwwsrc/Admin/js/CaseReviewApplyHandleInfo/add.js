// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    var getApplyer = function() {
        $.get(url.getApplyer, {
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            var html = '<option value="" data-client="">请选择</option>';
            if (msg.status == 1) {
                $.each(msg.info, function(i, item) {
                    html += '<option value="' + item.case_client_relater_id + '"';
                    html += ' data-client="' + item.case_client_id + '"';
                    if (item.disabled == 1) {
                        html += ' disabled="disabled" class="text-primary"';
                    }
                    html += '>' + item.name;
                    html += '</option>'
                });
            }
            $('select[name="case_client_relater_id"]').html(html);
        });
    };
    getApplyer();
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
    // 注册其他字段
    $('.post-other').each(function() {
        var the = $(this);
        submit.reg({
            group: 'other',
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
        name: 'case_client_relater_id',
        get: function(name) {
            return $('select[name="case_client_relater_id"]').val() || '';
        },
        set: function(name, value, data) {}
    });
    submit.reg({
        group: 'gather',
        name: 'case_client_id',
        get: function(name) {
            return $('select[name="case_client_relater_id"] option:enabled:selected').data('client') || '';
        },
        set: function(name, value, data) {
            $('select[name="case_client_relater_id"] option:enabled').each(function() {
                var the = $(this);
                the.prop('selected', the.attr('value') == data['case_client_relater_id'] && the.data('client') == value);
            });
        }
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增', '关闭窗口']
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
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    $('.js-end-refresh').data('end', function() {
        getApplyer();
    });
    $('select[name="case_client_relater_id"]').on('change', function() {
        if ($(this).val() == '') {
            $('select[name="sex"]').val('').prop('disabled', false);
            $('input[name="idno"]').val('').prop('disabled', false);
            $('select[name="traffic_type"]').val('').prop('disabled', false);
            $('input[name="relation"]').val('').prop('disabled', false);
            return false;
        }
        var the = $(this).find('option:selected');
        $.post(url.getApplyerInfo, {
            'case_client_id': the.data('client'),
            'case_client_relater_id': the.attr('value')
        }, function(msg) {
            if (msg.status == 1) {
                var info = msg.info;
                console.log(info);
                $('select[name="sex"]').val(info.sex).prop('disabled', true);
                $('input[name="idno"]').val(info.idno).prop('disabled', true);
                $('select[name="traffic_type"]').val(info.traffic_type).prop('disabled', info.traffic_type);
                $('input[name="relation"]').val(info.relation).prop('disabled', true);
            } else {
                layer.alert(msg.info);
            }
        });
    });
});
