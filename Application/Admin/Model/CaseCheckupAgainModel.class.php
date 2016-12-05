<?php
namespace Admin\Model;

class CaseCheckupAgainModel extends CommonModel {

	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID必须填写'),
		array('case_checkup_id', 'require', '检验鉴定ID必须填写'),
		//array('case_client_id', 'require', '当事人ID 必须填写'),
		array('from_user_id', 'require', '申请人 必须选择'),
		array('to_user_id', 'require', '审核人用户编号 必须填写'),
		//array('again_content', 'require', '申请理由 必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('status', 0),
		array('checked_time', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>