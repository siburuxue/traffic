<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
	<thead>
	<tr>
        <th class="sort" data-field="id">编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
		<th>所属大队</th>
		<th>办案人</th>
		<th>车号</th>
		<th>车型</th>
		<th>停放地点</th>
        <th class="sort" data-field="detain_time">进场时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
        <th class="sort" data-field="detain_return_time">放行时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
	</tr>
	</thead>
	<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ((isset($vo["id"]) && ($vo["id"] !== ""))?($vo["id"]):'-'); ?></td>
			<td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
			<td><?php echo ((isset($vo["case_handle_true_name"]) && ($vo["case_handle_true_name"] !== ""))?($vo["case_handle_true_name"]):'-'); ?></td>
			<td><?php echo ((isset($vo["car_no"]) && ($vo["car_no"] !== ""))?($vo["car_no"]):'-'); ?></td>
			<td><?php echo ((isset($vo["grade_type_name"]) && ($vo["grade_type_name"] !== ""))?($vo["grade_type_name"]):'-'); ?></td>
			<td><?php echo ((isset($vo["detain_parking_name"]) && ($vo["detain_parking_name"] !== ""))?($vo["detain_parking_name"]):'-'); ?></td>
			<td><?php echo (date('Y-m-d H:i',$vo["detain_time"])); ?></td>
			<td>
				<?php if(($vo["detain_return_time"]) == "0"): ?>-
					<?php else: ?>
					<?php echo (date('Y-m-d H:i',$vo["detain_return_time"])); endif; ?>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>