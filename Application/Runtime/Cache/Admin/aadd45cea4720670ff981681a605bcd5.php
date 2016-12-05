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
    
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_MODULE_URL); ?>/css/sign.css" />

</head>

<body>
    
    <div class="container" id="login">
        <div class="form-signin">
            <input type="text" id="user_name" id="user_name" placeholder="登录名" autofocus maxlength="20">
            <input type="password" id="password" placeholder="密码" maxlength="16">
            <input type="button" id="submit" value="">
        </div>
    </div>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    
    <script type="text/javascript">
    url.loginCheck = "<?php echo U('Sign/loginCheck');?>";
    if (window.top !== window.self) {
        window.top.location = window.location;
    }
    $(function() {
        var submitStatus = 0;
        $(document).on('keypress', function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == 13) {
                $('#submit').trigger('click');
            }
        });

        $('#submit').on('click', function() {
            if (submitStatus == 1) {
                return false;
            }
            submitStatus = 1;
            $.post(url.loginCheck, {
                user_name: $('#user_name').val(),
                password: $('#password').val()
            }, function(msg) {

                if (msg.status == 1) {
                    submitStatus = 0;
                    window.location.href = msg.url;
                } else {
                    layer.alert(msg.info, function(index) {
                        layer.close(index);
                        submitStatus = 0;
                    });
                }

            });
        });
    });
    </script>

</body>

</html>