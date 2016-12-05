

$(function() {
    $('.js-close-picnumber').on('click', function() {
        var pic = $('input[type="checkbox"]');
        
        parent.$('#pic_number').text(pic.length);
        parent.layer.close(win_index);
    });

    $('.js-end-refresh').data('end', function() {
        window.location.reload();
    });
});

// 图片加载
$(function() {

    //删除图片
    $('#delete').click(function() {

        var idss = $('#photoTable input:checked').map(function() {
            return $(this).val();
        }).get();
        if(idss==""||idss==null){
            layer.alert('未选择任何图片，不能操作！');
            return;
        }

        layer.confirm('是否确定删除图片?', {
            btn: ['是', '否'],
            closeBtn: 0
        }, function(index) {
            var ids = $('#photoTable input:checked').map(function() {
                return $(this).val();
            }).get();
            var case_id = $('#case_id').val();
            $.post(url.delete, { ids: ids, case_id: case_id }, function(msg) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function(index){});
                if (msg.status == 1) {
                    $('#photoTable input:checked').parents('.col-xs-2').remove();
                }
            });
        }, function(index) {
            layer.close(index);
        });

    });


});