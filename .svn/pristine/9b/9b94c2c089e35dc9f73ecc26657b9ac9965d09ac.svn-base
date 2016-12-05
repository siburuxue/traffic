// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
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
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.insert, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    window.location.href = msg.url;
                    //submit.reset();
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // $('.js-end-refresh').data('end', function() {
    //     window.location.reload();
    // });


});

$(function() {
    $('.js-end-refresh').data('end', function() {
       // var pic = $('input[type="checkbox"]');
        //alert(pic.length);
       window.location.reload();
    });
});


$(function() {

    // $('#reset').on('click', function() {
    //     parent.layer.close(win_index);
    //     //window.location.reload();
    // });
    $('.re-check-refresh').on('click', function() {
        //alert();
        //layer.close(index);
        history.back();
    });


    //提请重新检验鉴定 
    $('#re-checkup').on('click', function() {
        var from_user_id = $('select[name="from_user_id"] option:selected').val();
        var again_content = $('input[name="again_content"]').val();
        if (!from_user_id) {

            layer.alert('请选择申请人', function(index) {
                layer.close(index);
            });
            return;
        }
        if (!again_content) {

            layer.alert('请填写申请理由', function(index) {
                layer.close(index);
            });
            return;
        }
        var the = $(this);
        var id = the.data('id');
        approvalWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#approval-box'),
            zIndex: 1,
            title: false,
            end: function() {
                layer.close(approvalWin);
                //window.location.reload();
            }
        });
    });
    $('.reload-window').on('click', function() {
        layer.close(approvalWin);
        //window.location.reload();
    });



    //提审关闭按钮
    $('#approval-close').on('click', function() {
        //layer.close(approvalWin);
        $('textarea[name="content"]').val('');

        // var dialog = $('#approval-box');
        // dialog.find('input[type=radio]').prop('checked', false);
    });


    //选择审核人
    $('#approval-submit-2').on('click', function() {
        var content = $('textarea[name="content"]').val();
        if (!content) {
            layer.alert('请填写报告内容', function(index) {
                layer.close(index);
            });
            return;

        } else {

            var the = $(this);
            var id = the.data('id');
            approvalWin2 = layer.open({
                type: 1,
                closeBtn: 0,
                area: ['100%', '100%'],
                content: $('#approval-box-2'),
                zIndex: 1,
                title: false,
                end: function() {
                    layer.close(approvalWin2);
                    //window.location.reload();
                }
            });


        }

    });

    $('.reload-window-2').on('click', function() {
        layer.close(approvalWin2);
        //window.location.reload();
    });


    //提审提交按钮
    $('#approval-submit').on('click', function() {
        var to_user_id = $('input[name=to_user_id]:checked').val();
        var data = {
            'to_user_id': to_user_id,
            'check_user_id': to_user_id
        };
        $('.post-gather-top').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        data.from_user_name = $('select[name=from_user_id] option:selected').text();

        $.post(url.againInsert, data, function(msg) {
            //console.log(msg);
            if (msg.status == 1) {
                layer.msg(msg.info, { shade: 0.1, shadeClose: true }, function(index) {
                    window.location.href = msg.url;
                    //alert(1);
                    //layer.close(index);
                    //parent.layer.close(index);
                });
            } else {
                layer.alert(msg.info, function(index) {
                    //alert(2);
                    layer.close(index);
                    //layer.close(approvalWin2);
                    //parent.layer.close(win_index);
                });
            }

        }, 'json');
    });

});
