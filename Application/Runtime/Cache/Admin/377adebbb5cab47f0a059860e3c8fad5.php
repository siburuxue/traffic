<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>"  data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="case_id">事故编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th class="sort" data-field="accident_time">事故发生时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>地点</th>
            <th>办案人</th>
            <th>当事人</th>
            <th>交通方式</th>
            <th>车牌号</th>
            <th>认定状态</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): foreach($list as $i=>$vo): ?><tr>
                <?php if($list[$i - 1]['id'] != $vo['id'] or $i == 0): ?><td rowspan="<?php echo ($vo['count']); ?>"><a href="<?php echo U('detail?case_id='.$vo['id']);?>"  class="js-open js-end-refresh"><?php echo ($vo["code"]); ?></a></td>
                    <td rowspan="<?php echo ($vo['count']); ?>"><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                    <td rowspan="<?php echo ($vo['count']); ?>"><?php echo ($vo["accident_place"]); ?></td>
                    <td rowspan="<?php echo ($vo['count']); ?>"><?php echo ($vo["true_name"]); ?></td><?php endif; ?>
                <td><?php echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["traffic_type"]) && ($vo["traffic_type"] !== ""))?($vo["traffic_type"]):'-'); ?></td>
                <td><?php echo ((isset($vo["car_no"]) && ($vo["car_no"] !== ""))?($vo["car_no"]):'-'); ?></td>
                <?php if($list[$i - 1]['id'] != $vo['id'] or $i == 0): ?><td rowspan="<?php echo ($vo['count']); ?>"><?php echo ($vo["cognizance_status"]); ?></td><?php endif; ?>
            </tr><?php endforeach; endif; ?>
    </tbody>
</table>