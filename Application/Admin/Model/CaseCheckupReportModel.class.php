<?php
namespace Admin\Model;

class CaseCheckupReportModel extends CommonModel {

	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID必须填写'),
		array('case_checkup_id', 'require', '检验鉴定ID必须填写'),
		array('finish_time', 'require', '鉴定完成时间必须填写'),
		array('code', 'require', '鉴定书编号必须填写'),
		array('result', 'require', '鉴定结果必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('back_reason', 0),
		array('is_back', 0),
		array('back_time', 0),
		array('back_user_id', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

}
?>