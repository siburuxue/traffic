<?php
namespace Admin\Model;

class CasePunishModel extends CommonModel {

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('punish_fine_money', 'is_money', '金额必须是数字', 2,'function'),
        array('punish_fine_score', 'number', '扣分必须是数字', 2),
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
        array('is_del', 0),
    );
}
?>