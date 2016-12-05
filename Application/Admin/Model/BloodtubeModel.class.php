<?php
namespace Admin\Model;

class BloodtubeModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('bloodtube_cate_id', 'require', '分组编号必须填写'),
		array('code', 'require', '采血管编号必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('recover_type', 0),
		array('recover_time', 0),
		array('recover_user_id', 0),
		array('is_recover', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>