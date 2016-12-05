<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>
        维护日历信息
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
    
    <style>
        .search{display: block;width:100%;height:auto;margin:50px 0 50px 0;text-align: center}
        .calender{display: block;width:100%;height:auto;text-align: center}
        .head{display: inline-block;text-align: left;width: 560px;}
        .title{
            display: inline-block;
            width:80px;
            height:80px;
            line-height: 80px;
            background:#47A4D3;
            font-weight:bold;
            color:#FFF;
            text-align: center;
            float:left;
            border-right:1px solid #c9c9c9;
            border-top:1px solid #c9c9c9;
            border-bottom:1px solid #c9c9c9;
            font-size: 20px;
        }
        .title:nth-of-type(1){border-left:1px solid #c9c9c9;color:red}
        .title:last-of-type{color:red}
        .info{display: block;text-align: center;width: 100%;margin-top: -5px;}
        .rows{display: inline-block;text-align: left;width: 560px;}
        .cell{
            cursor:default;
            display: inline-block;
            width:80px;
            height:80px;
            line-height: 80px;
            text-align: center;
            float:left;
            border-right:1px solid #c9c9c9;
            border-bottom:1px solid #c9c9c9;
            font-weight: normal;
            font-size: 30px;
        }
        .cell:nth-of-type(7n + 1){border-left:1px solid #c9c9c9;}
        /*.cell:nth-of-type(7n){color:red}*/
        .active{background: #D9ffff}
        #month{width: 263px;height:40px;margin-left:30px;font-size:15px;}
        #year{width: 263px;height:40px;font-size:15px;}
        .red{color:red;}
    </style>
    <script type="text/javascript">
        // 定义URL地址
        url.update = "<?php echo U('update');?>";
        url.getDays = "<?php echo U('getDays');?>";
    </script>

</head>

<body>
    
    <!-- 页面主体 -->
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <!-- 基础信息 -->
        <div class="panel panel-default form-inline">
            <!-- 面板标题 -->
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6"><span class="block-title">维护日历信息</span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn btn-danger btn-sm js-close"><span class="glyphicon glyphicon-off"></span> 关闭</button>
                    </div>
                </div>
            </div>
            <!-- 面板内容 -->
            <div class="panel-body" style="text-align: center!important;">
                <div class="search">
                    <select id="year" name="year" class="form-control input-sm post-gather">
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                        <option value="2031">2031</option>
                        <option value="2032">2032</option>
                        <option value="2033">2033</option>
                        <option value="2034">2034</option>
                        <option value="2035">2035</option>
                        <option value="2036">2036</option>
                        <option value="2037">2037</option>
                        <option value="2038">2038</option>
                        <option value="2039">2039</option>
                        <option value="2040">2040</option>
                        <option value="2041">2041</option>
                        <option value="2042">2042</option>
                        <option value="2043">2043</option>
                        <option value="2044">2044</option>
                        <option value="2045">2045</option>
                        <option value="2046">2046</option>
                        <option value="2047">2047</option>
                        <option value="2048">2048</option>
                        <option value="2049">2049</option>
                        <option value="2050">2050</option>
                        <option value="2051">2051</option>
                        <option value="2052">2052</option>
                        <option value="2053">2053</option>
                        <option value="2054">2054</option>
                        <option value="2055">2055</option>
                        <option value="2056">2056</option>
                        <option value="2057">2057</option>
                        <option value="2058">2058</option>
                        <option value="2059">2059</option>
                        <option value="2060">2060</option>
                        <option value="2061">2061</option>
                        <option value="2062">2062</option>
                        <option value="2063">2063</option>
                        <option value="2064">2064</option>
                        <option value="2065">2065</option>
                        <option value="2066">2066</option>
                    </select>
                    <select id="month" name="month" class="form-control input-sm post-gather">
                        <option value="1">1月</option>
                        <option value="2">2月</option>
                        <option value="3">3月</option>
                        <option value="4">4月</option>
                        <option value="5">5月</option>
                        <option value="6">6月</option>
                        <option value="7">7月</option>
                        <option value="8">8月</option>
                        <option value="9">9月</option>
                        <option value="10">10月</option>
                        <option value="11">11月</option>
                        <option value="12">12月</option>
                    </select>
                </div>
                <div class="calender">
                    <div class="head">
                        <div class="title">SUN</div>
                        <div class="title">MON</div>
                        <div class="title">TUE</div>
                        <div class="title">WED</div>
                        <div class="title">THU</div>
                        <div class="title">FRI</div>
                        <div class="title">SAT</div>
                    </div>
                    <div class="info">
                        <div class="rows"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    

    <script type="text/javascript" src="<?php echo (SRC_MODULE_URL); ?>/js/Calendar/index.js"></script>

</body>

</html>