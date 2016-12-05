<?php
namespace Admin\Model;

class CaseCoescapeSmsModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('msg_content', 'require', '请输入告知内容'),
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