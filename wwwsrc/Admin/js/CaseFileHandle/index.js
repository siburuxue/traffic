// 定义全局变量
var submit;
// 页面加载完毕
$(function() {
    /**************************************************************************************************/
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
        $.post(url.save, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true}, function() {
                    window.location.reload();
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    var data = {case_id:$('#case_id').val()};
    $('#make').on('click',function () {
        $.post(url.make,data,function (msg) {
            var i = 0;
            $('#content').val('').val(function () {
                console.log(msg.info);
                return msg.info.map(function(x){ return ++i + '. ' + x['content'] + (i == msg.info.length ? "" : "\n"); }).join('');
            });
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
    var photoTableInit = function(){
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:60});
    }
    //上传图片
    $('#upload').data('end',function(){
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    photoTableInit();
    //删除图片
    $('#delete').click(function(){
        if($('#photoTable input:checked').size() == 0){
            layer.alert('未选择图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $('#photoTable input:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                            $('#photoTable input:checked').parents('.col-xs-2').remove();
                        });
                    }else{
                        layer.alert(msg.info,function(index){
                            layer.close(index);
                        });
                    }
                });
            },function(){

            });
        }
    });
});
