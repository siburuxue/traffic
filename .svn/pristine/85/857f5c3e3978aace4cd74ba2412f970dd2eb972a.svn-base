// 定义全局变量
var submit;
var page = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
// 页面加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 注册提交字段
    $('.post-gather').each(function() {
        var the = $(this);
        submit.reg({
            group: 'gather',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增', '关闭窗口']
                }, function(index) {
                    layer.close(index);
                    submit.reset();
                }, function(index) {
                    layer.close(index);
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });

    $('#execute').on('click',function(){
        var sql = $('#content').val();
        if(sql.indexOf('insert') != -1 || sql.indexOf('update') != -1 || sql.indexOf('delete') != -1 || sql.indexOf('modify') != -1
            || sql.indexOf('alter') != -1 || sql.indexOf('create') != -1 || sql.indexOf('drop') != -1 || sql.indexOf('truncate') != -1 ){
            layer.alert('查询中不能包含可以改变数据库结构、数据状态的语句',function(index){
                layer.close(index);
            });
        }else{
            submit1 = $.vmcSubmit();
            // 发送POST请求
            submit1.success = function(data) {
                data['sql'] = sql;
                $('#table-content').load(url.table, data, function(response, status, xhr) {
                    if(status == 'error'){
                        layer.alert('查询结果异常，请重新输入模板内容',function(index){
                            layer.close(index);
                        });
                    }else{
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
                        $('#data-panel').show();
                    }
                });
            };
            // 注册通用分页事件
            commonPageEvent(submit1);
            // 初始化
            submit1.execute('page');
        }
    });
});
