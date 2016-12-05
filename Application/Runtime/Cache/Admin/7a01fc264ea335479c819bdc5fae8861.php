<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        案件办理
    </title>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap/css/bootstrap.min.css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you viewInfo the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/html5shiv.min.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_MODULE_URL); ?>/css/common.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/layer/layer.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/vmc.submit.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-treeview/js/bootstrap-treeview.js"></script>
    
    <script type="text/javascript">
    // 定义URL地址
    url.getCaseInfo = "<?php echo U('getCaseInfo');?>";
    url.caseAlarmHandleDetail = "<?php echo U('CaseAlarmHandle/detail?case_id='.$info['id'].'&id=_alarm_id_');?>";
    url.caseAcceptHandleAdd = "<?php echo U('CaseAcceptHandle/add?case_id='.$info['id']);?>";
    url.caseAcceptHandleEdit = "<?php echo U('CaseAcceptHandle/edit?case_id='.$info['id'].'&id=_accept_id_');?>";
    url.caseClientEdit = "<?php echo U('CaseClient/edit?case_id='.$info['id'].'&id=_client_id_');?>";
    url.caseRecordHandleEdit = "<?php echo U('CaseRecordHandle/edit?case_id='.$info['id'].'&id=_record_id_');?>";
    url.caseCheckupEdit = "<?php echo U('CaseCheckup/edit?case_id='.$info['id'].'&case_checkup_id=_checkup_id_');?>";
    url.casePunishHandleIndex = "<?php echo U('CasePunishHandle/index?case_id='.$info['id'].'&id=_client_id_');?>";
    url.caseCognizanceSimpleIndex = "<?php echo U('CaseCognizance/simpleIndex?case_id='.$info['id'].'&id=_cognizance_id_');?>";
    url.caseCognizanceNormalIndex = "<?php echo U('CaseCognizance/normalIndex?case_id='.$info['id'].'&action=investigation_report&id=_cognizance_id_');?>";
    url.caseCognizanceUnCognizanceIndex = "<?php echo U('CaseCognizance/unCognizanceIndex?case_id='.$info['id'].'&action=investigation_report&id=_cognizance_id_');?>";
    </script>

</head>

<body>
    
    <!-- 页面主体 -->
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <!-- 案件信息 -->
        <div class="panel panel-default" id="caseInfo">
    <!-- 面板标题 -->
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6"><span class="block-title">案件办理</span></div>
            <div class="col-xs-6 text-right">
                <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
            </div>
        </div>
    </div>
    <!-- 面板内容 -->
    <div class="panel-body">
        <table class="table table-bordered table-condensed table-form">
            <tr>
                <th width="10%">事故编号</th>
                <td width="15%">
                    <input type="text" name="code" class="form-control input-sm" disabled="disabled">
                </td>
                <th width="10%">事故时间</th>
                <td width="15%">
                    <input type="text" name="accident_time" class="form-control input-sm" disabled="disabled">
                </td>
                <th width="10%">事故地点</th>
                <td width="15%">
                    <input type="text" name="accident_place" class="form-control input-sm" disabled="disabled">
                </td>
                <th width="10%">事故类型</th>
                <td>
                    <select name="accident_type" class="form-control input-sm" disabled="disabled">
                        <option value="">请选择</option>
                        <?php if(is_array($accidentType)): $i = 0; $__LIST__ = $accidentType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="2">事故后果</th>
                <th>死亡人数</th>
                <td>
                    <input type="text" name="death_num" class="form-control input-sm" disabled="disabled">
                </td>
                <th>受伤人数</th>
                <td>
                    <input type="text" name="hurt_num" class="form-control input-sm" disabled="disabled">
                </td>
                <th>财产损失</th>
                <td>
                    <select name="property_loss" class="form-control input-sm" disabled="disabled">
                        <option value="">请选择</option>
                        <?php if(is_array($propertyLoss)): $i = 0; $__LIST__ = $propertyLoss;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="2">事故初查</th>
                <td colspan="6">
                    <div class="checkbox">
                        <?php if(is_array($firstCognizance)): $i = 0; $__LIST__ = $firstCognizance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label>
                                <input type="checkbox" name="first_cognizance" value="<?php echo ($vo["id"]); ?>" disabled="disabled"><?php echo ($vo["name"]); ?></label>&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php if(is_power($myPower,'case_edit_handle')): ?><a href="<?php echo U('edit?id='.$info['id']);?>" class="btn btn-primary js-open js-end-refresh"><span class="glyphicon glyphicon-pencil"></span> 编辑</a><?php endif; ?>
    </div>
