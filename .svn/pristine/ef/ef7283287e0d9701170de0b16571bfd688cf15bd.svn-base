<?php
namespace Admin\Model;

class CaseModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('accident_name', 'require', '事故名称必须填写'),
		array('accident_time', 'require', '事故发生时间必须填写'),
		array('accident_time', 'is_time', '请输入有效的事故发生时间', 0, 'function'),
		array('accident_place', 'require', '事故发生地点必须填写'),
		array('death_num', 'number', '死亡人数必须是数字', 2),
		array('hurt_num', 'number', '受伤人数必须是数字', 2),
		array('property_loss', 'number', '请正确选择财产损失'),
		array('accident_type', 'number', '请正确选择事故类型'),
		// array('accident_type', 'checkAccidentType', '请正确选择事故类型', 0, 'callback'),
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

	/**
	 * 验证事故类型
	 */
	protected function checkAccidentType() {
		// 必须有死亡和受伤人数
		if (!isset($_POST['death_num']) || !isset($_POST['hurt_num'])) {
			return false;
		}
		$deathNum = I('post.death_num', 0, 'int');
		$hurtNum = I('post.hurt_num', 0, 'int');
		$accidentType = I('post.accident_type', '', 'int');

		// 如果字典项(accident_type)修改，此处应更新
		switch ($accidentType) {
		case 3: // 死亡事故
			if ($deathNum <= 0) {
				return false;
			}
			break;
		case 2: // 伤人事故
			if ($deathNum > 0 || $hurtNum <= 0) {
				return false;
			}
			break;
		case 1: // 财产损失事故
			if ($deathNum > 0 || $hurtNum > 0) {
				return false;
			}
			break;
		default:
			return false;
			break;
		}
		return true;
	}
}
?>