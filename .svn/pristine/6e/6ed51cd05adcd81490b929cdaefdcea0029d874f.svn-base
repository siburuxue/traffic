<?php
namespace Admin\Model;

class CaseAcceptModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('reason', 'require', '案由必须填写'),
        array('case_src', 'require', '案件来源必须填写'),
        array('alarm_time', 'require', '报警时间必须填写'),
        array('alarm_type', 'require', '报警方式必须填写'),
        array('reporter_name', 'require', '报警人姓名必须填写'),
        array('reporter_sex', 'require', '报警人性别必须填写'),
        array('reporter_tel', 'require', '报警人联系电话必须填写'),
        array('reporter_address', 'require', '报警人住址或者单位必须填写'),
        array('reporter_note', 'require', '报警人附注必须填写'),
        array('content', 'require', '报案内容必须填写'),
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