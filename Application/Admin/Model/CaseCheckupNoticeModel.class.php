<?php
namespace Admin\Model;

class CaseCheckupNoticeModel extends CommonModel {

	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID 必须填写'),
		array('case_client_id', 'require', '当事人 必须选择'),
		array('case_checkup_id', 'require', '检验鉴定ID 必须填写'),
		array('notice_department', 'require', '告知单位 必须填写'),
		array('notice_time', 'require', '告知时间 必须选择'),
		array('notice_place', 'require', '告知地点 必须填写'),
		array('notice_person', 'require', '告知人 必须填写'),
		array('target_person', 'require', '被告知人 必须选择'),
		array('content', 'require', '告知内容 必须填写'),
		array('is_clear', 'require', '是否听清 必须选择'),
		array('is_again', 'require', '是否申请重新检验鉴定 必须选择'),
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