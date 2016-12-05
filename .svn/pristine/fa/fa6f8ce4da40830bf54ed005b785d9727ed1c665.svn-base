// 页面加载完毕
$(function() {
    /**************************************************************************************************/
    var photoTableInit = function(){
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:60});
    }
    photoTableInit();
    //删除图片
    $('#delete').click(function(){
        if($('#photoTable input:checked').size() == 0){
            layer.alert('未选择图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $('#photoTable input:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                            $('#photoTable input:checked').parents('.col-xs-2').remove();
                        });
                    }else{
                        layer.alert(msg.info,function(index){
                            layer.close(index);
                        });
                    }
                });
            },function(){

            });
        }
    });
});
