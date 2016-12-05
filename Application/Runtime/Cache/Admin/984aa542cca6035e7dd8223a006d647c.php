<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        车辆救援统计
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
    url.table = "<?php echo U('carTable');?>";
    url.getHandle = "<?php echo U('getHandle');?>";
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
                    <div class="col-xs-6"><span class="block-title">车辆救援统计</span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板主体 -->
            <div class="panel-body">
                <!-- 搜索 -->
                <div class="table-responsive">
    <table class="table table-bordered table-condensed table-form">
        <tr>
            <th width="15%">所属大队</th>
            <td colspan="3">
                <input type="text" class="form-control input-sm <?php if(false===is_power($myPower,'statistics_blood_3')): ?>disabled<?php endif; ?>" name="condition_department" readonly="readonly" data-target="#select-department" data-default="<?php if(false===is_power($myPower,'statistics_blood_3')): echo ($myBrigade["id"]); endif; ?>">
            </td>
        </tr>
        <tr>
            <th width="15%">办案人</th>
            <td width="35%">
                <input type="text" class="form-control input-sm <?php if(false===is_power($myPower,'statistics_blood_2,statistics_blood_3','or')): ?>disabled<?php endif; ?>" name="condition_handle" readonly="readonly" data-target="#select-handle" data-default="<?php if(false===is_power($myPower,'statistics_blood_2,statistics_blood_3','or')): echo ($my["id"]); endif; ?>">
            </td>
            <th width="15%">事故时间</th>
            <td>
                <table width="100%">
                    <tr>
                        <td>
                            <input type="text" class="form-control input-sm search-auto form-datetime" name="condition_start_time" maxlength="16" readonly="readonly">
                        </td>
                        <td width="30" class="text-center">~</td>
                        <td>
                            <input type="text" class="form-control input-sm search-auto form-datetime" name="condition_end_time" maxlength="16" readonly="readonly">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>事故地点</th>
            <td>
                <input type="text" class="form-control input-sm search-auto" name="condition_accident_place" maxlength="255">
            </td>
            <th>被提取人姓名</th>
            <td>
                <input type="text" class="form-control input-sm search-auto" name="condition_client_name" maxlength="255">
            </td>
        </tr>
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

    
    <div class="panel panel-default" style="position:absolute;z-index:9999;display:none;" id="select-department">
    <div class="list-group" style="height:198px;overflow:auto;">
        <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list-group-item <?php if(($vo["cate"]) != "2"): ?>disabled<?php endif; ?>" data-checked="0" data-id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["name"]); ?>" style="padding:6px 10px;cursor:pointer;">
                <span class="glyphicon glyphicon-unchecked sd-icon"></span>&nbsp; <?php echo ($vo["_prefix"]); echo ($vo["name"]); ?>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary btn-sm sd-submit"><span class="glyphicon glyphicon-saved"></span> 确定</button>
        <button class="btn btn-danger btn-sm sd-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
    </div>
</div>
<div class="panel panel-default" style="position:absolute;z-index:9999;display:none;" id="select-handle">
    <div class="list-group" style="height:198px;overflow:auto;" id="select-handle-content">
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary btn-sm sd-submit"><span class="glyphicon glyphicon-saved"></span> 确定</button>
        <button class="btn btn-danger btn-sm sd-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
    </div>
</div>


    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/vmc.select.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/Statistics/car.js"></script>

</body>

</html>