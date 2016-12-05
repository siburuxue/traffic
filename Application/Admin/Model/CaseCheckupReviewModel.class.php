<?php
namespace Admin\Model;

class CaseCheckupReviewModel extends CommonModel {

	protected $_validate = array(

		array('content', 'require', '报告内容 必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('from_userid', 'getMyUserId', 1, 'callback'),
		array('to_userid', 0),
		array('title', 0),
		array('status', 0),
		array('delay_time', 0),
		array('checked_time', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>