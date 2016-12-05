<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table">
    <thead>
        <tr>
            <th>部门名称</th>
            <th width="10%">类型</th>
            <th width="10%">行政区划代码</th>
            <th colspan="2">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td style="text-align: left;"><?php echo ($vo["_prefix"]); echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'-'); ?></td>
                <td><?php echo ($departmentType[$vo['cate']]); ?></td>
                <td><?php echo ((isset($vo["area_code"]) && ($vo["area_code"] !== ""))?($vo["area_code"]):'-'); ?></td>
                <td width="70">
                    <a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">编辑</a>
                </td>
                <td width="70">
                    <a href="<?php echo U('delete?id='.$vo['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh">删除</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>