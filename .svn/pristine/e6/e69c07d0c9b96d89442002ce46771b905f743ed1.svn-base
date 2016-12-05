<?php
namespace Admin\Model;

class CaseCheckupSmsModel extends CommonModel {

	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID必须填写'),
		array('case_checkup_id', 'require', '检验鉴定ID必须填写'),
		//array('case_client_id', 'require', '当事人ID 必须填写'),
		array('case_client_name', 'require', '案件当事人姓名 必须选择'),
		array('msg_type', 'require', '告知文件类型 必须填写'),
		array('msg_content', 'require', '告知内容 必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('case_client_mobile', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>