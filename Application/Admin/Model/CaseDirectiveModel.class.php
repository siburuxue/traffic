<?php
namespace Admin\Model;

class CaseDirectiveModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '非法操作'),
		array('content', 'require', '指示内容不能为空'),
		array('directive_time', 'is_time', '指示时间错误', 0, 'function'),
		array('directive_user_id', 'number', '指示用户编号错误'),
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