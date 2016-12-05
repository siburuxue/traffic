<?php
namespace Admin\Model;

class CaseCoescapeNoticeModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('code', 'require', '文书名称、文号必须填写'),
        array('post_time', 'require', '送达时间必须填写'),
        array('post_place', 'require', '送达地点必须填写'),
        array('post_method', 'require', '送达方式必须填写'),
        array('target_user_name', 'require', '请选择受送达人'),
        array('witness', 'require', '见证人必须填写'),
        array('sender', 'require', '送达人必须填写'),
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