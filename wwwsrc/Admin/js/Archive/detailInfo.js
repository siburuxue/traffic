// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
   var photoTableInit = function(){
        var case_id  = $('#target_case_id').val();
        var id = $('#target_id').val();
        var cate = $('#target_cate').val();

        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:cate});
    }
    photoTableInit();

});

