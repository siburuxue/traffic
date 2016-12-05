// 页面加载完毕
$(function() {
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
            $('#punish_warning_time').val(info['punish_warning_time']);
            //是否罚款
            $('#punish_is_fine').prop('checked',info['punish_is_fine'] == "1");
            //罚款时间
            $('#punish_fine_time').val(info['punish_fine_time']);
            //罚款金额
            $('#punish_fine_money').val(info['punish_fine_money']);
            //扣分
            $('#punish_fine_score').val(info['punish_fine_score']);
            //是否暂扣
            $('#punish_is_seize').prop('checked',info['punish_is_seize'] == "1");
            //暂扣时间
            $('#punish_seize_time').val(info['punish_seize_time']);
            //暂扣时长
            $('#punish_seize_date').val(info['punish_seize_date']);
            //是否吊销
            $('#punish_is_revoke').prop('checked',info['punish_is_revoke'] == "1");
            //执行时间
            $('#punish_revoke_time').val(info['punish_revoke_time']);
            //吊销时长
            $('#punish_revoke_date').val(info['punish_revoke_date']);
            //是否拘留
            $('#punish_is_detain').prop('checked',info['punish_is_detain'] == "1");
            //拘留时间
            $('#punish_detain_time').val(info['punish_detain_time']);
            //拘留天数
            $('#punish_detain_date').val(info['punish_detain_date']);
            //立案时间
            $('#criminal_time').val(info['criminal_time']);
            //案件类别
            $('#criminal_case_type').val(info['criminal_case_type'] || '');
            //是否刑事拘留
            $('#criminal_is_detain').prop('checked',info['criminal_is_detain'] == "1");
            //刑事拘留时间
            $('#criminal_detain_time').val(info['criminal_detain_time']);
            //是否逮捕
            $('#criminal_is_arrest').prop('checked',info['criminal_is_arrest'] == "1");
            //逮捕时间
            $('#criminal_arrest_time').val(info['criminal_arrest_time']);
            //是否取保候审
            $('#criminal_is_remand').prop('checked',info['criminal_is_remand'] == "1");
            //取保候审时间
            $('#criminal_remand_time').val(info['criminal_remand_time']);
            //是否移送起诉
            $('#criminal_is_sue').prop('checked',info['criminal_is_sue'] == "1");
            //移送起诉时间
            $('#criminal_sue_time').val(info['criminal_sue_time']);
            //当事人ID
            $('#case_client_id').val(clientId);
            //隐藏按钮
            if(info['id'] === ''){
                $('.page-num').attr('href','javascript:;').removeClass('js-open').css('color','#c9c9c9');
            }else{
                $('.page-num').attr('href',function(){ return $(this).data('href') + info['id']}).addClass('js-open').css('color','#337ab7');
            }
            //画面主键
            $('#id').val(info['id']);
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
    $('#case-photo-upload,#case-photo-delete').remove();
    //画面加载获取当事人处罚信息
    if($('#id').val() == ''){
        $('#user-list .client-list-head:eq(0)').click();
    }else{
        $('#user-list .client-list-head[id=' + $('#id').val() + ']').click();
    }
    /**************************************************************************************************/
});
