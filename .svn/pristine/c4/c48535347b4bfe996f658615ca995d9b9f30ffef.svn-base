// 页面加载完毕
$(function() {
    $('#edit').data('end',function () {
        var caseId = $('#case_id').val();
        $.post(url.getInfo,{id:caseId},function (msg) {
            if(msg.status == 1){
                $('#accident_time').val(msg.info.accident_time);
                $('#accident_place').val(msg.info.accident_place);
                $('#accident_name').val(msg.info.accident_name);
            }else{
                layer.alert(msg.info,function (index) {
                    layer.close(index);
                });
            }
        });
    });
	$('.js-open').data('end',function(){
		window.location.reload();
	});
});
