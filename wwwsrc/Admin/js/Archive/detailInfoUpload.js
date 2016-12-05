// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    var photoTableInit = function() {
            var case_id = $('#target_case_id').val();
            var id = $('#target_id').val();
            var cate = $('#target_cate').val();
            $('#photoTable').parent().load(url.photoList, { case_id: case_id, id: id, cate: cate });
        }
        //上传图片
    $('#upload').data('end', function() {
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function() {

        var idss = $('#photoTable input:checked').map(function() {
            return $(this).val();
        }).get();
        if (idss == "" || idss == null) {
            layer.alert('未选择任何图片，不能操作！');
            return;
        }

        layer.confirm('是否确定删除图片?', {
            btn: ['是', '否'],
            closeBtn: 0
        }, function(index) {

            var ids = $('#photoTable input:checked').map(function() {
                return $(this).val(); }).get();
            var case_id = $('#target_case_id').val();
            $.post(url.delete, { ids: ids, case_id: case_id }, function(msg) {
                layer.alert(msg.info);
                if (msg.status == 1) {
                    $('#photoTable input:checked').parents('.col-xs-2').remove();
                }
            });
        });
    });

});
