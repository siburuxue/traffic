<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table">
    <thead>
    <tr>
        <th>文件夹名</th>
        <th>文件数</th>
        <th colspan="4">操作</th>
    </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td style="text-align: left"><?php echo ($vo["_prefix"]); echo ($vo["title"]); ?></td>
                <td><?php echo ($vo["count"]); ?></td>
                <td width="60"><a href="<?php echo U('viewFile?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh view">查看</a></td>
                <td width="60"><a href="<?php echo U('editFolder?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh edit">编辑</a></td>
                <td width="60"><a href="javascript:;" class="btn btn-warning btn-sm del">删除</a></td>
                <td width="90">
                    <?php if($vo['level'] != 0): ?><a href="javascript:;" class="btn btn-info btn-sm" disabled="">查看全部</a>
                    <?php else: ?>
                        <a href="<?php echo U('viewAll?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open view-all">查看全部</a><?php endif; ?>
                </td>
                <td style="display: none"><input type="hidden" value="<?php echo ($vo["id"]); ?>"></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>