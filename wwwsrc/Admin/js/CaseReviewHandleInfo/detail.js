$(function() {
    var loadInfo = function() {
        $.get(url.getDetailInfo, function(msg) {
            if (msg.status == 1) {
                renderStep(msg.info.caseReview);
                renderApply(msg.info.caseReview);
                renderAccept(msg.info.caseReview);
                renderResult(msg.info.caseReview);
                renderOther(msg.info);
            } else {
                layer.alert(msg.info);
            }
        });
    };
    var renderStep = function(data) {
        var dom = $('#case-review-step');
        dom.find('button[name="apply_status"]').toggleClass('btn-success', data.apply_status == 1);
        dom.find('button[name="accept_status"]').toggleClass('btn-success', data.accept_status != 0);
        dom.find('button[name="over_status"]').toggleClass('btn-success', data.check_status != 0 || data.stop_status != 0);
    };
    var renderApply = function(data) {
        if (data.apply_status == 1) {
            $('#caseReviewApplyExist').show();
            $('#caseReviewApplyEmpty').hide();
        } else {
            $('#caseReviewApplyExist').hide();
            $('#caseReviewApplyEmpty').show();
        }
    };
    var renderAccept = function(data) {
        var dom = $('#caseReviewAcceptContent');
        dom.find('.list-group-item').hide();
        if (data.accept_status == 0) {
            dom.find('#caseReviewAccept_0').show();
        } else if (data.accept_status == 1) {
            dom.find('#caseReviewAccept_1').show();
        } else if (data.accept_status == 2) {
            dom.find('#caseReviewAccept_2').show();
        }
    };
    var renderResult = function(data) {
        var dom = $('#caseReviewResultContent');
        dom.find('.list-group-item').hide();
        if (data.check_status == 0 && data.stop_status == 0) {
            dom.find('#caseReviewResult_0').show();
        } else if (data.stop_status == 1) {
            dom.find('#caseReviewResult_1').show();
        } else if (data.check_status == 1) {
            dom.find('#caseReviewResult_2').show();
        }
    };
    var renderOther = function (data) {
         if($.isEmptyObject(data.caseReply)){
            $('#replyContent').text('无');
         }else{
            $('#replyContent').text(data.caseReply.content);
         }
         if($.isEmptyObject(data.caseDirective)){
            $('#directiveContent').text('无');
         }else{
            $('#directiveContent').text(data.caseDirective.content);
         }
         if($.isEmptyObject(data.caseDiscuss)){
            $('#discussContent').text('无');
         }else{
            $('#discussContent').text(data.caseDiscuss.discuss_time + '集体研究');
         }
    };
    loadInfo();
    $('.js-end-refresh').data('end', loadInfo);
});
