$(function() {
    // 不能出现无当事人的情况，否则报错
    // 当前当事人的编号
    var nowCaseReviewApplyId = $('input[name="now_case_review_apply_id"]').val();
    nowCaseReviewApplyId = nowCaseReviewApplyId == 0 ? $('.client-item:first').data('id') : nowCaseReviewApplyId;
    // 获取当前申请人信息
    var getApplyer = function(applyId) {
        // 更新当前申请人编号
        nowCaseReviewApplyId = applyId;
        $('input[name="case_review_apply_id"]').val(applyId);
        // 当事人界面状态
        $('.client-item').each(function() {
            var the = $(this);
            if (the.data('id') == applyId) {
                the.find('.collapse').collapse('show');
            }
        });
        // 获取指定当事人相关的申请人的列表
        $.get(url.getApplyer, {
            case_review_apply_id: applyId
        }, function(msg) {
            if (msg.status == 1) {
                // 更新申请人列表
                var notice = msg.info.notice;
                var applyer = msg.info.applyer;
                if ($.isEmptyObject(notice)) {
                    $('input[name="id"]').val(0);
                    $('input[name="case_client_name"]').val(applyer.case_client_name);
                    $('input[name="case_client_relater_name"]').val(applyer.case_client_relater_id == 0 ? applyer.case_client_name : applyer.case_client_relater_name);
                    $('input[name="relation"]').val(applyer.relation);
                    $('input[name="contact"]').val(applyer.case_client_relater_id == 0 ? applyer.case_client_tel : applyer.case_client_relater_tel);
                    $('input[name="accident_name"]').val('');
                    $('textarea[name="content"]').val('');
                    $('#print').prop('disabled', true);
                } else {
                    $('input[name="id"]').val(notice.id);
                    $('input[name="case_client_name"]').val(notice.case_client_name);
                    $('input[name="case_client_relater_name"]').val(notice.case_client_relater_name);
                    $('input[name="relation"]').val(notice.relation);
                    $('input[name="contact"]').val(notice.contact);
                    $('input[name="accident_name"]').val(notice.accident_name);
                    $('textarea[name="content"]').val(notice.content);
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
                    getApplyer(nowCaseReviewApplyId);
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
        getApplyer(nowCaseReviewApplyId);
    });
    // 初始化 
    getApplyer(nowCaseReviewApplyId);
});
