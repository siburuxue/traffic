// 定义全局变量
var submit;
var page = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();

    // 发送POST请求
    submit.success = function(data) {
        data.case_id = $('#case_id').val();
        data.case_checkup_id = $('#case_checkup_id').val();
        data.cate = $('#cate').val();
        $('#table-content').load(url.table, data, function(response, status, xhr) {
            var $table = $(this).find('#table');
            $table.find('.js-end-refresh').data('end', function() {
                submit.execute('page');
            });
            page.totalrows = $table.data('totalrows');
            page.totalpage = $table.data('totalpage');
            page.nowpage = $table.data('nowpage');
            // 更新分页信息
            $('.page-first,.page-prev').prop('disabled', page.nowpage <= 1);
            $('.page-next,.page-last').prop('disabled', page.nowpage >= page.totalpage);
            $('#page-nowpage').text(page.nowpage);
            $('#page-totalpage').text(page.totalpage);
            $('#page-totalrows').text(page.totalrows);
            $('#search-page').val(page.nowpage).attr('max', page.totalpage);
        });
    };
    // 注册通用分页事件
    commonPageEvent(submit);
    // 初始化
    submit.execute('page');
    $('#search-jump').prop('disabled', false);
});
// 页面加载完毕
$(function() {
    var photoTableInit = function() {
            var case_id = $('#case_id').val();
            var id = $('#case_checkup_id').val();
            var cate = $('#photo_cate').val();
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
