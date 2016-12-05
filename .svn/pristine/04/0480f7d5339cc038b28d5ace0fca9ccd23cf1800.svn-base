$(function() {
    // 案件编号
    var caseId = $('input[name="case_id"]').val();
    /****************************************************************************************************/
    var getCaseInfo = function() {
        $.post(url.getCaseInfo, {
            case_id: caseId
        }, function(msg) {
            if (msg.status == 1) {
                var data = msg.info;
                renderCaseInfo(data.case);
                renderCaseStatus(data.case);
                renderAlarmList(data.alarm);
                renderAccept(data.accept);
                renderSurvey(data.survey, data.surveyStatus);
                renderClient(data.client);
                renderRecord(data.record);
                renderCheckup(data.checkup);
                renderPunish(data.punish);
                renderMediate(data.mediate, data.client);
                renderCognizance(data.cognizance);
                renderCaseStatusText(data.statusText);
                renderCaseDirective(data.directive);
                renderCaseDiscuss(data.discuss);
                renderCaseReview(data.caseReview);
            } else {
                layer.alert(msg.info);
            }
        });
    };
    /****************************************************************************************************/
    // 渲染复核
    var renderCaseReview = function(caseReview) {
        var dom = $('#caseReviewDetail');
        if ($.isEmptyObject(caseReview)) {
            dom.hide();
        } else {
            dom.attr('href', dom.attr('href').replace('_case_review_id_', caseReview.id));
            dom.show();
        }
    };
    /****************************************************************************************************/
    // 渲染案件基础信息
    var renderCaseInfo = function(caseInfo) {
        var caseInfoBox = $('#caseInfo');
        caseInfoBox.find('input[name="code"]').val(caseInfo.code);
        caseInfoBox.find('input[name="accident_time"]').val(caseInfo.accident_time);
        caseInfoBox.find('input[name="accident_place"]').val(caseInfo.accident_place);
        caseInfoBox.find('select[name="accident_type"]').val(caseInfo.accident_type);
        caseInfoBox.find('input[name="death_num"]').val(caseInfo.death_num);
        caseInfoBox.find('input[name="hurt_num"]').val(caseInfo.hurt_num);
        caseInfoBox.find('select[name="property_loss"]').val(caseInfo.property_loss);
        caseInfoBox.find('input[name="first_cognizance"]').each(function() {
            var the = $(this);
            the.prop('checked', $.inArray(the.attr('value'), caseInfo.first_cognizance) >= 0);
        });
    };
    /****************************************************************************************************/
    // 渲染步骤按钮
    var renderCaseStatus = function(caseInfo) {
        var caseStatusBox = $('#caseStatus');
        caseStatusBox.find('button').each(function() {
            var the = $(this);
            the.toggleClass('btn-success', caseInfo[the.attr('name')] == 1);
        });
    };
    /****************************************************************************************************/
    // 渲染报警记录
    var renderAlarmList = function(alarm) {
        var html = '';
        if ($.isEmptyObject(alarm)) {
            html = '<div class="list-group-item">暂未关联报警信息</div>';
        } else {
            $.each(alarm, function(i, data) {
                var link = url.caseAlarmHandleDetail.replace('_alarm_id_', data.alarm_id);
                html += '<a href="' + link + '" class="list-group-item js-open">';
                html += '报警人：' + data.alarm_alarm_name + '&nbsp;&nbsp;&nbsp;&nbsp;时间：' + data.alarm_alarm_time + '</a>';
            });
        }
        $('#caseAlarmList').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 渲染受案登记
    var renderAccept = function(accept) {
        if ($.isEmptyObject(accept)) {
            $('#caseAccept').attr('href', url.caseAcceptHandleAdd);
            $('#caseAcceptBtn').text('创建受案登记');
            $('#caseAcceptContent').hide();
        } else {
            var link = url.caseAcceptHandleEdit.replace('_accept_id_', accept.id);
            $('#caseAccept').attr('href', link);
            $('#caseAcceptBtn').text('编辑受案登记');
            $('#caseAcceptContent').text('案由：' + accept.reason).attr('href', link).show();
        }
    };
    /****************************************************************************************************/
    // 渲染现场勘察信息
    var renderSurvey = function(survey, status) {
        if ($.isEmptyObject(survey)) {
            $('#caseSurvey').find('.list-group-item').each(function() {
                var the = $(this);
                the.attr('href', the.data('href').replace('_survey_id_', ''));
                the.toggleClass('list-group-item-info', false);
            });
        } else {
            $('#caseSurvey').find('.list-group-item').each(function() {
                var the = $(this);
                the.attr('href', the.data('href').replace('_survey_id_', survey.id));
                the.toggleClass('list-group-item-info', status[the.index()] != 0);
            });
        }
    };
    /****************************************************************************************************/
    // 渲染当事人
    var renderClient = function(client) {
        var html = '';
        if ($.isEmptyObject(client)) {
            html = '<div class="list-group-item">暂无当事人信息</div>';
        } else {
            $.each(client, function(i, data) {
                var link = url.caseClientEdit.replace('_client_id_', data.id);
                html += '<a href="' + link + '" class="list-group-item js-open">';
                html += data.name + '&nbsp;&nbsp;' + data.detain_car_status;
                html += '&nbsp;&nbsp;' + data.detain_licence_status + '&nbsp;&nbsp;' + data.escape_status + '</a>';
            });
        }
        $('#caseClientList').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 渲染笔录
    var renderRecord = function(record) {
        var html = '';
        if ($.isEmptyObject(record)) {
            html = '<div class="list-group-item">暂无询问笔录</div>';
        } else {
            $.each(record, function(i, data) {
                var link = url.caseRecordHandleEdit.replace('_record_id_', data.id);
                html += '<a href="' + link + '" class="list-group-item js-open">';
                html += data.user_type_name + '：' + data.name + '&nbsp;&nbsp;' + data.record_type_name + data.record_count + '次</a>';
            });
        }
        $('#caseRecordContent').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 渲染检验鉴定
    var renderCheckup = function(checkup) {
        var html = '';
        if ($.isEmptyObject(checkup)) {
            html = '<div class="list-group-item">暂无检验鉴定</div>';
        } else {
            $.each(checkup, function(i, data) {
                var link = url.caseCheckupEdit.replace('_checkup_id_', data.id);
                html += '<a href="' + link + '" class="list-group-item js-open';
                if (data.is_red) {
                    html += ' list-group-item-danger';
                }
                html += '">委托事项：' + data.item_name + '</a>';
            });
            $('button[name="checkup_status"]').addClass('btn-success');
        }
        $('#caseCheckupContent').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 渲染处罚
    var renderPunish = function(punish) {
        var html = '';
        if ($.isEmptyObject(punish)) {
            html = '<div class="list-group-item"></div>';
        } else {
            $.each(punish, function(i, data) {
                var link = url.casePunishHandleIndex.replace('_client_id_', data.case_client_id);
                html += '<a href="' + link + '" class="list-group-item js-open">';
                html += data.case_client_name + '：' + data.notice + '</a>';
            });
        }
        $('#casePunishContent').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 渲染调解
    var renderMediate = function(mediate, client) {
        var html = '';
        if ($.isEmptyObject(mediate)) {
            html = '<div class="list-group-item">暂无调解内容</div>';
        } else {
            html += '<div class="list-group-item">';
            $.each(client, function(i, data) {
                html += data.name + '：' + (($.inArray(data.id, mediate) >= 0) ? '申请' : '未申请') + '&nbsp;&nbsp;&nbsp;&nbsp;';
            });
            html += '</div>';
        }
        $('#caseMediateContent').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 事故认定
    var renderCognizance = function(cognizance) {
        if (cognizance.is_escape == 1) {
            $('#cognizanceBtnEscape').show();
        } else {
            $('#cognizanceBtnEscape').hide();
        }
        if (cognizance.is_exist == 1) {
            $('#cognizanceBtnSimple').hide();
            $('#cognizanceBtnNormal').hide();
            $('#cognizanceBtnUn').hide();
        } else {
            $('#cognizanceBtnSimple').show();
            if (cognizance.is_sure == 1) {
                $('#cognizanceBtnNormal').show();
                $('#cognizanceBtnUn').hide();
            } else {
                $('#cognizanceBtnNormal').hide();
                $('#cognizanceBtnUn').show();
            }
        }
        var html = '';
        if ($.isEmptyObject(cognizance.list)) {
            html = '<div class="list-group-item">暂无事故认定</div>';
        } else {
            $.each(cognizance.list, function(i, data) {
                var link = '';
                if (data.cognizance_type == 0) {
                    link = url.caseCognizanceSimpleIndex.replace('_cognizance_id_', data.id);
                    html += '<a href="' + link + '" class="list-group-item js-open">简易事故认定 状态：';
                    html += data.is_back == 1 ? '管理员退回' : '认定中';
                    html += '</a>';
                } else {
                    if (cognizance.is_sure == 1) {
                        link = url.caseCognizanceNormalIndex.replace('_cognizance_id_', data.id);
                    } else {
                        link = url.caseCognizanceUnCognizanceIndex.replace('_cognizance_id_', data.id);
                    }
                    html += '<a href="' + link + '" class="list-group-item js-open">一般事故认定 状态：';
                    if (data.is_back == 1) {
                        html += '管理员退回';
                    } else if (data.check_status == 2 && data.is_back == 0) {
                        html += '审核未通过';
                    } else if (data.check_status == 1) {
                        html += '审核通过';
                    } else if (data.check_status == 3 && data.is_back == 0) {
                        html += '复核科退回';
                    } else {
                        html += '认定中';
                    }
                    html += '</a>';
                }
            });
        }
        $('#caseCognizanceContent').html(html).find('.list-group-item').data('end', getCaseInfo);
    };
    /****************************************************************************************************/
    // 案件状态
    var renderCaseStatusText = function(statusText) {
        $('#caseStatusText').text(statusText);
    };
    /****************************************************************************************************/
    // 领导指示
    var renderCaseDirective = function(directive) {
        $('#caseDirectiveContent').text(directive);
    };
    /****************************************************************************************************/
    // 集体研究
    var renderCaseDiscuss = function(discuss) {
        $('#caseDiscussContent').text(discuss);
    };
    /****************************************************************************************************/
    var openCognizance = function(msg) {
        layer.open({
            type: 2,
            title: 0,
            closeBtn: 0,
            shadeClose: false,
            shade: 0.8,
            scrollbar: false,
            move: false,
            area: ['100%', '100%'],
            content: msg.url,
            end: getCaseInfo
        });
    };
    $(document).on('click', '#cognizanceBtnNormal,#cognizanceBtnUn', function(e) {
        e.preventDefault();
        var the = $(this);
        $.get(the.attr('href'), function(msg) {
            if (msg.status == 1) {
                $.get(msg.url, function(secMsg) {
                    if (secMsg.status == 1) {
                        openCognizance(secMsg);
                    } else {
                        layer.confirm(secMsg.info, function(index) {
                            layer.close(index);
                            openCognizance(secMsg);
                        });
                    }
                });
            } else {
                layer.alert(msg.info);
            }
        });
    });

    $(document).on('click', '#mediate', function(e) {
        e.preventDefault();
        var the = $(this);
        $.get(the.attr('href'), function(msg) {
            if (msg.status == 1) {
                openCognizance(msg);
            } else {
                layer.alert(msg.info,function(index){
                    layer.close(index);
                });
            }
        });
    });
    /****************************************************************************************************/
    $('.js-end-refresh').data('end', getCaseInfo);
    // 初始化
    getCaseInfo();
    /****************************************************************************************************/
    /****************************************************************************************************/
});
