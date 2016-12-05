$(function() {
    /*展开、收起*/
    $('.show-hide').on('click', function () {
        $(this).text(function(){
            return $(this).text() == '展开'?'收起':'展开';
        });
    });
    //下载图片
    $('.download').on('click',function(){
        if($(this).parents('.form-inline').find('input[type=checkbox]:checked').size() == 0){
            layer.alert('请选择图片',function (index) {
                layer.close(index);
            });
        }else{
            var ids = $(this).parents('.form-inline').find('input[type=checkbox]:checked').map(function(){ return $(this).val(); }).join();
            window.open(url.download + "&ids=" + ids);
        }
    });
    var case_id  = $('#case_id').val();
    var id = $('#id').val();
    $('.upload').each(function(){
        var cate = $(this).parents('.photo-box').attr('id');
        $(this).parents('.photo-box').find('.photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:cate});
    });
    $('#list-all .photoTable').each(function(){
        var cate = $(this).parent().data('cate');
        $(this).parent().load(url.photoList,{case_id:case_id,id:id,cate:cate});
    });
});
