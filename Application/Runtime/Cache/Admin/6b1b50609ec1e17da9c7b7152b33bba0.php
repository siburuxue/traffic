<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
         交通事故台账
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
        url.table = "<?php echo U('indexTable');?>";
        //大队联动用户列表
        url.getUserList = "<?php echo U('getUserList');?>";
        //打印
        url.print = "<?php echo U('exportExcel?paras=');?>";
    </script>

</head>

<body>
    
    <!-- 页面主体 -->
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <?php if($type == 'handle'): ?><ul class="nav nav-tabs nav-custom">
    <?php if(is_power($myPower,'case_pending')): ?><li><a href="<?php echo U('CaseHandle/pending');?>">待办工作</a></li><?php endif; ?>
    <?php if(is_power($myPower,'case_completed')): ?><li><a href="<?php echo U('CaseHandle/completed');?>">已完成工作</a></li><?php endif; ?>
    <?php if(is_power($myPower,'case_goods_return')): ?><li><a href="<?php echo U('CaseClientDetain/pending');?>">物品返还</a></li><?php endif; ?>
    <?php if(is_power($myPower,'case_bloodtube_search')): ?><li><a href="<?php echo U('CaseBloodtubeCateSearch/index');?>">采血管查询</a></li><?php endif; ?>
    <?php if(is_power($myPower,'case_account')): ?><li class="active"><a href="<?php echo U('CaseStandingBook/index?type=handle');?>">交通事故台帐</a></li><?php endif; ?>
</ul>


        <?php else: ?>
            <ul class="nav nav-tabs nav-custom">
    <?php if(is_power($myPower,'leader_my_work')): ?><li><a href="<?php echo U('CaseCheckLeader/pending');?>">待办工作</a></li>
        <li><a href="<?php echo U('CaseCheckLeader/completed');?>">已完成工作</a></li><?php endif; ?>
    <?php if(is_power($myPower,'leader_return')): ?><li><a href="<?php echo U('CaseClientDetainSupervisor/pending');?>">物品返还</a></li><?php endif; ?>
    <?php if(is_power($myPower,'leader_bloodtube_search')): ?><li><a href="<?php echo U('CaseBloodtubeCateSearchSupervisor/index');?>">采血管查询</a></li><?php endif; ?>
    <?php if(is_power($myPower,'leader_account')): ?><li class="active"><a href="<?php echo U('CaseStandingBook/index?type=leader');?>">交通事故台帐</a></li><?php endif; ?>
