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
        return;
        data.initial_reason_type = $('input:radio[name="initial_reason_type"]:checked').val();
        data.accident_type = $('input:radio[name="accident_type"]:checked').val();
        $('.value_input').each(function(){
            if($(this).prop('disabled')==false){
                data.initial_reason = $(this).val();
            }
        });       
        data.accident_type = $('input:radio[name="accident_type"]:checked').val();
        //data.accident_type_des = $("select[name='accident_type_des']").val();
        $('.accident_type').each(function(){
            if($(this).prop('disabled')==false){
                data.accident_type_des = $(this).val();
            }
        });   

        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade:0.1,
                    shadeClose:true
                }, function(index) {
                    layer.close(index);
                    //submit.reset();
                    window.location.reload();
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
        window.location.reload();
    });
});
//事故初查原因 相关代码
$(function(){

    //禁用 事故初查原因 所有的输入框 下拉选框
    $('.initial_reason_type_value').attr('disabled','disabled');
    //获取选中状态的事故初查原因 激活选项输入框、下拉选框
    var valid_radio = '';
    $('input:radio[name="initial_reason_type"]').each(function(){
        if($(this).prop('checked')==true){
            valid_radio = $(this).val();
        }
    });

    var valid_select = $('#default_initial_reason').val();
    $('.value_'+valid_radio).attr('disabled',false);
    $('.value_select').each(function(){
        if($(this).prop('disabled')==false){
            var the_select = $(this)[0];
            reason_value_select = $(this).val();
            $('.value_input').each(function(){
                if($(this).prop('disabled')==false){
                    $(this).val(reason_value_select);
                }
            });
        }
    });
    
    $('.initial_reason_type_radio_box').on('change',function(){
        var rank=$(this).val();        
        $('.initial_reason_type_value').attr('disabled','disabled');
        $("select[name='initial_reason'] option[value='']").attr("selected", true); 
        $('.value_input').val('');     
        $('.value_'+rank).attr('disabled',false);
        if(rank==valid_radio){
            $('.value_'+rank).val(valid_select); 

            $("select[name='initial_reason'] option[value="+valid_select+"]").attr("selected", true); 
        }
    });
    $('.value_select').on('change',function(){
        var reason_value = $(this).val();
        $('.value_input').each(function(){
            if($(this).prop('disabled')==false){
                $(this).val(reason_value);
            }
        });
    });
    $('.value_input').on('blur',function(){
        var this_input = $(this);
        var reason_value_input = $(this).val();
        if(reason_value_input!=''){
            $('.value_select').each(function(){
                if($(this).prop('disabled')==false){
                    var the_select = $(this)[0];
                    var verify = 0;
                    for (var i = 0; i < the_select.length; i++) {
                        if (the_select[i].value == reason_value_input) {
                           $("select[name='initial_reason'] option[value="+reason_value_input+"]").attr("selected", true); 
                           verify = 1;
                        }
                    }
                    if(verify==0){
                        var rank = $('input:radio[name="initial_reason_type"]:checked').val();
                        if(rank==valid_radio){ 
                            $('.value_input').each(function(){
                                if($(this).prop('disabled')==false){
                                    $(this).val(valid_select);
                                }
                            });                                            
                            $("select[name='initial_reason'] option[value="+valid_select+"]").attr("selected", true); 

                        }else{
                            $("select[name='initial_reason'] option[value='']").attr("selected", true); 
                            this_input.val('');
                        }
                        layer.alert('请输入有效代码');
                        this_input.focus();
                    }
                }
            });
        }
    });
});

//事故形态 相关代码
$(function(){
    //禁用 事故初查原因 所有的输入框 下拉选框
    $('.accident_type').attr('disabled','disabled');
    //获取选中状态的事故初查原因 激活选项输入框、下拉选框
    var valid_radio = $('input:radio[name="accident_type"]:checked').val();
    var valid_select = $('#default_accident_type_des').val();    
    $('.accident_type_'+valid_radio).attr('disabled',false);
    
    $('input:radio[name="accident_type"]').on('change',function(){
        var rank=$(this).val();
        $('.accident_type').attr('disabled','disabled');
        $("select[name='accident_type_des'] option[value='']").attr("selected", true); 
        $('.accident_type_'+rank).attr('disabled',false);
        if(rank==valid_radio){
            $("select[name='accident_type_des'] option[value="+valid_select+"]").attr("selected", true);
        }
    });
});


$(function() {

    $('input').attr('disabled', 'disabled');
    $('select').each(function() {
        $(this).attr('disabled', 'disabled');      
    });
});

$(function(){
     $('input[type=radio]').hide();
});