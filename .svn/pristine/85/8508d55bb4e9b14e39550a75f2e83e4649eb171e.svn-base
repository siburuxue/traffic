// 页面加载完毕
$(function() {

    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        parent.layer.close(win_index);
    });

});

$(function() {
    $('#accept_entrust').on('click', function() {
        var entrust_id = $('#entrust_id').val();
        layer.confirm('是否确认接收', {
            btn: ['是', '否']
        }, function(index) {
            $.post(url.setAccept, { 'id': entrust_id }, function(msg) {
                if (msg.status == 1) {
                    layer.msg(msg.info, {
                        shade: 0,
                        shadeClose: true
                    }, function(index) {
                        parent.layer.close(win_index);
                    });
                } else {
                    layer.alert(msg.info);
                    layer.close(index);
                }
            });
        });
    });
});
