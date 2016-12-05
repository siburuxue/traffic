<?php if (!defined('THINK_PATH')) exit();?><table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
    <thead>
        <tr>
            <th class="sort" data-field="case_id">案件编号 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>所属大队</th>
            <th>办案人</th>
            <th class="sort" data-field="accident_time">事故发生时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>事故类型</th>
            <th width="25%" style="padding:0;">
                <table class="sub-table">
                    <tr>
                        <th width="33%">当事人</th>
                        <th width="33%">交通方式</th>
                        <th>车牌号</th>
                    </tr>
                </table>
            </th>
            <th>当前状态</th>
            <th class="sort" data-field="time_limit">时限 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th width="80">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><a href="<?php echo U('CaseInfo/detail?id='.$vo['case_id']);?>" class="js-open js-end-refresh"><?php echo ((isset($vo["case_code"]) && ($vo["case_code"] !== ""))?($vo["case_code"]):'-'); ?></a></td>
                <td><?php echo ((isset($vo["brigade_name"]) && ($vo["brigade_name"] !== ""))?($vo["brigade_name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["case_handle_true_name"]) && ($vo["case_handle_true_name"] !== ""))?($vo["case_handle_true_name"]):'-'); ?></td>
                <td><?php echo (date('Y-m-d H:i',$vo["case_accident_time"])); ?></td>
                <td><?php echo ((isset($vo["accident_type_name"]) && ($vo["accident_type_name"] !== ""))?($vo["accident_type_name"]):'-'); ?></td>
                <td style="padding:0;">
                    <table class="sub-table">
                        <?php if(is_array($vo["client"])): $i = 0; $__LIST__ = $vo["client"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><tr>
                                <td width="33%"><?php echo ((isset($co["name"]) && ($co["name"] !== ""))?($co["name"]):'-'); ?></td>
                                <td width="33%"><?php echo get_custom_config('traffic_type.'.$co['traffic_type']);?></td>
                                <td><?php echo ((isset($co["car_no"]) && ($co["car_no"] !== ""))?($co["car_no"]):'-'); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </td>
                <td><?php echo ((isset($vo["case_status"]) && ($vo["case_status"] !== ""))?($vo["case_status"]):'-'); ?></td>
                <?php if($vo['time_status'] == -1): ?><td style="color:orange"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php elseif($vo['time_status'] == -2): ?>
                    <td style="color:red"><?php echo ($vo["timeLimit"]); ?></td>
                    <?php else: ?>
                    <td><?php echo ($vo["timeLimit"]); ?></td><?php endif; ?>
                <td><a href="<?php echo U('detail?id='.$vo['id']);?>" class="btn btn-primary btn-sm js-open js-end-refresh">操作</a></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>