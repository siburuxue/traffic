// 定义全局变量
var submit;
var page = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
var deleteWin,backWin;
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

   


    //作废
    $('#table-content').on('click', '.delete-btn', function() {
        var the = $(this);
        if($(this).data('msg') != ''){
            layer.alert($(this).data('msg'),function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作',function(index){
                layer.close(index);
                var id = the.data('id');
                $('#delete-box').find('input[name="id"]').val(id);
                deleteWin = layer.open({
                    type: 1,
                    closeBtn: 0,
                    area: ['600px', '240px'],
                    content: $('#delete-box'),
                    zIndex: 1,
                    title: '作废'
                });
            },function(){

            });
        }
    });



    $('#delete-submit').on('click', function() {
        var dialog = $('#delete-box');
        $.post(url.delete, {
            id: dialog.find('input[name="id"]').val(),
            del_reason: dialog.find('textarea[name="del_reason"]').val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    layer.close(deleteWin);
                    dialog.find('input[name="id"]').val('');
                    dialog.find('textarea[name="del_reason"]').val('');
                    submit.execute('page');
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    });
    $('#delete-close').on('click', function() {
        layer.close(deleteWin);
        var dialog = $('#delete-box');
        dialog.find('input[name="id"]').val('');
        dialog.find('textarea[name="del_reason"]').val('');
    });

    //退回
    $('#table-content').on('click', '.back-btn', function() {
        var the = $(this);
        if($(this).data('msg') != ''){
            layer.alert($(this).data('msg'),function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作',function(index){
                layer.close(index);
                var id = the.data('id');
                $('#back-box').find('input[name="id"]').val(id);
                backWin = layer.open({
                    type: 1,
                    closeBtn: 0,
                    area: ['600px', '240px'],
                    content: $('#back-box'),
                    zIndex: 1,
                    title: '退回'
                });
            },function(){

            });
        }
    });
    $('#back-submit').on('click', function() {
        var dialog = $('#back-box');
        $.post(url.back, {
            id: dialog.find('input[name="id"]').val(),
            back_reason: dialog.find('textarea[name="back_reason"]').val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    layer.close(backWin);
                    dialog.find('input[name="id"]').val('');
                    dialog.find('textarea[name="back_reason"]').val('');
                    submit.execute('page');
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    });
    $('#back-close').on('click', function() {
        layer.close(backWin);
        var dialog = $('#back-box');
        dialog.find('input[name="id"]').val('');
        dialog.find('textarea[name="back_reason"]').val('');
    });
});
