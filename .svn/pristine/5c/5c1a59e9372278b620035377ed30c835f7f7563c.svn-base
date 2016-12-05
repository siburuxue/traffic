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
        $.post(url.update, data, function(msg) {
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
    submit.reg({
        group: 'gather',
        name: "law",
        get: function(name) {
            return $('#info1 tr').map(function(){
                return {
                    "law_pid":$(this).find('select[name=law_pid]').val(),
                    "law_id":$(this).find('select[name=law_id]').val()
                };
            }).get();
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
        $('#info1 tr').remove();
        submit.reset();
    });
    //违法行为及条款 添加
    $('#add1').on('click',function(){
        $('#info1').append($('#tempalte1').html());
    });
    $(document).on('click','.del1',function(){
        $(this).parents('tr').remove();
    });
    /**************************************************************************************************/
    // 创建日期拾取器
    
    
    //大类联动小类
    $(document).on('change','select[name="law_pid"]',function(){
        var $this = $(this);
        var pid = $(this).val();
        if(pid == ""){
            $(this).parent().next().find('select option:gt(0)').remove();
        }else{
            $.post(url.getInfo,{pid:pid,case_id:$('#case_id').val()},function(data){
                $this.parent().next().find('select option:gt(0)').remove();
                $this.parent().next().find('select').append(function(){
                    var str = '';
                    for(var i = 0;i < data.length;i++){
                        str += "<option value=" + data[i]['id'] + ">" + data[i]['title'] + "</option>";
                    }
                    return str;
                });
            },"json");
        }
    });
    
    
    /**************************************************************************************************/
});
