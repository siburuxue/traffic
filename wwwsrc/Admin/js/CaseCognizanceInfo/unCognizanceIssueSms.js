// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.save, data, function(msg) {
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
    //注册当事人列表
    submit.reg({
        group: 'gather',
        name: "list",
        get: function(name) {
            return $('input[type=checkbox]:checked').map(function(){
                return {
                    case_client_name:$(this).parent().text().trim(),
                    case_client_mobile:$(this).val()
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
        $('input[type=checkbox]').prop('checked',false);
        $('textarea').val('');
    });
    /**************************************************************************************************/
});
