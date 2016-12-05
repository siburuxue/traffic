<?php if (!defined('THINK_PATH')) exit();?>

<table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>"  data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th width="50"></th>
            <th class="sort" data-field="case_id">事故编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th class="sort" data-field="accident_time">事故发生时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>地点</th>
            <th>事故类型</th>
            <th>当事人</th>
            <th>办案人</th>
            <th>是否逃逸</th>
            <th>逃逸案件是否侦破</th>
            <th>当前状态</th>
            <th>预计案结时间</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td>
                    <input class="selectAllcheckbox" name="get_attention"  type="checkbox"  value="<?php echo ($vo["id"]); ?>"/>
                </td>
                <td><a href="<?php echo U('CaseInfo/detail?id='.$vo['id']);?>" class="js-open js-end-refresh"><?php echo ((isset($vo["code"]) && ($vo["code"] !== ""))?($vo["code"]):'-'); ?></a></td>
                <td><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                <td><?php echo ((isset($vo["accident_place"]) && ($vo["accident_place"] !== ""))?($vo["accident_place"]):'-'); ?></td>
                <td><?php echo ((isset($vo["accident_type_name"]) && ($vo["accident_type_name"] !== ""))?($vo["accident_type_name"]):'-'); ?></td>
                <td title="<?php echo ($vo["case_client_names"]); ?>"><?php echo (left_str((isset($vo["case_client_names"]) && ($vo["case_client_names"] !== ""))?($vo["case_client_names"]):'-',20)); ?></td>
                <td><?php echo ((isset($vo["case_handle_true_name"]) && ($vo["case_handle_true_name"] !== ""))?($vo["case_handle_true_name"]):'-'); ?></td>
                <td>
                    <?php if(($vo["is_escape"]) == "1"): ?>是
                        <?php else: ?>否<?php endif; ?>
                </td>
                <td>
                    <?php if(($vo["is_escape"]) == "0"): ?>非逃逸事故
                        <?php else: ?>
                        <?php if(($vo["is_catch"]) == "0"): ?><span class="text-danger">未侦破</span>
                            <?php else: ?>
                            <span class="text-success">已侦破</span><?php endif; endif; ?>
                </td>
                <td><?php echo ((isset($vo["case_status"]) && ($vo["case_status"] !== ""))?($vo["case_status"]):'-'); ?></td>
                <td>-</td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>

    </tbody>

</table>