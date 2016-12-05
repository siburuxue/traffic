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
            commonSort($table);
            //案件关注点击事件
            $('#attention').on('click', function() {
                var allChecked = '';
                $('.selectAllcheckbox:checked').each(function() {
                    allChecked += $(this).val();
                    allChecked += ',';
                });
                if (allChecked) {
                    layer.confirm('是否确认关注', { btn: ['是', '否'] }, function() {
                        $.post(url.setAttention, { ids: allChecked }, function(msg) {
                            if (msg.status == 1) {
                                layer.msg(msg.info, { shade: 0.1, shadeClose: true }, function(index) {
                                    layer.close(index);
                                    parent.layer.close(win_index);
                                    //window.location.href=url.completed;
                                });

                            } else {
                                layer.alert(msg.info, function(index) {
                                    layer.close(index);
                                });
                            }

                        }, 'json');
                    });
                } else {
                    layer.alert('请选中案件', function(index) {
                        layer.close(index);
                    });
                }
            });
        });
    };
    // 注册通用分页事件
    commonPageEvent(submit);
    // 初始化
    submit.execute('page');
    $('#search-jump').prop('disabled', false);

    // $('.js-end-refresh').data('end', function() {
    //     window.location.reload();
    // });

});
