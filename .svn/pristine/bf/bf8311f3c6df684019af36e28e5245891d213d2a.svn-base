<?php
namespace Admin\Model;

class CaseCognizanceNormalModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('base_info', 'require', '请输入当事人、车辆、道路和交通环境等基本情况'),
        array('process', 'require', '请输入道路交通事故发生经过'),
        array('research', 'require', '请输入道路交通事故证据及事故形成原因分析'),
        array('reason', 'require', '请输入当事人导致交通事故的过错及责任或者意外原因'),
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