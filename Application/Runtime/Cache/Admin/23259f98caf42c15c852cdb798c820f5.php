<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th>所属大队</th>
            <th>办案人</th>
            <th class="sort" data-field="accident_time">事故时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>事故地点</th>
            <th>当事人</th>
            <th>身份证号码</th>
            <th>车号</th>
            <th class="sort" data-field="detain_time">扣押日期 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>是否返还</th>
            <th>扣留物品类别</th>
            <th>物品名称</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ((isset($vo["brigade_name"]) && ($vo["brigade_name"] !== ""))?($vo["brigade_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["case_handle_true_name"]) && ($vo["case_handle_true_name"] !== ""))?($vo["case_handle_true_name"]):'-'); ?></td>
                <td><?php echo (date('Y-m-d H:i',$vo["accident_time"])); ?></td>
                <td><?php echo ((isset($vo["accident_place"]) && ($vo["accident_place"] !== ""))?($vo["accident_place"]):'-'); ?></td>
                <td title="<?php echo ($vo["case_client_true_name"]); ?>"><?php echo (left_str((isset($vo["case_client_true_name"]) && ($vo["case_client_true_name"] !== ""))?($vo["case_client_true_name"]):'-',20)); ?></td>
                <td><?php echo ((isset($vo["case_client_idno"]) && ($vo["case_client_idno"] !== ""))?($vo["case_client_idno"]):'-'); ?></td>
                <td><?php echo ((isset($vo["case_client_car_no"]) && ($vo["case_client_car_no"] !== ""))?($vo["case_client_car_no"]):'-'); ?></td>
                <td>
                    <?php if(!empty($vo["detain_time"])): echo (date('Y-m-d',$vo["detain_time"])); ?>
                        <?php else: ?>-<?php endif; ?>
                </td>
                <td>
                    <?php if(($vo["detain_status"]) == "1"): ?><font style="color:green;">已返还</font>
                        <?php else: ?>
                        <font style="color:red;">已扣押</font><?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($vo["detain_name_id"])): if(($vo["detain_name_id"]) == "1"): ?>机动驾驶证<?php endif; ?>
                        <?php if(($vo["detain_name_id"]) == "2"): ?>其它驾驶证<?php endif; ?>
                        <?php if(($vo["detain_name_id"]) == "3"): ?>其它物品<?php endif; ?>
                        <?php else: ?> -<?php endif; ?>
                </td>
                <td><?php echo ((isset($vo["detain_name"]) && ($vo["detain_name"] !== ""))?($vo["detain_name"]):'-'); ?></td>
                <td>
                   <?php if(($vo["detain_status"]) == "1"): ?><button type="button" class="btn btn-primary btn-sm" disabled="disabled"> 返还</button>
                   <?php else: ?>
                     <button type="button" class="btn btn-primary btn-sm return" data-id="<?php echo ($vo["detain_id"]); ?>"> 返还</button><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>