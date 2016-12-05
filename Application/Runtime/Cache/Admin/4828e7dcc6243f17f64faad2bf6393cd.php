<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        支队鉴定中心
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
    url.table = "<?php echo U('finishTable');?>";
    </script>

</head>

<body>
    
    <!-- 页面主体 -->
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <!-- 列表面板 -->
        <div class="panel panel-default">
            <!-- 面板头 -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6"><span class="block-title">支队鉴定中心</span></div>
                    <div class="col-xs-6 text-right">
<!--                         <a href="<?php echo U('add');?>" class="btn btn-primary btn-sm js-open js-end-refresh"><span class="glyphicon glyphicon-plus"></span> 新增</a> -->
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板主体 -->
            <div class="panel-body">
                <!-- 搜索 -->
                <div style="width:100%;height:10px;float:left;"></div>
<ul class="nav nav-tabs nav-custom">
	<?php if(is_power($myPower,'checkup_pending')): ?><li><a href="<?php echo U('index');?>">待办工作</a></li><?php endif; ?>
	<?php if(is_power($myPower,'checkup_completed')): ?><li class="active"><a href="<?php echo U('finish');?>">已完成工作</a></li><?php endif; ?>
</ul>

                <div class="table-responsive">
    <table class="table table-bordered table-condensed table-custom">
        <tr>
            <th width="15%">委托人姓名</th>
            <td width="35%">
                <input type="text" class="form-control input-sm search-auto" name="condition_target_name" maxlength="50">
            </td>
            <th width="15%">委托时间</th>
            <td>
                <table width="100%">
                    <tr>
                        <td width="48%">
                            <input type="text" class="form-control input-sm search-auto form-datetime" id="start-time" name="condition_start_time" maxlength="16" readonly="readonly" style="min-width:auto;width:100%;">
                        </td>
                        <td width="4%" align="center"> ~
                        </td>
                        <td width="48%">
                            <input type="text" class="form-control input-sm search-auto form-datetime" id="end-time" name="condition_end_time" maxlength="16" readonly="readonly" style="min-width:auto;width:100%;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>委托内容</th>
            <td>
                <select name="condition_accident_type" class="form-control input-sm search-auto">
                    <option value="">请选择</option>
                    <option value="1">财产损失事故</option>
                    <option value="2">伤人事故</option>
                    <option value="3">死亡事故</option>
                </select>
            </td>
            <th>委托书编号</th>
            <td>
                <input type="text" class="form-control input-sm search-auto" name="condition_code" maxlength="255">
            </td>
        </tr>
<!--         <tr>
            <th>所属大队</th>
            <td>
                <select name="condition_from_department_id" class="form-control input-sm search-auto">
                    <option value="">请选择</option>
                    <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["cate"]) != "2"): ?>disabled="disabled" style="color:red;"<?php endif; ?>><?php echo ($vo["_prefix"]); echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </td>
            <th>委托事项</th>
            <td>
                <select name="condition_role_id" class="form-control input-sm search-auto" style="width:50%;float:left;">
                    <option value="">请选择</option>
                    <?php if(is_array($role)): $i = 0; $__LIST__ = $role;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="condition_role_id" class="form-control input-sm search-auto"  style="width:50%;float:left;">
                    <option value="">请选择</option>
                    <?php if(is_array($role)): $i = 0; $__LIST__ = $role;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>

            </td>
        </tr> -->
    </table>
</div>
<div style="margin-bottom:20px;">
    <button type="button" class="btn btn-primary" id="search-submit"><span class="glyphicon glyphicon-search"></span> 查询</button>
    <button type="button" class="btn btn-warning" id="search-reset"><span class="glyphicon glyphicon-repeat"></span> 重置</button>
</div>

                <!-- 表格 -->
                <div class="table-responsive" id="table-content"></div>
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
    <!-- 页面主体 结束 -->

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    

    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/CaseCheckupDetachment/finish.js"></script>

</body>

</html>