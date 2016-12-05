<?php
namespace Admin\Model;

class CaseCognizanceReportModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('base_info_client', 'require', '请输入当事人情况'),
        array('base_info_car', 'require', '请输入基本情况-车辆情况'),
        array('base_info_road', 'require', '请输入基本情况-道路情况'),
        array('fact', 'require', '请输入交通事故的基本事实'),
        array('desc', 'require', '请输入交通事故的证据、检验、鉴定结论'),
        array('reason', 'require', '请输入道路交通事故成因分析'),
        array('cognizance', 'require', '请输入责任认定'),
        array('punish', 'require', '请输入处罚意见'),
        array('reform', 'require', '请输入整改措施'),
    );
    /**
     * 自动完成
     */
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('submit_time', 'time', 1, 'function'),
        array('submit_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
    );
}
?>