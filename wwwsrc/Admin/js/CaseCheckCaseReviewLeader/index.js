$(function() {
    /**************************************************************************************************/
    // 是否同意
    var buttonStatus = function() {
        var status = $('#status').val();
        var level = $('input[name="level"]').val();
        var isDeath = $('input[name="is_death"]').val();
        // 判断最后一次审批
        $('#apply').show();
        if (level == 0) {
            if (isDeath == 0) {
                $('#apply').hide();
            }
        } else {
            $('#apply').hide();
        }
        // 判断是否已完成审批
        if ($('#status').prop('disabled')) {
            $('#submit').prop('disabled', true);
            $('#apply').prop('disabled', true);
            return false;
        }
        // 按钮可用状态
        if (status == 1) {
            if (level == 0) {
                if (isDeath == 1) {
                    $('#submit').prop('disabled', true);
                    $('#apply').prop('disabled', false);
                } else {
                    $('#submit').prop('disabled', false);
                    $('#apply').prop('disabled', true);
                }
            } else {
                $('#submit').prop('disabled', false);
                $('#apply').prop('disabled', true);
            }
        } else {
            $('#submit').prop('disabled', false);
            $('#apply').prop('disabled', true);
        }
    };
    $('#status').on('change', buttonStatus);
    buttonStatus();
    /**************************************************************************************************/
    // 提请审批
    var confirmWin;
    $('#apply').on('click', function() {
        confirmWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#confirm-box'),
            zIndex: 999,
            title: 0
        });
    });
    // 关闭
    $('#confirm-close').on('click', function() {
        layer.close(confirmWin);
    });
    // 提交
    $('#submit-check').on('click', function() {
        var dom = $('#confirm-box').find('input:radio[name="checkUserId"]:enabled:checked');
        if (dom.length <= 0) {
            layer.alert('请选择审核人');
            return false;
        }
        $.post(url.applyCheck, {
            id: $('input[name="id"]').val(),
            status: $('#status').val(),
            remark: $('#remark').val(),
            check_user_id: dom.val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    layer.close(confirmWin);
                    // window.location.reload();
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info);
            }
        });
    });
    /**************************************************************************************************/
    $('#submit').on('click', function() {
        var status = $('#status').val();
        var address = status == 1 ? url.resume : url.refuse;
        $.post(address, {
            id: $('input[name="id"]').val(),
            remark: $('#remark').val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    // window.location.reload();
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info);
            }
        });
    });
    /**************************************************************************************************/
});
