$(function() {
    // 加载报警记录列表
    var loadAlarmInfo = function() {
        $('#alarm-topn-content').load(url.alarmTopN, {
            case_id: $('input[name="case_id"]').val()
        }, function(response, status, xhr) {
            $('#alarm-topn-content').find('.js-end-refresh').data('end', function() {
                loadAlarmInfo();
            });
        });
    };
    // 当报警记录管理窗口关闭
    $('#alarm-manage').data('end', function() {
        loadAlarmInfo();
    });
    /****************************************************************************************************/
    // 加载受案登记信息
    var loadAcceptInfo = function() {
        $.post(url.getAcceptInfo, {
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            $('#accept-manage').attr('href', msg.url);
            if (msg.status == 1) {
                var html = '<a href="' + msg.url + '" class="list-group-item js-open js-end-refresh">案由：' + msg.info['reason'] + '</a>';
                $('#accept-topn-content').html(html);
                $('#accept-topn-content').find('.js-end-refresh').data('end', function() {
                    loadAcceptInfo();
                    loadCaseInfo();
                });
            } else {
                $('#accept-topn-content').html('');
            }
        });
    };
    // 当受案登记管理窗口关闭
    $('#accept-manage').data('end', function() {
        loadAcceptInfo();
        loadCaseInfo();
    });
    /****************************************************************************************************/
    // 加载询问笔录列表
    var loadRecordInfo = function() {
        $('#record-topn-content').load(url.recordTopN, {
            case_id: $('input[name="case_id"]').val()
        }, function(response, status, xhr) {
            $('#record-topn-content').find('.js-end-refresh').data('end', function() {
                loadRecordInfo();
            });
        });
    };
    // 当报警询问笔录窗口关闭
    $('#record-manage').data('end', function() {
        loadRecordInfo();
        loadCaseInfo();
    });
    /****************************************************************************************************/
    // 加载案件信息
    var loadCaseInfo = function() {
        $.post(url.getCaseInfo, {
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            if (msg.status == 1) {
                var acceptStatus = msg.info['accept_status'] == 1 ? true : false;
                $('#accept-status').toggleClass('btn-success', acceptStatus).toggleClass('btn-default', !acceptStatus);
                var surveyStatus = msg.info['survey_status'] == 1 ? true : false;
                $('#survey-status').toggleClass('btn-success', surveyStatus).toggleClass('btn-default', !surveyStatus);
                var clientStatus = msg.info['client_status'] == 1 ? true : false;
                $('#client-status').toggleClass('btn-success', clientStatus).toggleClass('btn-default', !clientStatus);
                var recordStatus = msg.info['record_status'] == 1 ? true : false;
                $('#record-status').toggleClass('btn-success', recordStatus).toggleClass('btn-default', !recordStatus);
                var checkupStatus = msg.info['checkup_status'] == 1 ? true : false;
                $('#checkup-status').toggleClass('btn-success', checkupStatus).toggleClass('btn-default', !checkupStatus);
                var lawDocStatus = msg.info['law_doc_status'] == 1 ? true : false;
                $('#law-doc-status').toggleClass('btn-success', lawDocStatus).toggleClass('btn-default', !lawDocStatus);
                var cognizanceStatus = msg.info['cognizance_status'] == 1 ? true : false;
                $('#cognizance-status').toggleClass('btn-success', cognizanceStatus).toggleClass('btn-default', !cognizanceStatus);
                var punishStatus = msg.info['punish_status'] == 1 ? true : false;
                $('#punish-status').toggleClass('btn-success', punishStatus).toggleClass('btn-default', !punishStatus);
                var mediateStatus = msg.info['mediate_status'] == 1 ? true : false;
                $('#mediate-status').toggleClass('btn-success', mediateStatus).toggleClass('btn-default', !mediateStatus);
                var catalogStatus = msg.info['catalog_status'] == 1 ? true : false;
                $('#catalog-status').toggleClass('btn-success', catalogStatus).toggleClass('btn-default', !catalogStatus);
            } else {
                layer.alert(msg.info);
            }
        });
    };
    /****************************************************************************************************/
    // 初始化
    // 加载案件信息
    loadCaseInfo();
    // 加载报警记录列表
    loadAlarmInfo();
    // 加载受案登记信息
    loadAcceptInfo();
    // 加载询问笔录列表
    loadRecordInfo();
});
