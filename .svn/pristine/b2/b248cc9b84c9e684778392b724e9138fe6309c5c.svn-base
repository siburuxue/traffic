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
});
