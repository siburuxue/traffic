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
        $('#table-content').load(url.table, data, function(response, status, xhr) {
            if(status == 'error'){
                layer.alert('查询结果异常，请重新输入模板内容',function(index){
                    layer.close(index);
                });
                $('#export').attr('href','javascript:;');
                $('#table-content').next().hide();
            }else{
                $('#table-content').next().show();
                $('#export').attr('href',function () {
                    return $(this).data('href');
                });
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
            }
        });
    };
    // 注册通用分页事件
    commonPageEvent(submit);
    // 初始化
    submit.execute('page');
});
