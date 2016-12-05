<?php
namespace Admin\Model;

class CaseMediateRefuseModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('agent', 'require', '请选择代理人'),
        array('content', 'require', '请输入不调解文本'),
        array('handle_1', 'require', '请选择办案人1'),
        array('handle_2', 'require', '请选择办案人2'),
        array('contact', 'require', '请输入电话或通讯地址'),
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