// 定义全局变量
var submit;
var approvalWin;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.check, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    $('.js-close').click();
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
    //审批状态改变
    $('#status').on('change',function(){
        if(accidentType == '3'){
            if(level != "2"){
                if($(this).val() == '1'){
                    $('#submit').prop('disabled',true);
                    $('#approval').prop('disabled',false);
                }else{
                    $('#submit').prop('disabled',false);
                    $('#approval').prop('disabled',true);
                }
            }
        }else{
            if(level != "1"){
                if($(this).val() == '1'){
                    $('#submit').prop('disabled',true);
                    $('#approval').prop('disabled',false);
                }else{
                    $('#submit').prop('disabled',false);
                    $('#approval').prop('disabled',true);
                }
            }
        }
    });
    // 提请审批
    //提审按钮
    $('#approval').on('click', function() {
        var the = $(this);
        var id = the.data('id');
        approvalWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#approval-box'),
            zIndex: 1,
            title: 0,
            scrollbar: 0,
            end: function(){
                //window.location.reload();
            }
        });
    });
    $('#approval-reset').on('click',function () {
        $('#approval-list input[type=radio]:eq(0)').prop('checked',true);
    });
    //提审提交按钮
    $('#approval-submit').on('click', function(){
        var data = {
            id:$('#id').val(),
            check_user_id:$('input[type=radio]:checked').val(),
            remark:$('#remark').val()
        };
        $.post(url.checkApprove,data,function(msg){
            if(msg.status == 1){
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    $('#approval-close').click();
                    $('.js-close').click();
                });
            }else{
                layer.alert(msg.info,function(index){
                    layer.close(index);
                });
            }
        });
    });
    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked',false).filter(':eq(0)').prop('checked',true);
    });
});
