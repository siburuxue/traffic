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
    
</head>

<body>
    
    <div class="container-fluid main">
        <!-- 页头 -->
        <div class="head-space"></div>
        <!-- 面板 -->
        <div class="panel panel-default">
            <!-- 面板标题 -->
            <div class="panel-heading">
                <span class="block-title">昨日道路交通事故情况</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="well text-center" style="margin-bottom:0;">
                            <h3>交通事故起数</h3>
                            <h1>12</h1>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="well text-center" style="margin-bottom:0;">
                            <h3>死亡人数</h3>
                            <h1>12</h1>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="well text-center" style="margin-bottom:0;">
                            <h3>扣留车辆台数</h3>
                            <h1>12</h1>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="well text-center" style="margin-bottom:0;">
                            <h3>验血人数</h3>
                            <h1>12</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6" id="container"></div>
                    <div class="col-xs-6" id="container1"></div>
                </div>
            </div>
        </div>

               <div class="panel panel-default">
    <div class="panel-heading">
        <span class="block-title">我的待办工作</span>
    </div>
    <div class="panel-body">
        <div class="table-responsive" id="table-case-check"></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="block-title">重点关注案件</span>
    </div>
    <div class="panel-body">
        <div class="table-responsive" id="table-case-attention"></div>
    </div>
</div>
<script type="text/javascript">
url.tableCheck = "<?php echo U('CaseCheckLeader/pendingTable');?>";
url.tableAttention = "<?php echo U('CaseAttention/completedTable');?>";

$(function() {
    var load = function() {
        $('#table-case-check').load(url.tableCheck);
        $('#table-case-attention').load(url.tableAttention);
    };
    load();
});
</script>


               

    </div>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/highcharts/highcharts.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/highcharts/highcharts-3d.js"></script>
    <script type="text/javascript">
    $(function() {
        $('#container').highcharts({
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            credits: {
                enabled: false,

            },
            title: {
                text: '扣押车辆类型'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 50,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '比例',
                data: [
                    ['中山区', 45.0],
                    ['西岗区', 26.8], {
                        name: '沙河口区',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['甘井子区', 8.5],
                    ['金州区', 6.2],
                    ['开发区', 0.7]
                ]
            }]
        });
    });

    $(function() {
        $('#container1').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '交通事故伤亡情况'
            },
            subtitle: {
                text: ''
            },
            credits: {
                enabled: false,
            },
            xAxis: {
                categories: [
                    '一月',
                    '二月',
                    '三月',
                    '四月',
                    '五月',
                    '六月',
                    '七月',
                    '八月',
                    '九月',
                    '十月',
                    '十一月',
                    '十二月'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: '人数(只)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} 人</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: '死亡',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

            }, {
                name: '重伤',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

            }, {
                name: '轻伤',
                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

            }, {
                name: '失踪',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

            }]
        });
    });
    </script>

    
    <div style="height:20px;"></div>

    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo (SRC_COMMON_URL); ?>/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
        
    
</body>

</html>