</ul><?php endif; ?>

        <!-- 列表面板 -->
        <div class="panel panel-default">
            <!-- 面板头 -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6"><span class="block-title">交通事故台账</span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板主体 -->
            <div class="panel-body">
                <!-- 搜索 -->
                <table class="table table-bordered table-condensed table-custom">
    <tr>
        <th width="15%">所属大队</th>
        <td width="35%">
            <?php if($type == 'leader'): if(is_power($myPower,'leader_standing_book_height')): ?><select class="form-control input-sm search-auto" id="brigade" name="condition_brigade">
                        <option value="">请选择</option>
                        <?php if(is_array($brigadeList)): $i = 0; $__LIST__ = $brigadeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                <?php else: ?>
                    <select class="form-control input-sm search-auto" name="condition_brigade" disabled>
                        <option value="">请选择</option>
                        <?php if(is_array($brigadeList)): $i = 0; $__LIST__ = $brigadeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $brigade['id']): ?>selected<?php endif; ?>  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select><?php endif; ?>
            <?php else: ?>
                <?php if(is_power($myPower,'case_standing_book_height')): ?><select class="form-control input-sm search-auto" id="brigade" name="condition_brigade">
                        <option value="">请选择</option>
                        <?php if(is_array($brigadeList)): $i = 0; $__LIST__ = $brigadeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                <?php else: ?>
                    <select class="form-control input-sm search-auto" name="condition_brigade" disabled>
                        <option value="">请选择</option>
                        <?php if(is_array($brigadeList)): $i = 0; $__LIST__ = $brigadeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $brigade['id']): ?>selected<?php endif; ?>  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select><?php endif; endif; ?>
        </td>
        <th width="15%" colspan="2">办案人</th>
        <td>
            <?php if($type == 'leader'): if(is_power($myPower,'leader_standing_book_height')): ?><select class="form-control input-sm search-auto" id="user" name="condition_user">
                        <option value="">请选择</option>
                    </select>
                <?php elseif(is_power($myPower,'leader_standing_book_normal')): ?>
                    <select class="form-control input-sm search-auto" name="condition_user">
                        <option value="">请选择</option>
                        <?php if(is_array($userList)): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                <?php else: ?>
                    <select class="form-control input-sm search-auto" name="condition_user" disabled>
                        <option value="">请选择</option>
                        <?php if(is_array($userList)): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $my['id']): ?>selected<?php endif; ?> ><?php echo ($vo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select><?php endif; ?>
            <?php else: ?>
                <?php if(is_power($myPower,'case_standing_book_height')): ?><select class="form-control input-sm search-auto" id="user" name="condition_user">
                        <option value="">请选择</option>
                    </select>
                <?php elseif(is_power($myPower,'case_standing_book_normal')): ?>
                    <select class="form-control input-sm search-auto" name="condition_user">
                        <option value="">请选择</option>
                        <?php if(is_array($userList)): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                <?php else: ?>
                    <select class="form-control input-sm search-auto" name="condition_user" disabled>
                        <option value="">请选择</option>
                        <?php if(is_array($userList)): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id'] == $my['id']): ?>selected<?php endif; ?> ><?php echo ($vo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select><?php endif; endif; ?>

        </td>
    </tr>
    <tr>
        <th width="15%">事故时间</th>
        <td width="35%">
            <table width="100%">
                <tr>
                    <td><input type="text" class="form-control input-sm search-auto form-datetime" name="condition_start_time" readonly></td>
                    <td width="30" class="text-center">~</td>
                    <td><input type="text" class="form-control input-sm search-auto form-datetime" name="condition_end_time" readonly></td>
                </tr>
            </table>
        </td>
        <th width="15%" colspan="2">事故地点</th>
        <td>
            <input type="text" class="form-control input-sm search-auto" name="condition_accident_place">
        </td>
    </tr>
    <tr>
        <th width="15%">事故类型</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_accident_type">
                <option value="">请选择</option>
                <?php if(is_array($accidentType)): $i = 0; $__LIST__ = $accidentType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
        <th rowspan="3">事故后果</th>
        <th>死亡人数</th>
        <td>
            <table width="100%">
                <tr>
                    <td><input type="number" class="form-control input-sm search-auto" name="condition_death_num_min" max="99999999.99"></td>
                    <td width="30" class="text-center">~</td>
                    <td><input type="number" class="form-control input-sm search-auto" name="condition_death_num_max" max="99999999.99"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>

        <th width="15%">初查情况</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_first_result">
                <option value="">请选择</option>
                <?php if(is_array($firstCognizance)): $i = 0; $__LIST__ = $firstCognizance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
        <th>受伤人数</th>
        <td>
            <table width="100%">
                <tr>
                    <td><input type="number" class="form-control input-sm search-auto" name="condition_hurt_num_min" max="99999999.99"></td>
                    <td width="30" class="text-center">~</td>
                    <td><input type="number" class="form-control input-sm search-auto" name="condition_hurt_num_max" max="99999999.99"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th width="15%">姓名</th>
        <td>
            <input type="text" class="form-control input-sm search-auto" name="condition_name">

        </td>
        <th>财产损失</th>
        <td>
            <select class="form-control input-sm search-auto" name="condition_property_loss">
                <option value="">请选择</option>
                <?php if(is_array($property_loss)): $i = 0; $__LIST__ = $property_loss;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th width="15%">交通方式</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_traffic_type">
                <option value="">请选择</option>
                <?php if(is_array($trafficType)): $i = 0; $__LIST__ = $trafficType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
        <th width="15%" colspan="2">车牌号</th>
        <td>
            <input type="text" class="form-control input-sm search-auto" name="condition_car_no">
        </td>
    </tr>
    <tr>
        <th width="15%">违法行为</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_law">
                <option value="">请选择</option>
                <?php if(is_array($lawList)): $i = 0; $__LIST__ = $lawList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
        <th width="15%" colspan="2">伤害程度</th>
        <td>
            <select class="form-control input-sm search-auto" name="condition_hurt_type">
                <option value="">请选择</option>
                <?php if(is_array($hurtType)): $i = 0; $__LIST__ = $hurtType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th width="15%">行政强制措施</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_coercive_measure">
                <option value="">请选择</option>
                <option value="1">警告</option>
                <option value="2">罚款</option>
                <option value="3">暂扣</option>
                <option value="4">吊销</option>
                <option value="5">拘留</option>
            </select>
        </td>
        <th width="15%" colspan="2">责任认定时间</th>
        <td>
            <table width="100%">
                <tr>
                    <td><input type="text" class="form-control input-sm search-auto form-datetime" name="condition_cognizance_start_time" readonly></td>
                    <td width="30" class="text-center">~</td>
                    <td><input type="text" class="form-control input-sm search-auto form-datetime" name="condition_cognizance_end_time" readonly></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th width="15%">事故责任</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_blame_type">
                <option value="">请选择</option>
                <?php if(is_array($blameType)): $i = 0; $__LIST__ = $blameType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
        <th width="15%" colspan="2">处罚</th>
        <td>
            <select class="form-control input-sm search-auto" name="condition_punish_type">
                <option value="">请选择</option>
                <option value="1">已处罚</option>
                <option value="2">未处罚</option>
            </select>
        </td>
    </tr>
    <tr>
        <th width="15%">调解</th>
        <td width="35%">
            <select class="form-control input-sm search-auto" name="condition_mediate_type">
                <option value="">请选择</option>
                <option value="1">不调解</option>
                <option value="2">调解</option>
            </select>
        </td>
        <th width="15%" colspan="2">当前状态</th>
        <td>
            <select class="form-control input-sm search-auto" name="condition_status_type">
                <option value="">请选择</option>
                <?php if(is_array($statusType)): $i = 0; $__LIST__ = $statusType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
    </tr>
</table>
<div style="margin-bottom: 20px;">
    <button type="button" class="btn btn-primary" id="search-submit"><span class="glyphicon glyphicon-search"></span> 查询</button>
    <button type="button" class="btn btn-warning" id="search-reset"><span class="glyphicon glyphicon-repeat"></span> 重置</button>
</div>


                <div class="text-right" style="margin-bottom: 10px;">
                    <a href="javascript:;" class="btn btn-warning btn-sm " id="print"><span class="glyphicon glyphicon-save-file"></span>导出Excel</a>
                </div>
                <!-- 表格 -->
                <div id="table-content"style="overflow-x:auto;margin-bottom: 20px;"></div>
                <!-- 分页 -->
                <div class="row">
    <div class="col-xs-6">
        <div class="btn-group">
            <button type="button" class="btn btn-default page-first" disabled="disabled"><span class="glyphicon glyphicon-fast-backward"></span></button>
            <button type="button" class="btn btn-default page-prev" disabled="disabled"><span class="glyphicon glyphicon-backward"></span></button>
            <button type="button" class="btn btn-default page-next" disabled="disabled"><span class="glyphicon glyphicon-forward"></span></button>
            <button type="button" class="btn btn-default page-last" disabled="disabled"><span class="glyphicon glyphicon-fast-forward"></span></button>
        </div>
    </div>
    <div class="col-xs-6 text-right">
        <div class="form-inline">
            <div class="input-group">
                <div class="input-group-btn">
                    <button class="btn btn-default">共 <span id="page-totalrows">0</span> 条记录</button>
                    <button class="btn btn-default">第 <span id="page-nowpage">0</span>/<span id="page-totalpage">0</span> 页</button>
                </div>
                <input type="text" class="form-control text-center" value="1" id="search-page" maxlength="10" style="width:60px;">
                <div class="input-group-btn">
                    <button class="btn btn-default" id="search-jump" disabled="disabled">跳转</button>
                </div>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
        <!-- 表格 结束 -->
    </div>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/CaseStandingBook/index.js"></script>

</body>

</html>