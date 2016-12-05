<?php
namespace Admin\Model;

class CaseHandleModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '案件编号错误'),
		array('user_id', 'number', '办案人用户编号错误'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('start_time', 'time', 1, 'function'),
		array('end_time', 0),
		array('is_now', 1),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>