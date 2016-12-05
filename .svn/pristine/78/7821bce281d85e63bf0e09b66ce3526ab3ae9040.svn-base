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

    // 特殊字段手工注册
    submit.reg({
        group: 'gather',
        name: 'code',
        get: function(name) {
            var data = new Array();
            $("input[name='code']").each(function() {
                data.push($(this).val());
            });
            return data;
        },
        set: function(name, value, data) {
            $("input[name='code']").each(function() {
                //var val = $(this).val();
                //$(this).val(val);
            });
        }
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续新增'],
                    closeBtn: 0,
                }, function(index) {
                    layer.close(index);
                    
                    window.location.reload(true);
                    submit.reset();
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                    //window.location.reload(true);
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
    	window.location.reload(true);
        submit.reset();
    });
});

// 时间拾取
$(function() {
    // 创建日期拾取器
    
    
});


// 扫码读取采血管组别等相关信息
$(function() {
    $('#scan-bloodtube_1').on('change', function() {
        //获取部门id
        var scan_code = $(this).val();

        $.post(url.ajaxBloodtube, { 'code': scan_code }, function(msg) {

            //console.log(msg);
            if (msg.status == 1) {
                var bloodData = msg.info;
                if (bloodData == "" || bloodData == null) {
                    layer.alert('无可采血管数据', function(index) {
                        layer.close(index);
                    });
                }else{
                   $('#scan-bloodtube_1').val(scan_code);
                   if(bloodData[0].code != scan_code){
                     $('#scan-bloodtube_2').val(bloodData[0].code);
                  	  var scan_code_2 = bloodData[0].code; 
                   }else{
                     $('#scan-bloodtube_2').val(bloodData[1].code);
                      var scan_code_2 = bloodData[1].code;

                   }
                   $('#bloodtubeCate_id').val(bloodData[0].id);
                   layer.alert('该组采血管编码为:<br/><font style="color:red;">'+scan_code+"</font><br/>"+scan_code_2+"<br/>请仔细核对!", function(index) {
                        layer.close(index);
                    });

                }

            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });

    });
});
