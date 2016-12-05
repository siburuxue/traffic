<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        我的工作
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
                    <div class="col-xs-6"><span class="block-title">我的工作</span></div>
                    <div class="col-xs-6 text-right">
                        <a href="<?php echo U('add');?>" class="btn btn-primary btn-sm js-open js-end-refresh"><span class="glyphicon glyphicon-plus"></span> 新增</a>
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
            <th width="15%">当事人姓名</th>
            <td width="35%">
                <input type="text" class="form-control input-sm search-auto" name="condition_case_client_true_name" maxlength="255">
            </td>
            <th width="15%">车牌号</th>
            <td>
                <input type="text" class="form-control input-sm search-auto" name="condition_case_client_car_no" maxlength="255">
            </td>
        </tr>
        <tr>
            <th>事故发生时间</th>
            <td colspan="3">
                <table width="40.7%">
                    <tr>
                        <td>
                            <input type="text" class="form-control input-sm search-auto form-datetime" name="condition_start_time" readonly="readonly">
                        </td>
                        <td width="30" class="text-center">~</td>
                        <td>
                            <input type="text" class="form-control input-sm search-auto form-datetime" name="condition_end_time" readonly="readonly">
                        </td>
                    </tr>
                </table>
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

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/FastProcess/index.js"></script>

</body>

</html>