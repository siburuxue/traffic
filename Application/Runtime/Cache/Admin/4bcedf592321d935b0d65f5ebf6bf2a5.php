<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>">
    <thead>
        <tr>
            <th>登录名</th>
            <th>姓名</th>
            <th>警号</th>
            <th>队别</th>
            <th>职务</th>
            <th>用户角色</th>
            <th>联系方式</th>
            <th>事故处理等级</th>
            <th colspan="3">操作</th>
            <th>状态</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ((isset($vo["user_name"]) && ($vo["user_name"] !== ""))?($vo["user_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["true_name"]) && ($vo["true_name"] !== ""))?($vo["true_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["police_no"]) && ($vo["police_no"] !== ""))?($vo["police_no"]):'-'); ?></td>
                <td><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["post_name"]) && ($vo["post_name"] !== ""))?($vo["post_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["role_name"]) && ($vo["role_name"] !== ""))?($vo["role_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["tel"]) && ($vo["tel"] !== ""))?($vo["tel"]):'-'); ?></td>
                <td><?php echo ((isset($vo["traffic_level_name"]) && ($vo["traffic_level_name"] !== ""))?($vo["traffic_level_name"]):'-'); ?></td>
                <td>
                    <a href="<?php echo U('resetPassword?id='.$vo['id']);?>" class="btn btn-info btn-sm js-ajax js-confirm">重置密码</a>
                </td>
                <td>
                    <a href="<?php echo U('edit?id='.$vo['id']);?>" class="btn btn-info btn-sm js-open js-end-refresh">编辑</a>
                </td>
                <td>
                    <a href="<?php echo U('delete?id='.$vo['id']);?>" class="btn btn-warning btn-sm js-ajax js-confirm js-end-refresh">删除</a>
                </td>
                <td>
                    <?php if(($vo["is_locked"]) == "1"): ?><a href="<?php echo U('resume?id='.$vo['id']);?>" class="btn btn-danger btn-sm js-ajax js-confirm js-end-refresh">已禁用</a>
                        <?php else: ?>
                        <a href="<?php echo U('forbid?id='.$vo['id']);?>" class="btn btn-success btn-sm js-ajax js-confirm js-end-refresh">已激活</a><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>