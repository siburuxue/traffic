// 定义全局变量
var submit;
var approvalWin;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续更新', '关闭窗口']
                }, function(index) {
                    layer.close(index);
                    window.location.reload();
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
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    // 创建日期拾取器
    
    
    var photoTableInit = function(){
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:id});
    }
    //上传图片
    $('#upload').data('end',function(){
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function(){
        var ids = $('#photoTable input:checked').map(function(){ return $(this).val(); }).get();
        var case_id = $('#case_id').val();
        $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
            layer.alert(msg.info);
            if(msg.status == 1){
                $('#photoTable input:checked').parents('.col-xs-2').remove();
            }
        });
    });
    /**************************************************************************************************/
    // 提请审批
    //提审按钮
    $('#approval').on('click', function() {
        var data = {};
        $('.post-gather').map(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.inputCheck, data, function(msg) {
            if(msg.status == 1){
                var the = $(this);
                var id = the.data('id');
                approvalWin = layer.open({
                    type: 1,
                    closeBtn: 0,
                    area: ['600px', '200px'],
                    content: $('#approval-box'),
                    zIndex: 1,
                    title: '审核人确认',
                    end: function(){
                        //window.location.reload();
                    }
                });
            }else{
                layer.alert(msg.info,function(index){
                    layer.close(index);
                })
            }
        });
    });
    //提审提交按钮
    $('#approval-submit').on('click', function(){
        var check_user_id = $('input[name=check]:checked').val();
        var case_id = $('#case_id').val();
        var id = $('#id').val();
        var data = {
                        check_user_id:check_user_id,
                        case_id:case_id,
                        id:id
                    };
        $('.post-gather').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        $.post(url.check,data,function(data){
            layer.alert(data.info,function(index){
                layer.close(index);
                if(data.status == 1){
                    window.location.reload();
                }
            });
        },'json');
    });
    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked',false).filter(':eq(0)').prop('checked',true);
    });
    //画面加载审批列表
    $('#checkTable').load(url.checkTable,{id:$('#id').val(),case_id:$('#case_id').val()});
});
