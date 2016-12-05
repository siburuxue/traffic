<?php
namespace Admin\Model;

class CaseWorkLogModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('content', 'require', '请输入记录内容'),
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