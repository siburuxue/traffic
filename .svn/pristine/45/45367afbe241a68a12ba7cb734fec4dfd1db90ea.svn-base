<?php
namespace Admin\Model;

class CaseReviewStopModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '案件编号错误'),
		array('case_review_id', 'number', '复核编号错误'),
		array('case_client_id', 'number', '请正确选择申请人'),
		array('case_client_relater_id', 'number', '请正确选择申请人'),
		array('reason', 'require', '终止原因不能为空'),
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