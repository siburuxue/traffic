<?php
namespace Admin\Model;

class ArchiveLendModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('archive_id', 'require', '档案id必须填写'),
		array('cate', 'require', '操作类型必须填写'),
		array('user', 'require', '操作人必须填写'),
		array('time', 'require', '操作时间必须填写'),
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