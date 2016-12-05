// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    $('#to_checkup_org_id').on('change', function() {
        var org_name = $(this).children('option:selected').text();
        $('#to_checkup_org_name').val(org_name);

    });


    // 提交
    $('#submit').on('click', function() {
        var data = {};
        $('.post-gather-re-entrust').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        layer.confirm('是否确认保存', {
            btn: ['是', '否']
        }, function(index) {
            $.post(url.reEntrustInsert, data, function(msg) {
                if (msg.status == 1) {
                    layer.msg(msg.info, {
                        shade: 0.1,
                        shadeClose: true
                    }, function(index) {
                        layer.close(index);
                        window.location.href = msg.url;
                    });
                } else {
                    layer.alert(msg.info, function(index) {
                        layer.close(index);
                    });
                }
            });
        });
        //submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        //parent.layer.close(index);
        window.location.reload();
    });

    $('#close-parent').on('click', function() {
        //history.back();
        parent.layer.close(index);
        //window.location.reload();
    });

});
