<?php if (!defined('THINK_PATH')) exit();?>
<a href="<?php echo U('expExcel?id='.$excelData);?>" class="btn btn-warning btn-sm " style="float:right;"><span class="glyphicon glyphicon-save-file"></span>导出Excel</a>
    <div style="width:100%;height:10px;float:left;"></div>
<table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <!-- <thead> -->
    <tr>
        <th>被派发大队</th>
        <th>被派发办案人</th>
        <th>派发时间</th>
        <th class="sort" data-field="accident_time">事故时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
        <th>事故时间</th>
        <th>采血管编号</th>
        <th>是否使用</th>
        <th>是否回收</th>
        <th>回收方式</th>
    </tr>
    <!-- </thead> -->
    <tbody>
        <tr class="table_content">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="table_content">               
                    <td rowspan="2" valign="middle"><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):"-"); ?></td>
                    <td rowspan="2" valign="middle"><?php echo ((isset($vo["true_name"]) && ($vo["true_name"] !== ""))?($vo["true_name"]):"-"); ?></td>
                    <td rowspan="2" valign="middle">
                        <?php if(!empty($vo["to_user_time"])): echo (date('Y-m-d H:i',$vo["to_user_time"])); ?>
                            <?php else: ?>-<?php endif; ?>
                    </td>
                    <td rowspan="2" valign="middle"><?php echo ($vo["case_id"]); ?></td>
                    <td rowspan="2" valign="middle">
                        <?php if(!empty($vo["case_accident_time"])): echo (date('Y-m-d H:i',$vo["case_accident_time"])); ?>
                            <?php else: ?>-<?php endif; ?>
                    </td>
                    <td><?php echo ($vo["bloodtube"]["0"]["code"]); ?></td>
                    <td>
                        <?php if(($vo["is_used"]) == "0"): ?>未使用<?php endif; ?>
                        <?php if(($vo["is_used"]) == "1"): ?><font style="color:red;">已使用</font><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($vo["bloodtube"]["0"]["is_recover"]) == "0"): ?><font style="color:red;">未回收</font>
                            <?php else: ?>
                            <font style="color:yellowgreen;">已回收</font><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($vo["bloodtube"]["0"]["recover_type"]) == "0"): ?>-<?php endif; ?>
                        <?php if(($vo["bloodtube"]["0"]["recover_type"]) == "1"): ?>检验鉴定回收<?php endif; ?>
                        <?php if(($vo["bloodtube"]["0"]["recover_type"]) == "2"): ?>备份血样<?php endif; ?>
                        <?php if(($vo["bloodtube"]["0"]["recover_type"]) == "3"): ?>失效管回收<?php endif; ?>
                    </td>
                </tr>
                <tr class="table_content">
                    <td><?php echo ($vo["bloodtube"]["1"]["code"]); ?></td>
                    <td>
                        <?php if(($vo["is_used"]) == "0"): ?>未使用<?php endif; ?>
                        <?php if(($vo["is_used"]) == "1"): ?><font style="color:red;">已使用</font><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($vo["bloodtube"]["1"]["is_recover"]) == "0"): ?><font style="color:red;">未回收</font>
                            <?php else: ?>
                            <font style="color:yellowgreen;">已回收</font><?php endif; ?>
                    </td>
                    <td>
                        <?php if(($vo["bloodtube"]["1"]["recover_type"]) == "0"): ?>-<?php endif; ?>
                        <?php if(($vo["bloodtube"]["1"]["recover_type"]) == "1"): ?>检验鉴定回收<?php endif; ?>
                        <?php if(($vo["bloodtube"]["1"]["recover_type"]) == "2"): ?>备份血样<?php endif; ?>
                        <?php if(($vo["bloodtube"]["1"]["recover_type"]) == "3"): ?>失效管回收<?php endif; ?>
                    </td>                  
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tr>
    </tbody>
</table>