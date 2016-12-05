<?php
namespace Admin\Model;

class ArchiveModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件编号必须选择'),
		array('code', 'require', '档案编号必须填写'),
		array('name', 'require', '档案名称必须填写'),
		array('catalog', 'require', '目录号必须填写'),
		array('dossier', 'require', '案卷号必须填写'),
		array('case_no', 'require', '案件号必须填写'),
		array('place', 'require', '所在位置必须填写'),
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
		array('status', 0),
	);

}
?>