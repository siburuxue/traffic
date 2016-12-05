// 定义全局变量
var submit;
var page = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
var printPara = null;
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        printPara = data;
        $('#table-content').load(url.table, data, function(response, status, xhr) {
            var $table = $(this).find('#table');
            $table.find('.sequence').each(function(k,v){
                $(this).text(k + 1);
            });
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
        });
    };
    // 注册提交字段
    $('.search-auto').each(function() {
        var the = $(this);
        submit.reg({
            group: 'condition',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 创建日期拾取器
    // 注册通用分页事件
    commonPageEvent(submit);
    // 刷新事件
    $('.js-end-refresh').data('end', function() {
        submit.execute();
    });
    // 初始化
    submit.execute();
    $('#search-submit').on('click',function(){
        submit.execute();
    });
    //大队联动用户列表
    $('#brigade').on('change',function(){
        var data = {brigadeId:$(this).val()};
        $.post(url.getUserList,data,function(msg){
            if(msg.status == 1){
                $('#user').find('option:gt(0)').remove().end().append(function(){
                    return msg.info.map(function(v){
                        return '<option value="' + v['id'] + '">' + v['true_name'] + '</option>';
                    }).join('');
                });
            }
        });
    });
    //打印
    $('#print').click(function(){
        window.open(encodeURI(url.print + JSON.stringify(printPara)));
    });
});
