<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
    <tr>
        <th width="25%">机构名称</th>
        <th width="25%">可接受委托大队</th>
        <th>可进行检验鉴定项目</th>
        <th colspan="2">操作</th>
    </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo1["name"]); ?></td>
                <td><?php echo ($vo1["departments"]); ?></td>
                <td><?php echo ($vo1["checknames"]); ?></td>
                <td width="80">
                    <a href="<?php echo U('edit?id='.$vo1['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">编辑</a>
                </td>
                <td width="80">
                    <div data-id="<?php echo ($vo1["id"]); ?>" class="btn btn-warning btn-sm delete-btn">作废</div>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>