<?php
namespace Admin\Model;

class AlarmModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('alarm_time', 'require', '报警时间必须填写'),
		array('alarm_time', 'is_time', '请输入有效的报警时间', 0, 'function'),
		array('case_source', 'number', '案件来源必须填写'),
		array('alarm_name', 'require', '报警人姓名必须填写'),
		array('contact', 'require', '单位及联系方式必须填写'),
		array('is_protect', array(0, 1), '请正确选择是否保密', 0, 'in'),
		array('accident_time', 'require', '事故发生时间必须填写'),
		array('accident_time', 'is_time', '请输入有效的事故发生时间', 0, 'function'),
		array('accident_place', 'require', '事故地点必须填写'),
		array('casualties', 'require', '人员伤亡情况必须填写'),
		array('scene_end_time', 'is_time', '请输入有效的现场处置结束时间', 0, 'function'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('handle_type', 0),
		array('is_link', 0),
		array('case_id', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>