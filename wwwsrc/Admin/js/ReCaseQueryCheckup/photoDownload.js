$(function(){
    // 下载
    $('#download').on('click', function() {
        var ids = new Array();
        $('#photoTable').find('input:checkbox:enabled:checked').each(function() {
            ids.push($(this).attr('value'));
        });
        if (ids.length <= 0) {
            layer.alert('请选择下载图片');
            return false;
        }
        window.open(url.photoDownload.replace('__IDS__', ids.join()));
        $('#case-photo-content').find('input:checkbox[name="case-photo-selectone"]:enabled').prop('checked', false);
    });

});
