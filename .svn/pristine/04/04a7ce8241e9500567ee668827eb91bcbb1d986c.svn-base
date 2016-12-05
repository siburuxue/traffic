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
    $('.post-gather-checkbox').each(function() {
        var the = $(this);
        submit.reg({
            group: 'gather',
            name: the.attr('name'),
            get: function(name) {
                return the.prop('checked') ? 1 : 0;
            },
            set: function(name, value, data) {

            }
        });
    });
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.save, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function () {
                    var client_id = $('#case_client_id').val();
                    $('#user-list .client-list-head[id=' + client_id + ']').click();
                });
            }else{
                layer.alert(msg.info,function (index) {
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
        //清空画面
        $('input[type=checkbox]').prop('checked',false);
        $('input[type=number],input[type=text]').prop('disabled',true);
        $('select').val('');
        $('textarea').val('');
        $('.page-num').text(0);
        //获取信息
        if($('#id').val() == ''){
            $('#user-list .client-list-head:eq(0)').click();
        }else{
            $('#user-list .client-list-head[id=' + $('#id').val() + ']').click();
        }
    });
    /**************************************************************************************************/
    //复选框控制事件
    $('input[type=checkbox]').on('change',function(){
        $(this).parents('tr').find('input[type=number],input[type=text],select').prop('disabled',!$(this).prop('checked')).val('');
    });
    //点击当事人获取处罚信息
    $('#user-list .client-list-head').on('click',function(){
        var $this = $(this);
        var clientId = $(this).attr('id');
        var case_id = $('#case_id').val();
        var data = {
            clientId:clientId,
            case_id:case_id
        }
        $.post(url.getClientPunishInfo,data,function(info){
            //展开当前当事人列表
            $this.find('.collapse').collapse('show');
            //处罚意见
            $('#opinion').val(info['opinion']);
            //违法行为
            $('#illegal').val(info['illegal']);
            //是否警告
            $('#punish_is_warning').prop('checked',info['punish_is_warning'] == "1");
            //警告时间
            $('#punish_warning_time').val(info['punish_warning_time']).prop('disabled',info['punish_is_warning'] != "1");
            //是否罚款
            $('#punish_is_fine').prop('checked',info['punish_is_fine'] == "1");
            //罚款时间
            $('#punish_fine_time').val(info['punish_fine_time']).prop('disabled',info['punish_is_fine'] != "1");
            //罚款金额
            $('#punish_fine_money').val(info['punish_fine_money']).prop('disabled',info['punish_is_fine'] != "1");
            //扣分
            $('#punish_fine_score').val(info['punish_fine_score']).prop('disabled',info['punish_is_fine'] != "1");
            //是否暂扣
            $('#punish_is_seize').prop('checked',info['punish_is_seize'] == "1");
            //暂扣时间
            $('#punish_seize_time').val(info['punish_seize_time']).prop('disabled',info['punish_is_seize'] != "1");
            //暂扣时长
            $('#punish_seize_date').val(info['punish_seize_date']).prop('disabled',info['punish_is_seize'] != "1");
            //是否吊销
            $('#punish_is_revoke').prop('checked',info['punish_is_revoke'] == "1");
            //执行时间
            $('#punish_revoke_time').val(info['punish_revoke_time']).prop('disabled',info['punish_is_revoke'] != "1");
            //吊销时长
            $('#punish_revoke_date').val(info['punish_revoke_date']).prop('disabled',info['punish_is_revoke'] != "1");
            //是否拘留
            $('#punish_is_detain').prop('checked',info['punish_is_detain'] == "1");
            //拘留时间
            $('#punish_detain_time').val(info['punish_detain_time']).prop('disabled',info['punish_is_detain'] != "1");
            //拘留天数
            $('#punish_detain_date').val(info['punish_detain_date']).prop('disabled',info['punish_is_detain'] != "1");
            //立案时间
            $('#criminal_time').val(info['criminal_time']);
            //案件类别
            $('#criminal_case_type').val(info['criminal_case_type'] || '');
            //是否刑事拘留
            $('#criminal_is_detain').prop('checked',info['criminal_is_detain'] == "1");
            //刑事拘留时间
            $('#criminal_detain_time').val(info['criminal_detain_time']).prop('disabled',info['criminal_is_detain'] != "1");
            //是否逮捕
            $('#criminal_is_arrest').prop('checked',info['criminal_is_arrest'] == "1");
            //逮捕时间
            $('#criminal_arrest_time').val(info['criminal_arrest_time']).prop('disabled',info['criminal_is_arrest'] != "1");
            //是否取保候审
            $('#criminal_is_remand').prop('checked',info['criminal_is_remand'] == "1");
            //取保候审时间
            $('#criminal_remand_time').val(info['criminal_remand_time']).prop('disabled',info['criminal_is_remand'] != "1");
            //是否移送起诉
            $('#criminal_is_sue').prop('checked',info['criminal_is_sue'] == "1");
            //移送起诉时间
            $('#criminal_sue_time').val(info['criminal_sue_time']).prop('disabled',info['criminal_is_sue'] != "1");
            //当事人ID
            $('#case_client_id').val(clientId);
            //画面主键
            $('#id').val(info['id']);
            //隐藏按钮
            if(info['id'] === ''){
                $('#upload,#delete,#download').hide();
                $('.page-num').attr('href','javascript:;').removeClass('js-open').css('color','#c9c9c9');
            }else{
                $('#upload,#delete,#download').show();
                $('.page-num').attr('href',function(){ return $(this).data('href') + info['id']}).addClass('js-open').css('color','#337ab7');
                $('#upload').attr('href',function(){ return $(this).attr('href') + info['id']});
            }
            //行政处罚决定书
            $('#punish-decision').text(info['count25']);
            //行政拘留回执页数
            $('#detention-receipt').text(info['count26'])
            //刑事拘留文书页数
            $('#criminal-custody').text(info['count27'])
            //逮捕文书页数
            $('#arrest').text(info['count28'])
            //取保候审文书页数
            $('#bail').text(info['count29'])
            //移送起诉文书页数
            $('#referral-prosecution').text(info['count30'])
        },'json');
    });
    //画面加载获取当事人处罚信息
    if($('#id').val() == ''){
        $('#user-list .client-list-head:eq(0)').click();
    }else{
        $('#user-list .client-list-head[id=' + $('#id').val() + ']').click();
    }
    //传图片关闭时获取图片页数
    $('.page-num').data('end',function(){
        var $this = $(this);
        var cate = $(this).data('cate');
        var id = $('#id').val();
        var case_id = $('#case_id').val();
        var data = {
            cate:cate,
            id:id,
            case_id:case_id
        };
        $.post(url.getCount,data,function(data){
            $this.text(data['info'])
        },'json');
    })
    /**************************************************************************************************/
});
