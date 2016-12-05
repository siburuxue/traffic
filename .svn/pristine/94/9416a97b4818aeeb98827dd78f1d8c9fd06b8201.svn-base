// 定义全局变量
var submit;
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 注册其他字段
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
        $('#table-content').load(url.table, data);
    };
    // 初始化
    submit.execute();
    $('#upload').data('end',function(){
        submit.execute();
    });
    //全选
    $(document).on('change','#all',function () {
        $('#table tbody input').prop('checked',$(this).prop('checked'));
    });
    $(document).on('change','#table tbody input',function () {
        $('#all').prop('checked',$('#table tbody input:checked').size() == $('#table tbody input').size());
    });
    //下载文件
    $('#download').on('click',function () {
        if($('#table tbody input[type=checkbox]:checked').size() == 0){
            layer.alert('未选择文件，不能操作',function (index) {
                layer.close(index);
            });
        }else{
            var ids = $('#table tbody input[type=checkbox]:checked').map(function(){ return $(this).val(); }).get().join();
            var case_id = $('#case_id').val();
            window.open(url.downloadFiles + "&case_id=" + case_id + "&ids=" + ids);
        }
    });
});
