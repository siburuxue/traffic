$(function() {
    // 上传封面
    $('form[name="myform"]').ajaxForm({
        dataType: 'json',
        resetForm: true,
        success: function(responseText, statusText, xhr, $form) {
            if (responseText.status == 1) {
                layer.msg('上传成功', {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                });
            } else {
                layer.alert(responseText.info);
            }
        }
    });
    $('#submit').on('click', function() {
        if ($('input:file[name="file"]').val() == '') {
            layer.alert('请选择要上传的文件！');
            return false;
        }
        $('form[name="myform"]').submit();
    });
});
