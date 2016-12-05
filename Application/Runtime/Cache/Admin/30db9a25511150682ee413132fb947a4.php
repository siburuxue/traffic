<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="code">委托书编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>委托人姓名</th>
            <th class="sort" data-field="entrust_time">委托时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>            
            <th>委托内容</th>
            <th>所属大队</th>
            <th>委托事项</th>
            <th>状态</th>
            <th class="sort" data-field="time_limit">时限 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th width="150">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td>
                    <?php if(($vo["code"]) != "0"): echo ((isset($vo["code"]) && ($vo["code"] !== ""))?($vo["code"]):'-'); ?>
                        <?php else: ?>-<?php endif; ?>
                </td>
                <td>
                    <?php if(($vo['carry_name_1'] != '') and ($vo['carry_name_2'] != '')): echo ($vo["carry_name_1"]); ?>、<?php echo ($vo["carry_name_2"]); ?>
                        <?php else: ?> 
                        <?php if(($vo['carry_name_1'] == '') and ($vo['carry_name_2'] == '')): ?>-
                        <?php else: ?>
                        <?php echo ($vo["carry_name_1"]); ?> <?php echo ($vo["carry_name_2"]); endif; endif; ?>
                </td>
                <td><?php echo (date('Y-m-d H:i',(isset($vo["entrust_time"]) && ($vo["entrust_time"] !== ""))?($vo["entrust_time"]):'0')); ?></td>
                <td>
                    <?php if(($vo["case_accident_type"]) == "1"): ?>财产损失事故<?php endif; ?>
                    <?php if(($vo["case_accident_type"]) == "2"): ?>伤人事故<?php endif; ?>
                    <?php if(($vo["case_accident_type"]) == "3"): ?>死亡事故<?php endif; ?>
                </td>
                <td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td>
                    <?php if(($vo["checkup_org_item_pid"]) == "1"): ?>人员：<?php endif; ?>
                    <?php if(($vo["checkup_org_item_pid"]) == "2"): ?>车辆：<?php endif; ?>
                    <?php if(($vo["checkup_org_item_pid"]) == "3"): ?>其他：<?php endif; ?>
                    <?php echo ($vo["checkup_org_item_name"]); ?>
                </td>
                <td>待处理</td>
                <?php if($vo['time_status'] == -1): ?><td style="color:orange"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php elseif($vo['time_status'] == -2): ?>
                    <td style="color:red"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php else: ?>
                    <td><?php echo ($vo["timeLimit"]); ?></td><?php endif; ?>
                <td>
                    <a href="<?php echo U('detail?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">查看</a>
                    <!-- <a href="<?php echo U('delete?id='.$vo['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh">删除</a> -->
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>