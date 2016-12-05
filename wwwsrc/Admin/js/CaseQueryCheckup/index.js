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
        data.is_cancel = $('#is_cancel').val();
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
            $('.for-cancel').on('click', function() {
                var get_case_checkup_id = $(this).data('id');



                $.post(url.caseCheckupCancelStatus, { id: get_case_checkup_id,}, function(msg) {

                    if(msg.status==1){
                        layer.confirm('是否要作废此项', {
                            btn: ['是', '否']
                        }, function(index) {
                            layer.close(index);
                            var the = $('#approval');
                            var id = the.data('id');
                            approvalWin = layer.open({
                                type: 1,
                                closeBtn: 0,
                                area: ['600px', '230px'],
                                content: $('#approval-box'),
                                zIndex: 1,
                                title: '检验鉴定作废',
                                end: function() {
                                    layer.close(index);
                                    //window.location.reload();
                                }
                            });
                            //提审关闭按钮
                            $('#approval-close').on('click', function() {
                                layer.close(approvalWin);
                                var dialog = $('#approval-box');
                                dialog.find('input[type=radio]').prop('checked', false);
                            });
                            //提审提交按钮
                            $('#approval-submit').on('click', function() {
                                layer.close(approvalWin);
                                var case_checkup_id = get_case_checkup_id;
                                var cancel_reason = $('#cancel_reason').val();
                                $.post(url.caseCheckupCancel, { id: case_checkup_id, cancel_reason: cancel_reason }, function(msg) {
                                    layer.msg(msg.info, {shade:0.1,shadeClose:true},function(index) {
                                        layer.close(index);
                                        window.location.reload();
                                    });
                                }, 'json');
                            });
                        }, function(index) {
                            layer.close(index);
                            //window.location.reload();
                            // parent.layer.close(win_index);
                        });
                    }else{
                        layer.alert(msg.info,function(index){
                             layer.close(index);
                        });
                    }

                }, 'json');
            });
        });
    };
    // 注册通用分页事件
    commonPageEvent(submit);
    // 初始化
    submit.execute('page');
    $('#search-jump').prop('disabled', false);
});
