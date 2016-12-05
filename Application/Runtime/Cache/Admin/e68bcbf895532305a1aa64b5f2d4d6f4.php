<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
        <tr>
            <th width="30%">大队</th>
            <th width="10%">组别</th>
            <th width="">名称</th>
            <th width="70">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td><?php echo ($dutyGroupType[$vo['group_type']]); ?></td>
                <td><?php echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'-'); ?></td>
                <td>
                    <a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh"><!-- <span class="glyphicon glyphicon-edit"></span> -->编辑</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>