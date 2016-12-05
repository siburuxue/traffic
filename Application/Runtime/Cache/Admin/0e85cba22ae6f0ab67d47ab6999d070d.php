<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>"  data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="case_id">案件编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th class="sort" data-field="accident_time">事故发生时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>地点</th>
            <th>事故类型</th>
            <th>所属大队</th>
            <th>办案人</th>
            <th>当前状态</th>
            <th class="sort" data-field="time_limit">时限 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><a href="<?php echo U('CaseInfo/detail?id='.$vo['id']);?>" class="js-open js-end-refresh"><?php echo ((isset($vo["code"]) && ($vo["code"] !== ""))?($vo["code"]):'-'); ?></a></td>
                <td><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                <td><?php echo ((isset($vo["accident_place"]) && ($vo["accident_place"] !== ""))?($vo["accident_place"]):'-'); ?></td>
                <td><?php echo ((isset($vo["accident_type_name"]) && ($vo["accident_type_name"] !== ""))?($vo["accident_type_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["case_user"]) && ($vo["case_user"] !== ""))?($vo["case_user"]):'-'); ?></td>
                <td><?php echo ((isset($vo["approve_name"]) && ($vo["approve_name"] !== ""))?($vo["approve_name"]):'-'); ?></td>
                <?php if($vo['time_status'] == -1): ?><td style="color:orange"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php elseif($vo['time_status'] == -2): ?>
                    <td style="color:red"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php else: ?>
                    <td><?php echo ($vo["timeLimit"]); ?></td><?php endif; ?>
                <td>
                    <?php switch($vo["cate"]): case "0": ?><a href="<?php echo U('CaseAcceptInfo?id='.$vo['check_id']);?>" class="btn btn-info btn-sm js-open ">审批</a><?php break;?>
                        <?php case "2": case "4": ?><a href="<?php echo U('reportInfo?id='.$vo['check_id']);?>" class="btn btn-info btn-sm js-open">审批</a><?php break;?>
                        <?php case "3": ?><a href="<?php echo U('terminationInfo?id='.$vo['check_id']);?>" class="btn btn-info btn-sm js-open">审批</a><?php break;?>
                        <?php case "10": case "11": case "12": case "13": ?><a href="<?php echo U('CaseCheckupInfo?id='.$vo['check_id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">审批</a><?php break;?>
                        <?php case "20": ?><a href="<?php echo U('CaseCheckCaseReviewLeader/index?id='.$vo['check_id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">审批</a><?php break; endswitch;?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>