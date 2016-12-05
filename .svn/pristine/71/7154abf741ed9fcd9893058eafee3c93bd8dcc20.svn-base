<?php
namespace Admin\Model;

class CaseAttentionModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(

	);
	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('user_id', 'getMyUserId', 1, 'callback'),
		array('create_time', 'time', 1, 'function'),
		array('user_id', 'getMyUserId', 1, 'callback'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>