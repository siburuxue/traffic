<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        支队采血管派发
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
    url.update = "<?php echo U('grantDetachmentInsert');?>";
    </script>

</head>

<body>
    
    <!-- 页面主体 -->
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <!-- 面板 -->
        <div class="panel panel-default form-inline">
            <!-- 面板标题 -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6"><span class="block-title">支队采血管派发</span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板内容 -->
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-custom">
                    <tr>
                        <th width="15%">采血管编号</th>
                        <td width="35%">
                            <table width="100%">
                                <tr>
                                    <td width="48%">
                                        <input type="text" class="form-control input-sm post-gather" name="code_1" maxlength="250" style="width:100%;"/>
                                    </td>
                                    <td width="4%" align="center"> ~
                                    </td>
                                    <td width="48%">
                                        <input type="text" class="form-control input-sm post-gather" name="code_2" maxlength="250"  style="width:100%;"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <th width="15%">派发时间</th>
                        <td width="35%">
                            <input type="text" class="form-control input-sm post-gather form-datetime" id="start-time" name="to_department_time" maxlength="16" readonly="readonly" value="<?php echo (date('Y-m-d H:i',$nowtime)); ?>" style="width:100%;">
                        </td>
                    </tr>
                    <tr>
                        <th>派发人</th>
                        <td>
                            <input type="text" class="form-control input-sm " maxlength="16" value="<?php echo ($my["true_name"]); ?>" readonly="readonly" style="width:100%;">
                            <input type="hidden" class="form-control input-sm post-gather" name="to_department_user_id" value="<?php echo ($my["id"]); ?>" maxlength="16" style="width:100%;">
                        </td>
                        <th>派发大队</th>
                        <td>
                            <select name="target_department_id" class="form-control input-sm post-gather" id="select-department" style="width:100%;">
                                <option value="">请选择</option>
                                <?php if(is_array($department)): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["cate"]) != "2"): ?>disabled="disabled" style="color:red;"<?php endif; ?> ><?php echo ($vo["_prefix"]); echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-primary" id="submit"><span class="glyphicon glyphicon-saved"></span> 提交</button>
                <button type="button" class="btn btn-warning" id="reset"><span class="glyphicon glyphicon-repeat"></span> 重置</button>
            </div>
        </div>
    </div>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/BloodtubeCate/grantDetachment.js"></script>

</body>

</html>