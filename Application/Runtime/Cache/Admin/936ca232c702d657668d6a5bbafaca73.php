<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="start_time">值班日期 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th width="20%">部门(大队)</th>
            <th width="20%">组别</th>
            <th width="10%">值班人员</th>
            <th colspan="2">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo (date('Y-m-d H:i',$vo["start_time"])); ?> --- <?php echo (date('Y-m-d H:i',$vo["end_time"])); ?></td>
                <td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td>
                    <?php echo ($dutyGroupType[$vo['duty_group_group_type']]); ?>
                </td>
                <td><?php echo ((isset($vo["true_name"]) && ($vo["true_name"] !== ""))?($vo["true_name"]):'-'); ?></td>
                <td width="70">
                    <a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh"><!-- <span class="glyphicon glyphicon-edit"></span> -->编辑</a>
                </td>
                <td width="70">
                    <a href="<?php echo U('delete?id='.$vo['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh"><!-- <span class="glyphicon glyphicon-trash"></span> -->删除</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>