<?php if (!defined('THINK_PATH')) exit();?><div style="width: 2200px">
    <table class="table table-striped table-bordered table-hover table-condensed table-sheet" id="table" data-totalrows="<?php echo ($page["totalrows"]); ?>" data-totalpage="<?php echo ($page["totalpage"]); ?>" data-nowpage="<?php echo ($page["nowpage"]); ?>" style="width: 2500px;margin-bottom: 0px"  data-orderfield="<?php echo ($orderField); ?>" data-ordersort="<?php echo ($orderSort); ?>">
        <thead>
        <tr>
            <th>序号</th>
            <th>所属大队</th>
            <th class="sort" data-field="accident_time">事故时间 <span class="glyphicon glyphicon-arrow-up" style="display: none;"></span></th>
            <th>事故地点</th>
            <th>事故类型</th>
            <th>事故后果</th>
            <th>初查情况</th>
            <th>姓名</th>
            <th>交通方式</th>
            <th>车牌号</th>
            <th>违法行为</th>
            <th>伤害程度</th>
            <th>行政强制措施</th>
            <th>事故责任</th>
            <th>处罚</th>
            <th>责任认定时间</th>
            <th>调解</th>
            <th>当前状态</th>
            <th>办案人</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($allArray)): foreach($allArray as $i=>$vo): ?><tr>
                <?php if($allArray[$i - 1]['id'] != $vo['id']): ?><td rowspan="<?php echo ($vo["count"]); ?>" class="sequence"></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["brigadeName"]); ?></td>
                    <?php if($vo['accidentTime'] == 0 or $vo['accidentTime'] == ''): ?><td rowspan="<?php echo ($vo["count"]); ?>">-</td>
                        <?php else: ?>
                        <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo (date('Y-m-d H:i',$vo["accidentTime"])); ?></td><?php endif; ?>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["accidentPlace"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["accidentType"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["result"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["firstCognizance"]); ?></td><?php endif; ?>
                <td><?php echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'-'); ?></td>
                <td><?php echo ((isset($vo["trafficType"]) && ($vo["trafficType"] !== ""))?($vo["trafficType"]):'-'); ?></td>
                <td><?php echo ((isset($vo["carNo"]) && ($vo["carNo"] !== ""))?($vo["carNo"]):'-'); ?></td>
                <td><?php echo ((isset($vo["law"]) && ($vo["law"] !== ""))?($vo["law"]):'-'); ?></td>
                <td><?php echo ((isset($vo["hurtType"]) && ($vo["hurtType"] !== ""))?($vo["hurtType"]):'-'); ?></td>
                <td><?php echo ((isset($vo["coerciveMeasure"]) && ($vo["coerciveMeasure"] !== ""))?($vo["coerciveMeasure"]):'-'); ?></td>
                <td><?php echo ((isset($vo["blameType"]) && ($vo["blameType"] !== ""))?($vo["blameType"]):'-'); ?></td>
                <td><?php echo ((isset($vo["punishStatus"]) && ($vo["punishStatus"] !== ""))?($vo["punishStatus"]):'-'); ?></td>
                <?php if($allArray[$i - 1]['id'] != $vo['id']): if($vo['cognizanceTime'] == 0 or $vo['cognizanceTime'] == ''): ?><td rowspan="<?php echo ($vo["count"]); ?>">-</td>
                        <?php else: ?>
                        <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo (date('Y-m-d H:i',$vo["cognizanceTime"])); ?></td><?php endif; ?>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["mediateStatus"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["status"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["handleName"]); ?></td><?php endif; ?>
            </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
</div>