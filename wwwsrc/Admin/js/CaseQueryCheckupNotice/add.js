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
        $.post(url.update, data, function(msg) {
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
//选择被告知人
$(function() {
    $('.client-item').each(function() {

        var the = $(this);
        the.find('.collapse').collapse('show');
        var client = $(this).data('client');
        var top_name = $(this).data('name');
        $.post(url.getClientRelater, { id: client }, function(msg) {
            var obj = $('#target_person')[0];
            if (msg.status == 1) {
                if (msg.info != "" && msg.info != null) {
                    var allChildren = msg.info;
                    var n = allChildren.length;
                    allChildren[n] = top_name;
                    //obj.options.add(new Option('请选择', '')); 
                    for (var i = 0; i < allChildren.length; i++) {                       
                        obj.options.add(new Option(allChildren[i], allChildren[i]));
                    }

                } else {
                    obj.options.add(new Option(top_name, top_name));

                }
                $("select[name='target_person'] option[value=" + top_name + "]").attr("selected", true);


            } else {

            }

        }); 
        return false;
    });
});
$(function() {
    $('.jimmy-click').on('click', function() {
        var client = $(this).data("client");
        $('#case_client_id').val(client);
        var names = $(this).data("name");
        var top_name = $(this).data("name");
        $('.client-item').each(function() {
            var the = $(this);
            if (the.data('id') == client) {
                the.find('.collapse').collapse('show');
            }
        });
        var obj = $('#target_person')[0];
        $('#target_person').find("option").remove();
        $("#target_person").prepend("<option value='' selected='selected'>请选择</option>");
        $.post(url.getClientRelater, { id: client }, function(msg) {
            if (msg.status == 1) {
                if (msg.info != "" && msg.info != null) {
                    var n = msg.info.length;
                    msg.info[n] = names;
                    //console.log(msg.info);
                    //$('#target_person').val(names);
                    var allChildren = msg.info;
                    //obj.options.length = 1;
                    for (var i = 0; i < allChildren.length; i++) {
                        obj.options.add(new Option(allChildren[i], allChildren[i]));
                    }
                } else {
                    obj.options.add(new Option(names, names));
                }
            } else {
                obj.options.add(new Option(names, names));
            }
         $("select[name='target_person'] option[value=" + top_name + "]").attr("selected", true);
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
