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
    var photoTableInit = function(){
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:14});
    }
    //上传图片
    $('#upload').data('end',function(){
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function(){
        if($('#photoTable input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择任何图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $('#photoTable input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                            $('#photoTable input[type=checkbox]:checked').parents('.col-xs-2').remove();
                        });
                    }else{
                        layer.alert(msg.info,function (index) {
                            layer.close(index);
                        });
                    }
                });
            },function(){

            })
        }
    });
    //下载图片
    $('#download').on('click',function(){
        if($('#photoTable input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择任何图片，不能操作',function (index) {
                layer.close(index);
            })
        }else{
            var ids = $('#photoTable input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get().join();
            window.open(url.download + "&ids=" + ids);
        }
    });
    //如果简易事故认定已经被打印 画面上不能修改
    if(is_printed == "1" || is_back == "1"){
        $('textarea,#reset,#submit').prop('disabled',true);
    }
    /**************************************************************************************************/
});
