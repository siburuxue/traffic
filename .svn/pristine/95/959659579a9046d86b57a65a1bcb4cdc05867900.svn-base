<?php
namespace Admin\Model;

class CaseCheckupModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID必须填写'),
		array('checkup_org_item_pid', 'require', '鉴定对象必须选择'),
		// array('target_case_client_id', 'require', '鉴定对象人员或车辆必须选择'),
		array('checkup_org_item_id', 'require', '鉴定类型必须选择'),

		// array('target_car_no', 'require', '鉴定对象当事人车牌号必须选择'),
		array('checkup_org_id', 'require', '鉴定机构必须选择'),
		array('finish_time', 'is_time', '约定完成时间必须填写', 0, 'function'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('cancel_reason', 0),
		array('is_cancel', 0),
		array('is_over', 0),
		array('delay_checked', 0),
		// array('target_other', 0),
		array('out_checked', 0),
		array('is_out', 0),
		array('is_delay', 0),
		array('pid', 0),
		array('target_car_no', 0),
		array('is_del', 0),

	);

}
?>