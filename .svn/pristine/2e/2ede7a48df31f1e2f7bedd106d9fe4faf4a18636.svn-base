<?php
namespace Admin\Model;

class BloodtubeUsedLogModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('bloodtube_cate_id', 'require', '分组编号必须填写'),
		array('user_id', 'require', '回收操作人员必须填写'),
		array('status', 'require', '使用状态必须选择'),
		array('pre_status', 'require', '更改前使用状态必须填写'),
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