<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
    <tr>
        <th class="sort" data-field="case_id">案件编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
        <th class="sort" data-field="accident_time">事故发生时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
        <th>地点</th>
        <th>事故类型</th>
        <th>当事人</th>
        <th>办案人</th>
        <th>是否逃逸案件</th>
        <th>逃逸案件是否侦破</th>
        <th>当前状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><a href="<?php echo U('CaseInfo/detail?id='.$vo['id']);?>" class="js-open js-end-refresh"><?php echo ($vo["code"]); ?></a></td>
                <td><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                <td><?php echo ($vo["accident_place"]); ?></td>
                <td><?php echo ($accident_type[$vo['accident_type']]); ?></td>
                <td width="120px">
                    <div title="<?php echo ($vo["client_names"]); ?>" style="margin-top:6px;display: inline-block;width:120px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                        <?php echo ($vo["client_names"]); ?>
                    </div>
                </td>
                <td><?php echo ($vo["true_name"]); ?></td>
                <td><?php if($vo['is_escape'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
                <td><?php if($vo['is_catch'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
                <td><?php echo ($vo["case_status"]); ?></td>
                <td width="80">
                    <button class="btn btn-warning btn-sm delete-btn" data-id="<?php echo ($vo["id"]); ?>" data-msg="<?php echo ($vo['forbiddenMsg']); ?>" <?php if($vo['is_del'] == 1 ): ?>disabled<?php endif; ?> >作废</button>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>