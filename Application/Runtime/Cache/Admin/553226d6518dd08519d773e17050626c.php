<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="list-group-item" data-checked="0" data-id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["true_name"]); ?>" style="padding:6px 10px;cursor:pointer;">
        <span class="glyphicon glyphicon-unchecked sd-icon"></span>&nbsp; <?php echo ($vo["true_name"]); ?>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>