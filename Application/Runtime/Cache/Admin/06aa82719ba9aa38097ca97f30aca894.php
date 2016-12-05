<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="id">编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>所属大队</th>
            <th>办案人</th>
            <th class="sort" data-field="accident_time">事故时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>事故地点</th>
            <th>被提取人姓名</th>
            <th>抗凝瓶唯一编码</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td rowspan="2"><?php echo ((isset($vo["id"]) && ($vo["id"] !== ""))?($vo["id"]):'-'); ?></td>
                <td rowspan="2"><?php echo ((isset($vo["department_name"]) && ($vo["department_name"] !== ""))?($vo["department_name"]):'-'); ?></td>
                <td rowspan="2"><?php echo ((isset($vo["handle_true_name"]) && ($vo["handle_true_name"] !== ""))?($vo["handle_true_name"]):'-'); ?></td>
                <td rowspan="2"><?php echo (date('Y-m-d H:i',$vo["case_accident_time"])); ?></td>
                <td rowspan="2"><?php echo ((isset($vo["case_accident_place"]) && ($vo["case_accident_place"] !== ""))?($vo["case_accident_place"]):'-'); ?></td>
                <td rowspan="2"><?php echo ((isset($vo["case_client_name"]) && ($vo["case_client_name"] !== ""))?($vo["case_client_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["bloodtube"]["0"]["code"]) && ($vo["bloodtube"]["0"]["code"] !== ""))?($vo["bloodtube"]["0"]["code"]):'-'); ?></td>
            </tr>
            <tr>
                <td><?php echo ((isset($vo["bloodtube"]["1"]["code"]) && ($vo["bloodtube"]["1"]["code"] !== ""))?($vo["bloodtube"]["1"]["code"]):'-'); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>