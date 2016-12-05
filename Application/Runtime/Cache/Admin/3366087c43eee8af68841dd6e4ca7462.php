<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
        <tr>
            <th>字段名</th>
            <th>中文名</th>
            <th>备注</th>
            <th>是否可编辑</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["title"]) && ($vo["title"] !== ""))?($vo["title"]):'-'); ?></td>
                <td><?php echo ((isset($vo["remark"]) && ($vo["remark"] !== ""))?($vo["remark"]):'-'); ?></td>
                <td><?php echo ($vo['is_custom']?'是':'否'); ?></td>
                <td width="90">
                    <a href="<?php echo U('DictOption/index?dict_id='.$vo['id']);?>" class="btn btn-info btn-sm js-open">属性维护</a>
                </td>
                <td width="70">
                    <a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">编辑</a>
                </td>
                <td width="70">
                    <a href="<?php echo U('delete?id='.$vo['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh">删除</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>