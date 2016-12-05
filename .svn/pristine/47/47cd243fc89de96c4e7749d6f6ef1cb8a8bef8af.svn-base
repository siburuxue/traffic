<?php
namespace Admin\Model;

class CaseCognizanceProveModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('base_info', 'require', '请输入当事方基本情况'),
        array('fact', 'require', '请输入调查交通事故得到的事实'),
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