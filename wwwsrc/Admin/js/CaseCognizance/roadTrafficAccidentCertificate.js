// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.saveCognizanceProve, data, function(msg) {
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
    if($('#id').val() == ''){
        $('#case-photo-upload,#case-photo-download,#case-photo-delete').remove();
    }
    //如果被退回或者是已提交审批的状态下 画面不能修改
    if(is_submit == "1"){
        $('#submit,#reset,textarea,#approval,#send').prop('disabled',true);
    }
    if(check_status == '3'){
        $('#submit,#approval,#reset,textarea').prop('disabled',false);
        $('#make,#print,#issue').prop('disabled',true).attr('href','').removeClass('js-open');
    }
    if(is_back == "1"){
        $('#submit,#reset,textarea,#approval,#send').prop('disabled',true);
        $('#upload').attr('href','').prop('disabled',true).attr('disabled','disabled');
    }
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
            scrollbar:0,
            end: function(){
                window.location.reload();
            }
        });
    });
    //提审提交按钮
    $('#approval-submit').on('click', function(){
        var check_user_id = $('input[name=check]:checked').val();
        var case_id = $('#case_id').val();
        var id = $('#id').val();
        var case_cognizance_id = $('#case_cognizance_id').val();
        var data = {
            check_user_id:check_user_id,
            case_id:case_id,
            case_cognizance_id:case_cognizance_id,
            case_cognizance_report_id:$('#case_cognizance_report_id').val(),
            cate:4,
            id:id,
            base_info:$('#base_info').val(),
            fact:$('#fact').val()
        };
        $.post(url.check,data,function(data){
            if(data.status == 1){
                layer.msg(data.info,{shade:0.1,shadeClose:true},function(){
                    window.location.href = data.url;
                });
            }else{
                layer.alert(data.info,function(index){
                    layer.close(index);
                });
            }
        },'json');
    });
    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked',false);
    });
    $('#approval-reset').on('click',function () {
        $('#approval-list input[type=radio]:eq(0)').prop('checked',true);
    });
    //制作
    $('#make').on('click',function(){
        var data = {
            cognizanceId:$('#case_cognizance_id').val(),
            case_id:$('#case_id').val()
        };
        $.post(url.make,data,function(msg){
            if(msg.status == '1'){
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
    /**************************************************************************************************/
});
