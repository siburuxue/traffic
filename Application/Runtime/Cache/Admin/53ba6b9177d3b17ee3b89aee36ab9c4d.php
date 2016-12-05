<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title><?php echo (C("custom.sitename")); ?>管理平台</title>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_MODULE_URL); ?>/css/frame.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/jquery/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/layer/layer.js"></script>
    <script type="text/javascript">
    $(function() {
        var winresize = function() {
            var wrapHeight = $(window).height() - $('#topbar').outerHeight(true);
            var windowWidth = $(window).width();
            $('#wrap').height(wrapHeight);
            if (windowWidth <= 1200) {
                $('.handle').show();
                $('.main').css('marginLeft', 15);
                $('.side').css('left', -220);
            } else {
                $('.handle').hide();
                $('.main').css('marginLeft', 220);
                $('.side').css('left', 0);
            }
        };
        winresize();
        $(window).on('resize', winresize);

        $('.handle').on('mouseenter', function() {
            if ($(window).width() <= 1200) {
                $('.side').stop(true).animate({
                    left: 0
                }, 200, function() {
                    $('.handle').hide();
                });
            }
        });
        $('.side').on('mouseleave', function() {
            if ($(window).width() <= 1200) {
                $(this).stop(true).animate({
                    left: -220
                }, 200);
                $('.handle').show();
            }
        });
    });
    </script>
</head>

<body scroll="no">
    <div class="topbar" id="topbar">
        <a href="<?php echo U('Index/index');?>" class="logo"><?php echo (C("custom.sitename")); ?></a>
        <ul class="tool">
            <div class="avatar"></div>
            <div class="user-name"><?php echo ($my["true_name"]); ?></div>
            <div class="toolmenu">
                <a href="<?php echo U('Index/main');?>" target="mainframe">管理首页</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo U('Index/password');?>" target="mainframe">修改密码</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo U('Sign/logout');?>">退出</a>
            </div>
        </ul>
    </div>
    <div class="wrap" id="wrap">
        <div class="handle">
            <div class="triangle"></div>
        </div>
        <div class="side">
            <iframe src="<?php echo U('Index/side');?>" name="sideframe" id="sideframe" frameborder="no" scrolling="auto"></iframe>
        </div>
        <div class="main">
            <iframe src="<?php echo U('Index/main');?>" name="mainframe" id="mainframe" frameborder="no" scrolling="yes"></iframe>
        </div>
    </div>
</body>

</html>