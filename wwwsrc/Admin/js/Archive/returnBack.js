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
    // 重置
    $('.search-reset-jimmy').on('click', function() {
        window.location.reload();
        //submit.reset();
    }); 

    $('#reset-jimmy').on('click', function() {
        parent.layer.close(win_index);

        //window.location.reload();
        //submit.reset();
    });  

    $('#reset-jimmy-backData').on('click', function() {
        //parent.layer.close(win_index);

        window.location.reload();
        //submit.reset();
    });  
});

// 时间拾取
$(function() {
    // 创建日期拾取器
    
   
    
});
//案件归档数据保存
// duty_advance  高级全新操作人员 选择大队 读取大队所属值班人选 联动菜单
$(function() {
    $('#archive-submit').on('click', function() {
        if(!$("input[name='archive_case_id']:checked").val()){
            layer.alert('请选择案件');
            return;
        };
        if(!$("input[name='archive_code']").val()){
            layer.alert('请填写档案编号');
            return;
        };
        if(!$("input[name='archive_name']").val()){
             layer.alert('请填写档案名称');          
            return;
        };
        if(!$("input[name='archive_catalog']").val()){
            layer.alert('请填写目录号');
            return;
        };
        if(!$("input[name='archive_dossier']").val()){
            layer.alert('请填写案卷号');
            return;
        };
        if(!$("input[name='archive_case_no']").val()){
            layer.alert('请填写案件号');
            return;
        };
        if(!$("input[name='archive_place']").val()){
            layer.alert('请填写所在位置');
            return;
        };

        a_data = {
            'case_id':$("input[name='archive_case_id']:checked").val(),
            'code':$("input[name='archive_code']").val(),
            'name':$("input[name='archive_name']").val(),
            'catalog':$("input[name='archive_catalog']").val(),
            'dossier':$("input[name='archive_dossier']").val(),
            'case_no':$("input[name='archive_case_no']").val(),
            'place':$("input[name='archive_place']").val()
        };

        $.post(url.update,a_data, function(msg) {

            //console.log(msg);
            if (msg.status==1) {
                //var userData = msg.info;
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                      window.location.reload(true);                  
                    //parent.layer.close(win_index);
                });
                
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                    //parent.layer.close(win_index);
                });
            }
        });

    });
});
