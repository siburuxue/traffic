<?php
namespace Admin\Model;

class RoleModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('name', 'require', '角色名称必须填写'),
		array('name', '', '节点名称已存在', 0, 'unique'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>