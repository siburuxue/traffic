<?php
namespace Admin\Model;

class DutyModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('start_time', 'is_time', '起始时间必须填写', 0, 'function'),
		array('end_time', 'is_time', '终止时间必须填写', 0, 'function'),
		array('user_id', 'require', '值班人员必须填写'),
		array('duty_group_id', 'require', '值班组别必须填写'),

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