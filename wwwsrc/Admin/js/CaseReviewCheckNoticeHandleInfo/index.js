$(function() {
    /************************************************************************************************************/
    // 不能出现无当事人的情况，否则报错
    // 当前当事人的编号
    var nowCaseClientId = $('input[name="now_case_client_id"]').val();
    nowCaseClientId = nowCaseClientId == 0 ? $('.client-item:first').data('id') : nowCaseClientId;
    /************************************************************************************************************/
    // 申请人
    var applyer = {};
    var notice = {};
    // 获取案件当事人相关的全部申请人
    var getApplyer = function(clientId) {
        // 更新当前当事人编号
        nowCaseClientId = clientId;
        // 当事人界面状态
        $('.client-item').each(function() {
            var the = $(this);
            if (the.data('id') == clientId) {
                the.find('.collapse').collapse('show');
                $('input[name="case_client_id"]').val(the.data('id'));
                $('input[name="case_client_name"]').val(the.data('name'));
            }
        });
        // 申请人dom对象
        var dom = $('select[name="case_client_relater_id"]')[0];
        // 获取指定当事人相关的申请人的列表
        $.get(url.getApplyer, {
            case_client_id: clientId,
            case_review_id: $('input[name="case_review_id"]').val(),
            case_review_check_id: $('input[name="case_review_check_id"]').val()
        }, function(msg) {
            if (msg.status == 1) {
                // 更新申请人列表
                applyer = msg.info.applyer;
                // 更新已保存的消息记录
                notice = msg.info.notice;
                // 重置申请人DOM对象
                dom.options.length = 1;
                $.each(applyer, function(i, n) {
                    dom.options.add(new Option(n.name, n.id));
                });
                if ($.isEmptyObject(notice)) {
                    $('input[name="id"]').val(0);
                    $('input[name="code"]').val('');
                    $('input[name="post_time"]').val('');
                    $('input[name="post_place"]').val('');
                    $('input[name="post_method"]').val('');
                    $('select[name="case_client_relater_id"]').val('');
                    $('input[name="receiver"]').val('');
                    $('input[name="receiver_reason"]').val('');
                    $('input[name="receiver_relation"]').val('');
                    $('input[name="rejection_reason"]').val('');
                    $('input[name="witness"]').val('');
                    $('input[name="sender"]').val('');
                    $('input[name="remark"]').val('');
                    $('#print').prop('disabled', true);
                } else {
                    $('input[name="id"]').val(notice.id);
                    $('input[name="code"]').val(notice.code);
                    $('input[name="post_time"]').val(notice.post_time);
                    $('input[name="post_place"]').val(notice.post_place);
                    $('input[name="post_method"]').val(notice.post_method);
                    $('select[name="case_client_relater_id"]').val(notice.case_client_relater_id);
                    $('input[name="receiver"]').val(notice.receiver);
                    $('input[name="receiver_reason"]').val(notice.receiver_reason);
                    $('input[name="receiver_relation"]').val(notice.receiver_relation);
                    $('input[name="rejection_reason"]').val(notice.rejection_reason);
                    $('input[name="witness"]').val(notice.witness);
                    $('input[name="sender"]').val(notice.sender);
                    $('input[name="remark"]').val(notice.remark);
                    $('#print').prop('disabled', false);
                }
            } else {
                alert(msg.info);
            }
        });
    };
    // 点击当事人事件
    $('.client-item').on('click', function() {
        getApplyer($(this).data('id'));
    });
    // 选择申请人
    $('select[name="case_client_relater_id"]').on('change', function() {
        var id = $(this).val();
        if (!$.isEmptyObject(notice) && id == notice.case_client_relater_id) {
            $('#print').prop('disabled', false);
        } else {
            $('#print').prop('disabled', true);
        }
    });
    /************************************************************************************************************/
    // 实例化submit对象
    var submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    getApplyer(nowCaseClientId);
                });
            } else {
                layer.alert(msg.info);
            }
        });
    };
    // 批量注册字段
    $('.auto-gather').each(function() {
        var the = $(this);
        submit.reg({
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    /************************************************************************************************************/
    // 保存
    $('#submit').on('click', function() {
        submit.execute();
    });
    // 打印
    $('#print').on('click', function() {
        alert('打印');
    });
    // 重置
    $('#reset').on('click', function() {
        getApplyer(nowCaseClientId);
    });
    /************************************************************************************************************/
    // 初始化 
    getApplyer(nowCaseClientId);
    /************************************************************************************************************/
});
