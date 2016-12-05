<?php
namespace Admin\Model;

class AlarmProcessModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('process_time', 'require', '处警时间必须填写'),
		array('process_time', 'is_time', '请输入有效的处警时间', 0, 'function'),
		array('content', 'require', '处警内容必须填写'),
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