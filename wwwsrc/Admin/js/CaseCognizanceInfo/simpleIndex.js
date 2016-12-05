// 定义全局变量
var submit;
var approvalWin;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.save, data, function(msg) {
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
    //当事人-保险凭证号
    submit.reg({
        group: 'gather',
        name: 'detain_force_list',
        get: function(name) {
            return $('.client-table').map(function(){
                return {
                    id:$(this).attr('id'),
                    detain_force_id:$(this).find('.detain_force_id').val()
                }
            }).get()
        },
        set: function(name, value, data) {

        }
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });

    //如果简易事故认定已经被打印 画面上不能修改
    if(is_printed == "1" || is_back == "1"){
        $('textarea,#reset,#submit').prop('disabled',true);
    }
    /**************************************************************************************************/
    //退回
    $('#back').on('click', function() {
        if($(this).data('msg') != ''){
            layer.alert($(this).data('msg'),function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作',function(index){
                layer.close(index);
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
            id: $('#case_id').val(),
            back_reason: dialog.find('textarea[name="back_reason"]').val()
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    layer.close(backWin);
                    dialog.find('textarea[name="back_reason"]').val('');
                    window.location.reload();
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
        dialog.find('textarea[name="back_reason"]').val('');
    });
    //是否显示退回按钮
    if(window.parent.parent.location.href.indexOf('m=Admin&c=AccidentSearchLeader&a=index') != -1){
        $('#back').show();
    }
    $('#case-photo-upload,#case-photo-delete').remove();
});