</div>

        <!-- 按钮 -->
        <div style="margin-bottom:20px;">
            <a href="<?php echo U('CaseExtBase/add',array('case_id'=>$info['id']));?>"  type="button" class="btn btn-primary js-open js-end-refresh"><span class="glyphicon glyphicon-equalizer"></span> 案件信息采集补录</a>
            <a href="<?php echo U('DocumentView/index?case_id='.$info['id']);?>" type="button" class="btn btn-primary js-open js-end-refresh"><span class="glyphicon glyphicon-th-large"></span> 文书查看</a>
            <a href="<?php echo U('CaseReviewHandleInfo/detail?id=_case_review_id_');?>" class="btn btn-primary js-open js-end-refresh" id="caseReviewDetail" style="display:none;"><span class="glyphicon glyphicon-retweet"></span> 案件已被复核点击查看</a>
        </div>
        <!-- 步骤按钮 -->
        <style type="text/css">
#caseStatus {
    margin-bottom: 20px;
}
</style>
<div class="btn-group btn-group-justified" id="caseStatus">
    <div class="btn-group">
        <button name="accept_status" class="btn btn-default">受案信息</button>
    </div>
    <div class="btn-group">
        <button name="survey_status" class="btn btn-default">现场勘查信息</button>
    </div>
    <div class="btn-group">
        <button name="client_status" class="btn btn-default">当事人信息</button>
    </div>
    <div class="btn-group">
        <button name="record_status" class="btn btn-default">询问笔录</button>
    </div>
    <div class="btn-group">
        <button name="checkup_status" class="btn btn-default">检验鉴定</button>
    </div>
    <div class="btn-group">
        <button name="law_doc_status" class="btn btn-default">其他法律文书</button>
    </div>
    <div class="btn-group">
        <button name="cognizance_status" class="btn btn-default">事故认定</button>
    </div>
    <div class="btn-group">
        <button name="punish_status" class="btn btn-default">处罚</button>
    </div>
    <div class="btn-group">
        <button name="mediate_status" class="btn btn-default">调解</button>
    </div>
    <div class="btn-group">
        <button name="catalog_status" class="btn btn-default">归档</button>
    </div>
</div>

        <!-- 布局 -->
        <div class="row">
            <div class="col-xs-4">
                <!-- 面板 -->
<div class="panel panel-primary">
    <!-- 面板标题 -->
    <div class="panel-heading">受案信息</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:hidden;">
        <a href="<?php echo U('CaseAlarmHandle/index?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 报警记录</a>
        <div id="caseAlarmList" style="height:82px;overflow:auto;"></div>
        <a class="list-group-item list-group-item-info js-open js-end-refresh" id="caseAccept">
            <span class="glyphicon glyphicon-new-window"></span> <span id="caseAcceptBtn"></span>
        </a>
        <a class="list-group-item js-open js-end-refresh" id="caseAcceptContent"></a>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary" id="caseRecord">
    <!-- 面板标题 -->
    <div class="panel-heading">笔录材料</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseRecordHandle/index?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 询问笔录</a>
        <div id="caseRecordContent"></div>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary">
    <!-- 面板标题 -->
    <div class="panel-heading">事故认定</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseCognizance/escapeIndex?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh" id="cognizanceBtnEscape" style="display:none;"><span class="glyphicon glyphicon-new-window"></span> 逃逸事故认定</a>
        <a href="<?php echo U('CaseCognizance/simpleIndex?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh" id="cognizanceBtnSimple" style="display:none;"><span class="glyphicon glyphicon-new-window"></span> 简易程序</a>
        <a href="<?php echo U('checkIsCanNormalCognizance?sure=1&case_id='.$info['id']);?>" class="list-group-item list-group-item-info" id="cognizanceBtnNormal" style="display:none;"><span class="glyphicon glyphicon-new-window"></span> 一般程序</a>
        <a href="<?php echo U('checkIsCanNormalCognizance?sure=0&case_id='.$info['id']);?>" class="list-group-item list-group-item-info" id="cognizanceBtnUn" style="display:none;"><span class="glyphicon glyphicon-new-window"></span> 一般程序</a>
        <div id="caseCognizanceContent"></div>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary">
    <!-- 面板标题 -->
    <div class="panel-heading">调解</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseHandle/checkCognizance?case_id='.$info['id']);?>" id="mediate" class="list-group-item list-group-item-info"><span class="glyphicon glyphicon-new-window"></span> 道路交通事故损害赔偿调解申请书</a>
        <div id="caseMediateContent"></div>
    </div>
</div>

            </div>
            <div class="col-xs-4">
                <!-- 面板 -->
