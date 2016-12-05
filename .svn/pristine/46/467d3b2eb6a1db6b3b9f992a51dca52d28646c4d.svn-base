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
				layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
					window.location.reload();
				});
            } else {
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
    // 注册提交字段
     submit.reg({
		group: 'gather',
		name: "clientList",
		get: function(name) {
			var list = $('#table1 tbody tr').map(function(){
				var data = {};
				$(this).find('input').each(function(){
					data[$(this).attr('name')] = $(this).val();
				});
				return data;
			}).get();
			var para = $('#table2 > tbody > tr').get().map(function(v,k){
				var data = {};
				$(v).find('input[type=checkbox]').each(function(){
					data[$(this).attr('name')] = $(this).prop('checked') ? 1 : 0;
				});
				$.each(list[k],function(key,value){
					data[key] = value;
				});
				data['case_id'] = $('#case_id').val();
				return data;
			});
			return para;
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
        window.location.reload();
    });
    // 创建日期拾取器
    
    
    var photoTableInit = function(){
        var case_id  = $('#case_id').val();
        $('#photoTable').parent().load(url.photoList,{case_id:case_id,id:0});
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
            layer.alert('未选择图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $('#photoTable input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                            $('#photoTable input[type=checkbox]:checked').parents('.col-xs-2').remove();
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
    //下载图片
    $('#download').on('click',function(){
        if($('#photoTable input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择任何图片，不能操作',function (index) {
                layer.close(index);
            });
        }else{
            var ids = $('input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get().join();
            window.open(url.download + "&ids=" + ids);
        }
    });
});
