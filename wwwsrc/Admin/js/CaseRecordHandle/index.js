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
    //被询问人姓名
    submit.reg({
        group: 'page',
        name: 'case_id',
        get: function(name) {
            return $('input[name=case_id]').val();
        },
        set: function(name, value, data) {}
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
    //传图片关闭时获取图片页数
    $('.page-num').data('end', function() {
        var $this = $(this);
        var cate = '';
        var record_type = $(this).parents('tr').find('td:last').text();
        if(record_type == "0"){
            cate = 61;
        }else if(record_type == "1"){
            cate = 62;
        }else if(record_type == "2"){
            cate = 63;
        }
        var id = $this.data('id');
        var case_id = $('#case_id').val();
        var data = {
            cate: cate,
            id: id,
            case_id: case_id
        };
        $.post(url.getCount, data, function(data) {
            $this.text(data['info'])
        }, 'json');
    })
});
