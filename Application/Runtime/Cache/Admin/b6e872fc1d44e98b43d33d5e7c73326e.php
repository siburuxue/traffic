<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        采血管查询
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
    //ajax读取部门下所有可选值班人员
    url.ajaxAllUsers = "<?php echo U('ajaxAllUsers');?>";
    
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
                    <div class="col-xs-6"><span class="block-title">采血管查询</span></div>
                    <div class="col-xs-6 text-right">

                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板主体 -->
            <div class="panel-body">
                <!-- 搜索 -->
                
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-custom">
                <tr>
                    <?php if(is_power($myPower,'bloodtubecate_advance')): ?><th width="160">被派发大队</th>
                        <td>
                            <select name="condition_target_department_id" class="form-control input-sm search-auto" id="select-department"  style="width:100%;">
                                <option value="">请选择</option>
                                <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["cate"]) != "2"): ?>disabled="disabled" style="color:red;"<?php endif; ?> ><?php echo ($vo["_prefix"]); echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <th width="160">被派发办案人</th>
                        <td>
                            <select name="condition_target_user_id" class="form-control input-sm search-auto" id="select-allUsers"  style="width:100%;">
                                <option value="">请选择</option>
                            </select>
                        </td>
                        <?php else: ?>
                        <th width="160">被派发大队</th>
                        <td>
                            <input type="hidden" name="condition_target_department_id" value="$myBrigade.id" class="form-control input-sm search-auto">
                            <input type="text" name="condition_target_department_id" value="<?php echo ($myBrigade["name"]); ?>" class="form-control input-sm search-auto" readOnly="readOnly"  style="width:100%;">
                        </td>
                        <th width="160">被派发办案人</th>
                        <td>
                            <?php if(is_power($myPower,'bloodtubecate_normal')): ?><select name="condition_target_user_id" class="form-control input-sm search-auto"  style="width:100%;">
                                    <option value="">请选择</option>
                                    <?php if(is_array($allUsers)): $i = 0; $__LIST__ = $allUsers;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["true_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <?php else: ?>
                                <input type="hidden" name="condition_target_user_id" value="$my['id']" class="form-control input-sm search-auto">
                                <input type="text" name="condition_target_user_id" value="<?php echo ($my['true_name']); ?>" class="form-control input-sm search-auto" readOnly="readOnly"><?php endif; ?>
                        </td><?php endif; ?>
                </tr>
                <tr>
                    <th width="15%">派发时间</th>
                    <td width="35%">

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
                    <th width="15%">事故编号</th>
                    <td width="35%">
                        <input type="number" class="form-control input-sm search-auto" name="condition_case_id" maxlength="250" style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th>事故时间</th>
                    <td>
                      <table width="100%">
                            <tr>
                                <td width="48%">
                                    <input type="text" class="form-control input-sm search-auto form-datetime" id="start-time1" name="condition_case_start_time" maxlength="16" readonly="readonly" style="min-width:auto;width:100%;">
                                </td>
                                <td width="4%" align="center"> ~
                                </td>
                                <td width="48%">
                                    <input type="text" class="form-control input-sm search-auto form-datetime" id="end-time1" name="condition_case_end_time" maxlength="16" readonly="readonly" style="min-width:auto;width:100%;">
                                </td>
                        </table>
                    </td>
                    <th>采血管编号</th>
                    <td>
                        <input type="text" class="form-control input-sm search-auto" name="condition_bloodtube_code" maxlength="250"  style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th>是否使用</th>
                    <td>
                        <select name="condition_is_used" class="form-control input-sm search-auto"  style="width:100%;">
                            <option value="">请选择</option>
                            <option value="0">未使用</option>
                            <option value="1">已使用</option>
                        </select>
                    </td>
                    <th>是否回收</th>
                    <td>
                        <select name="condition_is_recover" class="form-control input-sm search-auto"  style="width:100%;">
                            <option value="">请选择</option>
                            <option value="0">未回收</option>
                            <option value="1">已回收</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>回收方式</th>
                    <td colspan="3">
                        <select name="condition_recover_type" class="form-control input-sm search-auto"  style="width:100%;">
                            <option value="">请选择</option>
                            <?php if(is_array($recover_type)): foreach($recover_type as $key=>$vo): ?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <button type="button" class="btn btn-primary" id="search-submit"><span class="glyphicon glyphicon-search"></span> 查询</button>
        <button type="button" class="btn btn-warning" id="search-reset"><span class="glyphicon glyphicon-repeat"></span> 重置</button>


            
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
    

    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/BloodtubeCate/index.js"></script>

</body>

</html>