<?php
namespace Admin\Model;

class CaseExtReasonModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件信息未获取'),
		array('client_id', 'require', '人员必须选择'),
		//array('bus_drivers', 'number', '客运车辆驾驶人数量', 2),

		// array('code', 'require', '案件编号未获取'),
		// array('client_num', 'number', '请输入正确当事人总数'),
		// array('client_death_scene', 'number', '请输入正确当场死亡人数'),
		// array('client_death_rescue', 'number', '请输入正确抢救无效死亡人数'),
		// array('client_missing', 'number', '请输入正确下落不明人数'),
		// array('client_hurt_serious', 'number', '请输入正确重伤人数'),
		// array('client_hurt_minor', 'number', '请输入正确轻伤人数'),
		// array('direct_property_loss', 'is_money', '请正确格式的直接财产损失', 0, 'function'),
		// array('indirect_property_loss', 'is_money', '请正确格式的间接财产损失', 0, 'function'),
		// array('initial_reason', 'require', '请选择事故初查原因'),
		// array('involve_num', 'number', '请输入正确事故涉及车辆和行人数量'),

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