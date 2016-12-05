<?php
namespace Admin\Model;

class CaseReviewCheckNoticeModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '案件编号错误'),
		array('case_review_id', 'number', '复核编号错误'),
		array('case_review_check_id', 'number', '复核结论编号错误'),
		array('case_client_id', 'number', '请正确选择受送达人'),
		array('case_client_relater_id', 'number', '请正确选择受送达人'),
		array('code', 'require', '文书名称、文号不能为空'),
		array('post_time', 'is_time', '请正确输入送达时间', 0, 'function'),
		array('post_place', 'require', '送达地点不能为空'),
		array('post_method', 'require', '送达方式不能为空'),
		array('target_user_id', 'number', '请正确选择受送达人'),
		array('witness', 'require', '见证人不能为空'),
		array('sender', 'require', '送达人不能为空'),
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