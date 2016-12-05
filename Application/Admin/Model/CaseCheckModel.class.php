<?php
namespace Admin\Model;

class CaseCheckModel extends CommonModel {
    protected $_validate = array(
        array('check_user_id','require','请选择审批人'),
    );
    /**
     * 自动完成
     */
    protected $_auto = array(
        array('submit_user_id', 'getMyUserId', 1, 'callback'),
        array('submit_time', 'time', 1, 'function'),
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
    );
}
?>