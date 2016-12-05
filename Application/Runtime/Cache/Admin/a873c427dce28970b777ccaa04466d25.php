<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        采血管回收
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
    url.update = "<?php echo U('recoverUpdate');?>";
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
                    <div class="col-xs-6"><span class="block-title">采血管回收</span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板内容 -->
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-custom">
                    <tr>
                        <th width="15%">回收方式</th>
                        <td width="35%">
                            <select name="recover_type" class="form-control input-sm post-gather" id="select-department" style="width:100%">
                                <option value="">请选择</option>
                                <?php if(is_array($recover_type)): foreach($recover_type as $key=>$vo): ?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td>
                        <th width="15%">回收时间</th>
                        <td width="35%">
                            <input type="text" class="form-control input-sm post-gather form-datetime" id="start-time" name="recover_time" maxlength="16" value="<?php echo (date('Y-m-d H:i',$nowtime)); ?>" readonly="readonly"  style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <th>采血管编号</th>
                        <td>
                            <input type="text" class="form-control input-sm post-gather" maxlength="250" name="code"  style="width:100%">
                        </td>
                        <th>回收人</th>
                        <td>
                            <input type="text" class="form-control input-sm " maxlength="16" value="<?php echo ($my["true_name"]); ?>" readonly="readonly"  style="width:100%">
                            <input type="hidden" class="form-control input-sm post-gather" name="recover_user_id" value="<?php echo ($my["id"]); ?>" maxlength="16">
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
    

    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/BloodtubeCate/recover.js"></script>

</body>

</html>