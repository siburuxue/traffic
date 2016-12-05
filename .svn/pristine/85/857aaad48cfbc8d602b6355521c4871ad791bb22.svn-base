<?php
namespace Admin\Model;

class BloodtubeCateModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('to_department_time', 'require', '派发时间必须填写'),
		array('to_department_user_id', 'require', '派发人员必须填写'),
		array('target_department_id', 'require', '派发大队必须选择'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('case_id', 0),
		array('case_client_id', 0),
		array('used_time', 0),
		array('used_user_id', 0),
		array('is_used', 0),
		array('is_to_department', 1),
		array('to_user_time', 0),
		array('to_user_user_id', 0),
		array('target_user_id', 0),
		array('is_to_user', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>