<div class="panel panel-primary" id="caseSurvey">
    <!-- 面板标题 -->
    <div class="panel-heading">现场勘察信息</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a data-href="<?php echo U('CaseSurveyHandle/edit?case_id='.$info['id'].'&id=_survey_id_#box1');?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 现场图 </a>
        <a data-href="<?php echo U('CaseSurveyHandle/edit?case_id='.$info['id'].'&id=_survey_id_#box2');?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 现场照片 </a>
        <a data-href="<?php echo U('CaseSurveyHandle/edit?case_id='.$info['id'].'&id=_survey_id_#box3');?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 现场勘查笔录 </a>
        <a data-href="<?php echo U('CaseSurveyHandle/edit?case_id='.$info['id'].'&id=_survey_id_#box4');?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 现场遗留物品清单 </a>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary" id="caseCheckup">
    <!-- 面板标题 -->
    <div class="panel-heading">检验鉴定</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseCheckup/index?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 检验鉴定委托</a>
        <div id="caseCheckupContent"></div>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary" id="casePunish">
    <!-- 面板标题 -->
    <div class="panel-heading">处罚</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CasePunishHandle/index?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 公安交通管理行政处罚决定书</a>
        <div id="casePunishContent"></div>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary">
    <!-- 面板标题 -->
    <div class="panel-heading">结案归档</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseCatalog/index?case_id='.$info['id']);?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 目录 </a>
        <a href="<?php echo U('CaseFileHandle/index?case_id='.$info['id']);?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 工作记录 </a>
        <a href="<?php echo U('Archive/caseToArchive?case_id='.$info['id']);?>" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 归档信息查看 </a>
    </div>
</div>

            </div>
            <div class="col-xs-4">
                <!-- 面板 -->
<div class="panel panel-primary" id="caseClient">
    <!-- 面板标题 -->
    <div class="panel-heading">当事人信息</div>
    <!-- 面板内容 -->
    <div class="list-group" style="height:204px;overflow:auto;">
        <a href="<?php echo U('CaseClient/add?case_id='.$info['id']);?>" class="list-group-item list-group-item-info js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 当事人信息 </a>
        <div id="caseClientList"></div>
    </div>
</div>

                <!-- 面板 -->
<div class="panel panel-primary">
    <!-- 面板标题 -->
    <div class="panel-heading">其他法律文书</div>
    <!-- 面板内容 -->
    <div class="list-group">
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 道路交通事故处理通知书 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 勘验笔录 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 辨认笔录 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 提取笔录 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 证据公开笔录 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 送达回执 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 通（告）知记录 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 物品扣押 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 尸体处理通知书 </a>
        <a href="#" class="list-group-item js-open js-end-refresh"><span class="glyphicon glyphicon-new-window"></span> 其他法律文书 </a>
    </div>
</div>

                <div class="panel panel-primary">
    <div class="list-group">
        <div class="list-group-item">
            正在进行：<span id="caseStatusText"></span>
        </div>
        <div class="list-group-item">
            <?php if($timeRs['timestamp'] == '-'): ?>完成期限：-&nbsp;&nbsp;&nbsp;&nbsp;今天为：-
            <?php elseif($timeRs['timestamp'] == '已完成'): ?>
                完成期限：<?php echo ($timeRs['timeLimit']); ?>天&nbsp;&nbsp;&nbsp;&nbsp;今天为：已完成
            <?php elseif($timeRs['timestamp'] == '已过期'): ?>
                完成期限：<?php echo ($timeRs['timeLimit']); ?>天&nbsp;&nbsp;&nbsp;&nbsp;今天为：已过期
            <?php else: ?>
                完成期限：<?php echo ($timeRs['timeLimit']); ?>天&nbsp;&nbsp;&nbsp;&nbsp;今天为：第<?php echo ($timeRs['timestamp']); ?>天<?php endif; ?>
        </div>
    </div>
</div>

                <div class="panel panel-primary">
    <div class="list-group">
        <a href="<?php echo U('LawRegulation/index');?>" class="list-group-item list-group-item-info js-open js-end-refresh">
            <span class="glyphicon glyphicon-new-window"></span> 道路交通相关法律法规
        </a>
    </div>
</div>

            </div>
        </div>
        <div class="panel panel-default">
    <!-- 面板主体 -->
    <div class="panel-body">
        <table class="table table-bordered table-condensed table-sheet" style="margin-bottom:0;">
            <tr>
                <th width="15%">指示意见</th>
                <td style="text-align:left;word-break:break-all;" id="caseDirectiveContent"></td>
                <td width="70">
                    <a href="<?php echo U('CaseDirectiveLeader/add?case_id='.$info['id']);?>" class="btn btn-primary btn-sm js-open js-end-refresh">指示</a>
                </td>
                <td width="70">
                    <a href="<?php echo U('CaseDirectiveLeader/index?case_id='.$info['id']);?>" class="btn btn-primary btn-sm js-open js-end-refresh">查看</a>
                </td>
            </tr>
            <tr>
                <th>集体研究</th>
                <td style="text-align:left;word-break:break-all;" id="caseDiscussContent"></td>
                <td>
                    <a href="<?php echo U('CaseDiscussLeader/add?case_id='.$info['id']);?>" class="btn btn-primary btn-sm js-open js-end-refresh">制作</a>
                </td>
                <td>
                    <a href="<?php echo U('CaseDiscussLeader/index?case_id='.$info['id']);?>" class="btn btn-primary btn-sm js-open js-end-refresh">查看</a>
                </td>
            </tr>
        </table>
    </div>
</div>

    </div>
    <input type="hidden" name="case_id" value="<?php echo ($info["id"]); ?>">

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/CaseHandle/detail.js"></script>

</body>

</html>