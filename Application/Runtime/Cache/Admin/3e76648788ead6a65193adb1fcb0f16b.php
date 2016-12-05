<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
	<thead>
	<tr>
        <th class="sort" data-field="id">编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
		<th>所属大队</th>
		<th>办案人</th>
		<th>被鉴定人（车、物）</th>
        <th class="sort" data-field="accident_time">事故时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
		<th>检验鉴定时间</th>
		<th>鉴定项目</th>
		<th>受委托单位</th>
        <th class="sort" data-field="finish_time">结论做出时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
		
	</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ((isset($vo["id"]) && ($vo["id"] !== ""))?($vo["id"]):'-'); ?></td>
			<td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
			<td><?php echo ((isset($vo["handle_true_name"]) && ($vo["handle_true_name"] !== ""))?($vo["handle_true_name"]):'-'); ?></td>
			<td>
				<?php switch($vo["checkup_org_item_pid"]): case "1": echo ($vo["target_case_client_name"]); break;?>
					<?php case "2": echo ($vo["target_case_car_no"]); break;?>
					<?php case "3": echo ($vo["target_other"]); break;?>
					<?php default: ?>
					-<?php endswitch;?>
			</td>
			<td><?php echo (date('Y-m-d H:i',$vo["case_accident_time"])); ?></td>
			<td><?php echo (date('Y-m-d H:i',$vo["create_time"])); ?></td>
			<td><?php echo ((isset($vo["checkup_org_item_name"]) && ($vo["checkup_org_item_name"] !== ""))?($vo["checkup_org_item_name"]):'-'); ?></td>
			<td title="<?php echo ($vo["checkup_org_name"]); ?>"><?php echo (left_str((isset($vo["checkup_org_name"]) && ($vo["checkup_org_name"] !== ""))?($vo["checkup_org_name"]):'-',18)); ?></td>
			<td>
				<?php if(!empty($vo["case_checkup_report_finish_time"])): echo (date('Y-m-d H:i',$vo["case_checkup_report_finish_time"])); ?>
					<?php else: ?>
					-<?php endif; ?>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>