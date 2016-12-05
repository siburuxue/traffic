<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-custom" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
    <tr>
        <th>名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($dict)): foreach($dict as $k=>$vo): ?><tr>
            <td colspan="2">|--&nbsp;&nbsp;<?php echo ($vo); ?></td>
        </tr>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i; if($vo1['checkup_org_obj'] == $k): ?><tr>
                    <td>|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--&nbsp;&nbsp;<?php echo ($vo1["name"]); ?></td>
                    <td>
                        <a href="<?php echo U('edit?id='.$vo1['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">编辑</a>
                        <a href="<?php echo U('delete?id='.$vo1['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh">删除</a>
                    </td>
                </tr><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; ?>
    </tbody>
</table>