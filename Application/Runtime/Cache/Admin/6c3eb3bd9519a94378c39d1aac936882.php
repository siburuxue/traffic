<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-custom" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
        <tr>
            <th width="25%">机构名称</th>
            <th width="25%">可接受委托大队</th>
            <th width="25%">可进行检验鉴定项目</th>
            <th>作废理由</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo1["name"]); ?></td>
                <td><?php echo ($vo1["departments"]); ?></td>
                <td><?php echo ($vo1["checknames"]); ?></td>
                <td style="word-break: break-all"><?php echo ($vo1["del_reason"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>