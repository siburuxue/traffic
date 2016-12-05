    // 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    $('#approval').on('click', function() {
        var the = $(this);
        var id = the.data('id');

        var delay_content = $('textarea[name="content"]').val();
        if (delay_content == '' || delay_content == null) {
            layer.alert('请填写报告内容', function(index) {
                layer.close(index);
                return;
            });
        } else {
            var get_datetime = $('input[name="finish_time"]').val();
            var datetime = $('#datetime').val();
            if(get_datetime<=datetime){
                approvalWin = layer.open({
                    type: 1,
                    closeBtn: 0,
                    area: ['100%', '100%'],
                    content: $('#approval-box'),
                    zIndex: 1,
                    title: false,
                    end: function() {
                        layer.close(index);
                        //window.location.reload();
                    }
                });

            }else{

                layer.alert('延期时间不可超过当前时间之后60个自然日', function(index) {
                    layer.close(index);
                    return;
                });

            }
            
        }

    });
    $('#reset').on('click', function() {
        window.location.reload();
    });
    $('#js-close-jimmy').on('click', function() {
        layer.close(approvalWin);
    });  

    //提审关闭按钮
    $('#approval-close').on('click', function() {
        //layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked', false);
    });
    //提审提交按钮
    $('#approval-submit').on('click', function() {
        var check_user_id = $('input[name=check_user_id]:checked').val();
        var case_id = $('#case_id').val();
        var case_checkup_id = $('#case_checkup_id').val();
        var data = {
            'check_user_id': check_user_id,
            'case_id': case_id,
            'case_checkup_id': case_checkup_id
        };
        $('.post-gather').each(function() {
            data[$(this).attr('name')] = $(this).val();
        });
        //console.log(data);
        $.post(url.check, data, function(data) {
            if (data.status == 1) {
                layer.msg(data.info, { shade: 0.1, shadeClose: true }, function(index) {
                    layer.close(index);
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(data.info, function(index) {
                    layer.close(index);
                    //parent.layer.close(win_index);
                });
            }

        }, 'json');
    });

});

// 时间拾取
$(function() {
    // 创建日期拾取器


});
