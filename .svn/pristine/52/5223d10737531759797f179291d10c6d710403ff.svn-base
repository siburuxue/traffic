<?php
namespace Admin\Model;

class CaseCoescapeModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('case_client_id', 'require', '请选择申请人'),
        array('apply_time', 'require', '请输入申请时间'),
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
    );
}
?>