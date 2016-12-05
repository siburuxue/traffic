$(function() {
    // 关联
    $('#link-submit').on('click', function() {
        $.post(url.link, {
            ids: [$('input[name="id"]').val()],
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    window.location.reload();
                }
            });
        });
    });
    // 取消关联
    $('#unlink-submit').on('click', function() {
        $.post(url.unlink, {
            ids: [$('input[name="id"]').val()],
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    window.location.reload();
                }
            });
        });
    });
    // 刷新事件
    $('.js-end-refresh').data('end', function() {
        window.location.reload();
    });
});
