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
        var notice_id = $('#notice_id').val();
        url.towords = '';
        if (notice_id != "" && notice_id != null) {
            url.towords = url.update;
        } else {
            url.towords = url.insert;
        }
        // alert($('#case_client_id').val());
        $.post(url.towords, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    window.location.href = msg.url;
                    // parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                    //window.location.reload();
                });
            }
        });
    };
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        //submit.reset();
        //window.location.reload();
        $('input[name="notice_place"]').val('');
        $('input[name="content"]').val('');

    });
});
// 时间拾取
$(function() {
    // 创建日期拾取器


});
//选择被告知人
$(function() {
    $('.client-item').each(function() {
        var default_names = $('#default_names').val();
        var the = $(this);
        var client = $(this).data('client');
        var top_name = $(this).data('name');
        $.post(url.getClientRelater, { id: client }, function(msg) {
            var obj = $('#target_person')[0];
            if (msg.status == 1) {
                
                if (msg.info != "" && msg.info != null) {
                    var allChildren = msg.info;
                    var n = allChildren.length;
                    allChildren[n] = top_name;
                    var allChildrenRelater = allChildren;
                    //obj.options.add(new Option('请选择', ''));                 
                    for (var i = 0; i < allChildren.length; i++) {
                        //alert(allChildren[i]);                        
                        if (allChildren[i] == default_names) {
                            the.find('.collapse').collapse('show');
                            var ml = allChildrenRelater.length-1;
                            delete allChildrenRelater[ml];
                            for (var m = 0; m <ml ; m++) {
                                obj.options.add(new Option(allChildrenRelater[m], allChildrenRelater[m]));
                            }
                            $("select[name='target_person'] option[value=" + $('#default_names').val() + "]").attr("selected", true);
                            return;
                        }
                    }

                } else {
                    if (top_name == default_names) {

                        the.find('.collapse').collapse('show');

                    }

                }


            } else {


            }

        });


        //the.find('.collapse').collapse('show');
        //return;
    });
});


$(function() {
    $('.jimmy-click').on('click', function() {
        var client = $(this).data("client");
        //alert(client);
        var names = $(this).data("name");
        $('.client-item').each(function() {
            var the = $(this);
            if (the.data('id') == client) {
                the.find('.collapse').collapse('show');
            }
        });

        //判断该当事人是否已经告知
        var notice_id = $('#notice_id').val();
        var case_id = $('#case_id').val();
        var case_checkup_id = $('#case_checkup_id').val();
        var case_checkup_report_id = $('#case_checkup_report_id').val();
        $.post(url.getNoticeInfo, { case_id: case_id, case_client_id: client, case_checkup_id: case_checkup_id, case_checkup_report_id: case_checkup_report_id }, function(msg) {
            //console.log(msg);return;
            if (msg.status == 1) {
                $('.to-print').prop('disabled', false);
                $('.to-print').prop('id', 'print');
                $('#notice_id').val(msg.info.id);
                $('#case_client_id').val(msg.info.case_client_id);
                $('#new_content').val(msg.info.content);
                $('#new_notice_place').val(msg.info.notice_place);
                $('#default_notice_place').val(msg.info.notice_place);
                $('#default_names').val(msg.info.target_person);
                $('#notice_time').val(msg.info.notice_time_date);
                $("select[name='is_clear'] option[value=" + msg.info.is_clear + "]").attr("selected", true);
                $("select[name='is_again'] option[value=" + msg.info.is_again + "]").attr("selected", true);
            } else {
                var nowtime = $('#nowtime').val();
                $('.to-print').prop('disabled', 'disabled');
                $('.to-print').prop('id', '');
                $('.jimmy-cancel').val('');
                $('#case_client_id').val(client);
                $('#notice_time').val(nowtime);
                $("select[name='is_clear'] option[value='1']").attr("selected", true);
                $("select[name='is_again'] option[value='0']").attr("selected", true);

            }
        });
        var default_names = $('#default_names').val();
        var default_content = $('#default_content').val();
        var obj = $('#target_person')[0];
        $('#target_person').find("option").remove();
        $.post(url.getClientRelater, { id: client }, function(msg) {
            if (msg.status == 1) {
                if (msg.info != "" && msg.info != null) {
                    var n = msg.info.length;
                    msg.info[n] = names;
                    //console.log(msg.info);
                    //$('#target_person').val(names);
                    var allChildren = msg.info;
                    //obj.options.add(new Option('请选择', ''));                 
                    for (var i = 0; i < allChildren.length; i++) {
                        obj.options.add(new Option(allChildren[i], allChildren[i]));
                    }
                    //$("#target_person").prepend("<option value='' selected='selected'>请选择</option>");
                    $("#target_person").each(function() {
                        if ($("#target_person option[value='" + default_names + "']").length > 1) {
                            $("#target_person option[value='" + default_names + "']:gt(0)").remove(); //删除Select中索引值为0的Option(第一个)
                        }
                    });
                    $("select[name='target_person'] option[value=" + $('#default_names').val() + "]").attr("selected", true);
                } else {
                    obj.options.add(new Option(names, names));
                }
            } else {
                obj.options.add(new Option(names, names));
            }
        }, 'json');


    });
    // $(".jimmy-click").click(function(e) {
    //     e.preventDefault();
    //     var client = $(this).data("client");
    //     alert(client);
    // });
});
// 图片加载
$(function() {
    var photoTableInit = function() {
            var case_id = $('#case_id').val();
            var id = $('#case_checkup_report_id').val();
            var cate = $('#photo_cate').val();

            $('#photoTable').parent().load(url.photoList, { case_id: case_id, id: id, cate: cate });
        }
        //上传图片
    $('#upload').data('end', function() {
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function() {

        var idss = $('#photoTable input:checked').map(function() {
            return $(this).val();
        }).get();
        if (idss == "" || idss == null) {
            layer.alert('未选择任何图片，不能操作！');
            return;
        }



        layer.confirm('是否确定删除图片?', {
            btn: ['是', '否'],
            closeBtn: 0
        }, function(index) {
            var ids = $('#photoTable input:checked').map(function() {
                return $(this).val();
            }).get();
            var case_id = $('#case_id').val();
            $.post(url.delete, { ids: ids, case_id: case_id }, function(msg) {
                layer.msg(msg.info, { shade: 0.1, shadeClose: true }, function(index) {});
                if (msg.status == 1) {
                    $('#photoTable input:checked').parents('.col-xs-2').remove();
                }
            });
        }, function(index) {
            layer.close(index);
        });

    });

});
