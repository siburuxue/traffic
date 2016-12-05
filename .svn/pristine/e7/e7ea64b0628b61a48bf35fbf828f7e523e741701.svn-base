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
            if(msg.status == 1){
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
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    
    
    /*上传图片*/
    var photoTableInit = function(obj){
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        var cate = obj.parents('.photo-box').attr('id');
        obj.parents('.photo-box').find('.photoTable').parent().load(url.photoList,{case_id:case_id,id:id,cate:cate});
    }
    //上传图片
    $('.upload').data('end',function(){
        $(this).parents('.photo-box').find('.photoTable .col-xs-2').remove();
        photoTableInit($(this));
    });
    $('.upload-all').data('end',function(){
        $('#list-all .photo-box .photoTable .col-xs-2').remove();
        var case_id  = $('#case_id').val();
        var id = $('#id').val();
        $('#list-all .photoTable').map(function(){
            var cate = $(this).parent().data('cate');
            $(this).parent().load(url.photoList,{case_id:case_id,id:id,cate:cate});
        });
    });
    //下载图片
    $('.download').on('click',function(){
        if($(this).parents('.form-inline').find('input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择任何图片，不能操作！',function (index) {
                layer.close(index);
            });
        }else{
            var ids = $(this).parents('.form-inline').find('input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get().join();
            window.open(url.download + "&ids=" + ids);
        }
    });
    //删除图片
    $('.delete').click(function(){
        var $this = $(this);
        if($this.parents('.photo-box').find('.photoTable input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择任何图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $this.parents('.photo-box').find('.photoTable input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                            $this.parents('.photo-box').find('.photoTable input[type=checkbox]:checked').parents('.col-xs-2').remove();
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
    /*展开、收起*/
    $('a[role=button]').on('click', function () {
        if($(this).find('span').hasClass('glyphicon-menu-up')){
            $(this).find('span').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
        }else{
            $(this).find('span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
        }
    });
});
