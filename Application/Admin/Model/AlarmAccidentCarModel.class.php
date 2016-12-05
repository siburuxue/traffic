<?php
namespace Admin\Model;

class AlarmAccidentCarModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('car_no', 'require', '车牌号码必须填写'),
		array('car_type', 'number', '请正确选择车辆类型'),
		array('is_danger', array(0, 1), '请正确选择是否危险品', 0, 'in'),
		array('is_bus', array(0, 1), '请正确选择是否客运车辆', 0, 'in'),
		array('is_school', array(0, 1), '请正确选择是否危险品', 0, 'in'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
	);

}
?>