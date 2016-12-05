// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        data['type'] = 'normal';
        $.post(url.saveReportInfo, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function() {
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
    //如果被退回或者是已提交审批的状态下 画面不能修改
    if(is_submit == "1"){
        $('#submit,#reset,textarea').prop('disabled',true);
        $('.short-box').addClass('disabled');
    }
    if(check_status == '3'){
        $('#submit,#reset,textarea').prop('disabled',false);
        $('#print').prop('disabled',true);
        $('.short-box').removeClass('disabled');
    }
    if(is_back == "1"){
        $('#submit,#reset,textarea').prop('disabled',true);
        $('#case-photo-upload').prop('disabled',true);
        $('.short-box').addClass('disabled');
    }
    if($('#id').val() == ''){
        $('#case-photo-upload,#case-photo-download,#case-photo-delete').remove();
    }
    /**************************************************************************************************/
});
