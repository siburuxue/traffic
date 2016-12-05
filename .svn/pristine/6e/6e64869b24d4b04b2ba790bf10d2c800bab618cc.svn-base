// 定义全局变量
var submit;
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $('#table-content').load(url.table, data,function(){
            $('.page-num,.view,.edit,.view-all').data('end',function () {
                submit.execute();
            });
        });
    };
    // 初始化
    submit.execute();
    //删除
    $(document).on('click','.del',function () {
        var $this = $(this);
        layer.confirm('确定执行该操作？',function(index){
            layer.close(index);
            var data = {
                id:$this.parents('tr').find('td:last input').val()
            }
            $.post(url.delete,data,function(msg){
                if(msg.status == 1){
                    layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                        submit.execute();
                    });
                }else{
                    layer.alert(msg.info,function (index) {
                        layer.close(index);
                    });
                }
            });
        },function () {
            
        });

    });
    $('#add-folder').data('end',function () {
        submit.execute();
    });
});
