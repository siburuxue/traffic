// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    /**************************************************************************************************/
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
    submit.reg({
        group: 'gather',
        name: 'bloodList',
        value: json,
        get: function(name) {
            return $('#info4 tr').map(function() {
                return {
                    'used_time': $(this).find('input[name=used_time]').val(),
                    'id': $(this).find('input[type=hidden]').val(),
                    'case_client_id': $('#id').val(),
                    'code': $(this).find('input[name=code1]').val(),
                    'case_id': $('#case_id').val()
                };
            }).get();
        },
        set: function(name, value, data) {}
    });

    function init() {
        $('#info4 tr').remove();
        json.forEach(function(v, k) {
            $('#info4').append($('#template4').html());
            $('#info4 tr:eq(' + k + ')').find('td:eq(0)').text(v['i']);
            $('#info4 tr:eq(' + k + ')').find('input[name=used_time]').val(v['used_time']);
            $('#info4 tr:eq(' + k + ')').find('input[type=hidden]').val(v['bloodtube_cate_id']);
            $('#info4 tr:eq(' + k + ')').find('input[name=code1]').val(v['code1']);
            $('#info4 tr:eq(' + k + ')').find('input[name=code2]').val(v['code2']);
        });
    }
    init();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.insert, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function() {
                    window.location.reload();
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    //添加
    $(document).on('click', '#add4', function() {
        $('#info4').append(function() {
            return $('#template4').html();
        }).find('tr:last td:eq(0)').text($('#info4 tr').size());
        $('.form-datetime').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            clearBtn: true,
            todayBtn: true,
            autoclose: true,
            minuteStep: 1,
            minView: 0,
            language: 'zh-CN'
        });
    });
    //删除
    $(document).on('click', '.del4', function() {
        $(this).parents('tr').remove();
        $('#info4 tr').each(function(k, v) {
            $(this).find('td:eq(0)').text(k + 1);
        });
    });
    //获取采血管组code
    $(document).on('change', 'input[name=code1]', function() {
        var $this = $(this);
        if ($this.val() == "") {
            $this.parent().next().find('input').val('');
            $this.parents('tr').find('td:last input').val('');
        } else {
            var data = {
                code: $this.val(),
                id: $('#id').val(),
                case_id: $('#case_id').val()
            };
            $.post(url.getCode, data, function(data) {
                if (data.status == 1) {
                    var rs = JSON.parse(data['info']);
                    $this.parent().next().find('input').val(rs['code']);
                    $this.parents('tr').find('td:last input').val(rs['bloodtube_cate_id']);
                } else {
                    layer.alert(data.info, function(index) {
                        $this.val('');
                        layer.close(index);
                    });
                }
            }, 'json');
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
