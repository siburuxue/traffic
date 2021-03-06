<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        
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
    
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_MODULE_URL); ?>/css/side.css" />

</head>

<body>
    
    <div class="container-fluid" id="menu">
        <?php if(is_power($myPower,'alarm_receive,alarm_all_receive','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-receive" data-parent="#menu">指挥中心 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-receive">
                    <?php if(is_power($myPower,'alarm_receive')): ?><a class="list-group-item" href="<?php echo U('AlarmReceive/index');?>" target="mainframe" hidefocus="true">报警信息</a><?php endif; ?>
                    <?php if(is_power($myPower,'alarm_all_receive')): ?><a class="list-group-item" href="<?php echo U('AlarmReceive/search');?>" target="mainframe" hidefocus="true">全部报警信息</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'case_pending,case_completed,case_goods_return,case_bloodtube_search,case_account','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-handle" data-parent="#menu">办案人 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-handle">
                    <?php if(is_power($myPower,'case_pending')): ?><a class="list-group-item" href="<?php echo U('CaseHandle/pending');?>" target="mainframe" hidefocus="true">待办工作</a><?php endif; ?>
                    <?php if(is_power($myPower,'case_completed')): ?><a class="list-group-item" href="<?php echo U('CaseHandle/completed');?>" target="mainframe" hidefocus="true">已完成工作</a><?php endif; ?>
                    <?php if(is_power($myPower,'case_goods_return')): ?><a class="list-group-item" href="<?php echo U('CaseClientDetain/pending');?>" target="mainframe" hidefocus="true">物品返还</a><?php endif; ?>
                    <?php if(is_power($myPower,'case_bloodtube_search')): ?><a class="list-group-item" href="<?php echo U('CaseBloodtubeCateSearch/index');?>" target="mainframe" hidefocus="true">采血管查询</a><?php endif; ?>
                    <?php if(is_power($myPower,'case_account')): ?><a class="list-group-item" href="<?php echo U('CaseStandingBook/index?type=handle');?>" target="mainframe" hidefocus="true">交通事故台帐</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'leader_my_work,leader_attention,leader_return,leader_bloodtube_search,leader_account,leader_transfer','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-leader" data-parent="#menu">领导 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-leader">
                    <?php if(is_power($myPower,'leader_my_work')): ?><a class="list-group-item" href="<?php echo U('CaseCheckLeader/pending');?>" target="mainframe" hidefocus="true">我的工作</a><?php endif; ?>
                    <?php if(is_power($myPower,'leader_attention')): ?><a class="list-group-item" href="<?php echo U('CaseAttention/completed');?>" target="mainframe" hidefocus="true">重点关注案件</a><?php endif; ?>
                    <?php if(is_power($myPower,'leader_return')): ?><a class="list-group-item" href="<?php echo U('CaseClientDetainSupervisor/pending');?>" target="mainframe" hidefocus="true">物品返还</a><?php endif; ?>
                    <?php if(is_power($myPower,'leader_bloodtube_search')): ?><a class="list-group-item" href="<?php echo U('CaseBloodtubeCateSearchSupervisor/index');?>" target="mainframe" hidefocus="true">采血管查询</a><?php endif; ?>
                    <?php if(is_power($myPower,'leader_account')): ?><a class="list-group-item" href="<?php echo U('CaseStandingBook/index?type=leader');?>" target="mainframe" hidefocus="true">交通事故台帐</a><?php endif; ?>
                    <?php if(is_power($myPower,'leader_transfer')): ?><a class="list-group-item" href="<?php echo U('CaseTransfer/pending');?>" target="mainframe" hidefocus="true">案件移交</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'case_fast_my_work','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-fast" data-parent="#menu">快速处理 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-fast">
                    <?php if(is_power($myPower,'case_fast_my_work')): ?><a class="list-group-item" href="<?php echo U('FastProcess/index');?>" target="mainframe" hidefocus="true">我的工作</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'checkup_pending,checkup_completed','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-checkup" data-parent="#menu">支队鉴定中心 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-checkup">
                    <?php if(is_power($myPower,'checkup_pending')): ?><a class="list-group-item" href="<?php echo U('CaseCheckupDetachment/index');?>" target="mainframe" hidefocus="true">待办工作</a><?php endif; ?>
                    <?php if(is_power($myPower,'checkup_completed')): ?><a class="list-group-item" href="<?php echo U('CaseCheckupDetachment/finish');?>" target="mainframe" hidefocus="true">已完成工作</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'recorder_case_search,today_upload','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-archivist" data-parent="#menu">案件资料录入员 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-archivist">
                    <?php if(is_power($myPower,'recorder_case_search')): ?><a class="list-group-item" href="<?php echo U('CaseSearch/index');?>" target="mainframe" hidefocus="true">案件查询</a><?php endif; ?>
                    <?php if(is_power($myPower,'today_upload')): ?><a class="list-group-item" href="<?php echo U('Index/main');?>" target="mainframe" hidefocus="true">今日上传资料</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'duty,duty_group','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-officework" data-parent="#menu">内勤人员 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-officework">
                    <?php if(is_power($myPower,'duty')): ?><a class="list-group-item" href="<?php echo U('Duty/index');?>" target="mainframe" hidefocus="true">值班维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'duty_group')): ?><a class="list-group-item" href="<?php echo U('DutyGroup/index');?>" target="mainframe" hidefocus="true">组别维护</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'review_pending,review_completed','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-review" data-parent="#menu">复核科 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-review">
                    <?php if(is_power($myPower,'review_pending')): ?><a class="list-group-item" href="<?php echo U('CaseReviewHandle/pending');?>" target="mainframe" hidefocus="true">待办工作</a><?php endif; ?>
                    <?php if(is_power($myPower,'review_completed')): ?><a class="list-group-item" href="<?php echo U('CaseReviewHandle/completed');?>" target="mainframe" hidefocus="true">已完成工作</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'statistics_blood,statistics_checkup,statistics_rescue,statistics_parking,statistics_case,statistics_hurt','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-statistics" data-parent="#menu">统计分析 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-statistics">
                    <?php if(is_power($myPower,'statistics_blood')): ?><a class="list-group-item" href="<?php echo U('Statistics/bloodtube');?>" target="mainframe" hidefocus="true">血样提取统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'statistics_checkup')): ?><a class="list-group-item" href="<?php echo U('Statistics/checkup');?>" target="mainframe" hidefocus="true">检验鉴定统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'statistics_rescue')): ?><a class="list-group-item" href="<?php echo U('Statistics/car');?>" target="mainframe" hidefocus="true">车辆救援统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'statistics_parking')): ?><a class="list-group-item" href="<?php echo U('Statistics/parking');?>" target="mainframe" hidefocus="true">车辆停放费用统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'statistics_case')): ?><a class="list-group-item" href="<?php echo U('Statistics/caseInfo');?>" target="mainframe" hidefocus="true">事故统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'statistics_hurt')): ?><a class="list-group-item" href="<?php echo U('Statistics/casualties');?>" target="mainframe" hidefocus="true">人员伤亡统计</a><?php endif; ?>
                    <?php if(is_power($myPower,'custom_search')): ?><a class="list-group-item" href="<?php echo U('CustomSearch/index');?>" target="mainframe" hidefocus="true">自定义查询</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'alarm_admin,admin_case_search,admin_short_word,admin_custom_sql,admin_department,admin_user,admin_role,admin_power,admin_dict,admin_calendar,admin_checkup_org,admin_checkup_org_del,admin_law_doc,admin_checkup_item','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-admin" data-parent="#menu">系统管理员 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-admin">
                    <?php if(is_power($myPower,'alarm_admin')): ?><a class="list-group-item" href="<?php echo U('AlarmAdmin/index');?>" target="mainframe" hidefocus="true">报警信息查询</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_case_search')): ?><a class="list-group-item" href="<?php echo U('AccidentSearchLeader/index');?>" target="mainframe" hidefocus="true">事故查询</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_short_word')): ?><a class="list-group-item" href="<?php echo U('PhraseTemplate/index');?>" target="mainframe" hidefocus="true">短语模板维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_custom_sql')): ?><a class="list-group-item" href="<?php echo U('Index/main');?>" target="mainframe" hidefocus="true">自定义查询条件</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_department')): ?><a class="list-group-item" href="<?php echo U('Department/index');?>" target="mainframe" hidefocus="true">组织机构维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_user')): ?><a class="list-group-item" href="<?php echo U('User/index');?>" target="mainframe" hidefocus="true">人员信息维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_role')): ?><a class="list-group-item" href="<?php echo U('Role/index');?>" target="mainframe" hidefocus="true">角色维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_power')): ?><a class="list-group-item" href="<?php echo U('Power/index');?>" target="mainframe" hidefocus="true">权限节点维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_dict')): ?><a class="list-group-item" href="<?php echo U('Dict/index');?>" target="mainframe" hidefocus="true">字典项维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_calendar')): ?><a class="list-group-item" href="<?php echo U('Calendar/index');?>" target="mainframe" hidefocus="true">日历维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_checkup_org')): ?><a class="list-group-item" href="<?php echo U('CheckupOrg/index');?>" target="mainframe" hidefocus="true">检验鉴定机构维护</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_checkup_org_del')): ?><a class="list-group-item" href="<?php echo U('CheckupOrg/void');?>" target="mainframe" hidefocus="true">已作废机构</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_law_doc')): ?><a class="list-group-item" href="<?php echo U('LawRegulation/index');?>" target="mainframe" hidefocus="true">其他法律法规</a><?php endif; ?>
                    <?php if(is_power($myPower,'admin_checkup_item')): ?><a class="list-group-item" href="<?php echo U('CheckupOrgItem/index');?>" target="mainframe" hidefocus="true">可进行检验鉴定项目</a><?php endif; ?>
                    <?php if(is_power($myPower,'custom_search_template_service')): ?><a class="list-group-item" href="<?php echo U('CustomSearchTemplateService/index');?>" target="mainframe" hidefocus="true">自定义查询模板维护</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'bloodtubecate_dispatch_one,bloodtubecate_dispatch_two,bloodtubecate_recover,bloodtubecate_advance,bloodtubecate_normal,bloodtubecate_low','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-bloodtube" data-parent="#menu">采血管管理子系统 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-bloodtube">
                    <?php if(is_power($myPower,'bloodtubecate_dispatch_one')): ?><a class="list-group-item" href="<?php echo U('BloodtubeCate/grantDetachment');?>" target="mainframe" hidefocus="true">支队采血管派发</a><?php endif; ?>
                    <?php if(is_power($myPower,'bloodtubecate_dispatch_two')): ?><a class="list-group-item" href="<?php echo U('BloodtubeCate/grantRegiment');?>" target="mainframe" hidefocus="true">大队采血管派发</a><?php endif; ?>
                    <?php if(is_power($myPower,'bloodtubecate_recover')): ?><a class="list-group-item" href="<?php echo U('BloodtubeCate/recover');?>" target="mainframe" hidefocus="true">采血管回收</a><?php endif; ?>
                    <?php if(is_power($myPower,'bloodtubecate_advance,bloodtubecate_normal,bloodtubecate_low','or')): ?><a class="list-group-item" href="<?php echo U('BloodtubeCate/index');?>" target="mainframe" hidefocus="true">采血管查询</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
        <?php if(is_power($myPower,'archive','or')): ?><div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#menu-archive" data-parent="#menu">案件档案管理子系统 <span class="caret"></span></div>
                <div class="list-group collapse" id="menu-archive">
                    <?php if(is_power($myPower,'archive')): ?><a class="list-group-item" href="<?php echo U('Archive/index');?>" target="mainframe" hidefocus="true">档案管理</a><?php endif; ?>
                </div>
            </div><?php endif; ?>
    </div>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript">
    $(function() {
        $('.list-group-item').on('click', function() {
            $('#menu').find('.list-group-item').removeClass('active');
            $(this).addClass('active');
        })
    });
    </script>

</body>

</html>