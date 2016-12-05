// 定义全局变量
var submit;
// var page = {
//     totalrows: 0,
//     totalpage: 1,
//     nowpage: 1
// };
// var page1 = {
//     totalrows: 0,
//     totalpage: 1,
//     nowpage: 1
// };
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    submit1 = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        data['case_id'] = $('#case_id').val();
        $('#table-content').load(url.applyTable, data, function(response, status, xhr) {
            // var $table = $(this).find('#table');
            // $table.find('.js-end-refresh').data('end', function() {
            //     submit.execute('page');
            // });
            // page.totalrows = $table.data('totalrows');
            // page.totalpage = $table.data('totalpage');
            // page.nowpage = $table.data('nowpage');
            // // 更新分页信息
            // $('#apply-div .page-first,.page-prev').prop('disabled', page.nowpage <= 1);
            // $('#apply-div .page-next,.page-last').prop('disabled', page.nowpage >= page.totalpage);
            // $('#apply-div #page-nowpage').text(page.nowpage);
            // $('#apply-div #page-totalpage').text(page.totalpage);
            // $('#apply-div #page-totalrows').text(page.totalrows);
            // $('#apply-div #search-page').val(page.nowpage).attr('max', page.totalpage);
            $('.page-num,.unMediate').data('end',function () {
                submit.execute();
            });
        });
    };
    submit1.success = function(data) {
        data['case_id'] = $('#case_id').val();
        $('#table-content1').load(url.mediateTable, data, function(response, status, xhr) {
            // var $table = $(this).find('#table1');
            // $table.find('.js-end-refresh').data('end', function() {
            //     submit1.execute('page');
            // });
            // page1.totalrows = $table.data('totalrows');
            // page1.totalpage = $table.data('totalpage');
            // page1.nowpage = $table.data('nowpage');
            // // 更新分页信息
            // $('#mediate-div .page-first,.page-prev').prop('disabled', page1.nowpage <= 1);
            // $('#mediate-div .page-next,.page-last').prop('disabled', page1.nowpage >= page1.totalpage);
            // $('#mediate-div #page-nowpage').text(page1.nowpage);
            // $('#mediate-div #page-totalpage').text(page1.totalpage);
            // $('#mediate-div #page-totalrows').text(page1.totalrows);
            // $('#mediate-div #search-page').val(page1.nowpage).attr('max', page1.totalpage);
            $('.notice,.result').data('end',function () {
                submit1.execute();
            });
        });

    };
    // 注册通用分页事件
    // commonPageEvent(submit);
    // commonPageEvent(submit1);
    // 初始化
    submit.execute();
    submit1.execute();

    $('#save').on('click',function(){
        var data = {};
        $('.post-gather').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.addMediateApply,data,function(msg){
            if(msg.status == 1){
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                    window.location.reload();
                });
            }else{
                layer.alert(msg.info,function(index){
                    layer.close(index);
                });
            }
        });
    });
    // //申请页面部分
    // // 第一页
    // $('#apply-div').find('.page-first').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#apply-div').find('#search-page').val(1);
    //     searchSubmit.execute('page');
    // });
    // // 最后一页
    // $('#apply-div').find('.page-last').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#apply-div').find('#search-page').val(searchPage.totalpage);
    //     searchSubmit.execute('page');
    // });
    // // 上一页
    // $('#apply-div').find('.page-prev').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#apply-div').find('#search-page').val(searchPage.nowpage - 1);
    //     searchSubmit.execute('page');
    // });
    // // 下一页
    // $('#apply-div').find('.page-next').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#apply-div').find('#search-page').val(searchPage.nowpage + 1);
    //     searchSubmit.execute('page');
    // });
    // // 当点击跳转按钮
    // $('#apply-div').find('#search-jump').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     submit.execute('page');
    // });
    // //调节页面部分
    // // 第一页
    // $('#mediate-div').find('.page-first').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#mediate-div').find('#search-page').val(1);
    //     searchSubmit.execute('page');
    // });
    // // 最后一页
    // $('#mediate-div').find('.page-last').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#mediate-div').find('#search-page').val(searchPage.totalpage);
    //     searchSubmit.execute('page');
    // });
    // // 上一页
    // $('#mediate-div').find('.page-prev').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#mediate-div').find('#search-page').val(searchPage.nowpage - 1);
    //     searchSubmit.execute('page');
    // });
    // // 下一页
    // $('#mediate-div').find('.page-next').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     $('#mediate-div').find('#search-page').val(searchPage.nowpage + 1);
    //     searchSubmit.execute('page');
    // });
    // // 当点击跳转按钮
    // $('#mediate-div').find('#search-jump').on('click', function() {
    //     if ($(this).prop('disabled')) {
    //         return;
    //     }
    //     submit1.execute('page');
    // });
    //全选
    $(document).on('change','#all-check',function(){
        $(this).parents('table').find('input[type=checkbox]').prop('checked',$(this).prop('checked'));
    });
    $(document).on('change','#table tbody input[type=checkbox]',function(){
        $('#all-check').prop('checked',$('#table tbody input[type=checkbox]:checked').size() == $('#table tbody input[type=checkbox]').size());
    });
    //调节
    $('#mediate').on('click',function(){
        if($('table tbody input:checked').size() == 0){
            layer.alert('请选择待调解当事人',function (index) {
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var data = {
                    case_id:$('#case_id').val(),
                    ids:$('#table tbody input:checked').map(function(){
                        return {
                            id:$(this).val(),
                            case_client_id:$(this).data('case-client-id'),
                            user_type:$(this).data('user-type')
                        }
                    }).get()
                };
                $.post(url.mediate,data,function (msg) {
                    if (msg.status == 1) {
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                            window.location.reload();
                        });
                    } else {
                        layer.alert(msg.info,function (index) {
                            layer.close(index);
                        });
                    }
                });
            },function () {

            })
        }
    });
    //删除
    $(document).on('click','.delete',function(){
        var $this = $(this);
        layer.confirm('确定执行该操作？',function(index){
            layer.close(index);
            var data = {
                id:$this.parents('tr').find('td:last input').val(),
                case_id:$('#case_id').val()
            }
            $.post(url.delete,data,function(msg){
                if(msg.status == 1){
                    layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                        window.location.reload();
                    });
                }else{
                    layer.alert(msg.info,function(index){
                        layer.close(index);
                    });
                }
            });
        },function () {
            
        })
    });
    //调解记录
    $('.result').data('end',function(){
        submit1.execute();
    });
    //当事人联动
    $('#case_client_id').on('change',function () {
        $('#apply_idno').val($(this).find('option:selected').data('idno'));
    });
});
