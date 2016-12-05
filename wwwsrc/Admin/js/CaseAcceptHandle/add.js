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
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function(index) {
                    window.location.href = msg.url;
                });
            }else{
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
    
    
    /**************************************************************************************************/
});
// 提请审批
$(function() {
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
                    area: ['100%', '100%'],
                    content: $('#approval-box'),
                    zIndex: 1,
                    scrollbar:0,
                    title: 0,
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
            layer.msg(data.info,{shade:0.1,shadeClose:true},function(index){
                if(data.status == 1){
                    window.location.href = data.url;
                }
            });
        },'json');
    });
    $('#approval-reset').on('click',function () {
        $('#approval-list input[type=radio]:eq(0)').prop('checked',true);
    });
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked',false).filter(':eq(0)').prop('checked',true);
    });
});
