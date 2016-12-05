<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="alarm_time">报警时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>案件来源</th>
            <th>报警人姓名</th>
            <th>事故发生时间</th>
            <th>事故发生地点</th>
            <th>处理状态</th>
            <th>关联状态</th>
            <th>作废状态</th>
            <th>作废缘由</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo (date('Y-m-d H:i',$vo["alarm_time"])); ?></td>
                <td><?php echo ((isset($vo["case_source_name"]) && ($vo["case_source_name"] !== ""))?($vo["case_source_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["alarm_name"]) && ($vo["alarm_name"] !== ""))?($vo["alarm_name"]):'-'); ?></td>
                <td><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                <td><?php echo ((isset($vo["accident_place"]) && ($vo["accident_place"] !== ""))?($vo["accident_place"]):'-'); ?></td>
                <td>
                    <?php switch($vo["handle_type"]): case "0": ?><span class="text-danger">未处理</span><?php break;?>
                        <?php case "1": ?><span class="text-success">当事人自行处理</span><?php break;?>
                        <?php case "2": ?><span class="text-success">快赔中心处理</span><?php break;?>
                        <?php case "3": ?><span class="text-success">事故中队处理</span><?php break; endswitch;?>
                </td>
                <td>
                    <?php if(($vo["is_link"]) == "1"): ?><span class="text-success">已关联</span>
                        <?php else: ?>
                        <span class="text-danger">未关联</span><?php endif; ?>
                </td>
                <td>
                    <?php if(($vo["is_del"]) == "1"): ?><span class="text-danger">已作废</span>
                        <?php else: ?>
                        <span class="text-success">正常</span><?php endif; ?>
                </td>
                <td><?php echo ((isset($vo["del_reason"]) && ($vo["del_reason"] !== ""))?($vo["del_reason"]):'-'); ?></td>
                <td width="80">
                    <a href="<?php echo U('detail?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open">查看</a>
                </td>
                <td width="80">
                    <?php if(($vo["is_can_action"]) == "1"): ?><a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">修改</a>
                        <?php else: ?>
                        <button type="button" class="btn btn-info btn-sm js-open js-end-refresh" disabled="disabled">修改</button><?php endif; ?>
                </td>
                <td width="80">
                    <?php if(($vo["is_can_action"]) == "1"): ?><button data-id="<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm delete-btn">作废</button>
                        <?php else: ?>
                        <button data-id="<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm delete-btn" disabled="disabled">作废</button><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>