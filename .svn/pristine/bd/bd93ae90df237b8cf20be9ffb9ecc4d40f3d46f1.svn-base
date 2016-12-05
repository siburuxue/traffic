<?php
namespace Admin\Model;

class DepartmentModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('pid', 'number', '请正确选择上级部门'),
		array('name', 'require', '部门名称必须填写'),
		array('area_code', 'checkAreaCode', '行政划区代码必须填写', 0, 'callback'),
		array('cate', array(1, 2, 3, 4), '请正确选择部门类型', 0, 'in'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('level', 'getLevel', 1, 'callback'),
		array('train', 'getTrain', 1, 'callback'),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

	/**
	 * 检查行政划区代码
	 */
	protected function checkAreaCode() {
		$cate = I('post.cate', '', 'int');
		if ($cate === 2) {
			$areaCode = I('post.area_code', '', 'trim,htmlspecialchars');
			if ($areaCode == '') {
				return false;
			}
		}
		return true;
	}

	/**
	 * 获取当前部门层级
	 */
	protected function getLevel() {
		$pid = I('post.pid', '', 'int');
		if ($pid === '') {
			return 1;
		}
		$map = array();
		$map['id'] = $pid;
		$parentLevel = M('Department')->where($map)->getField('level');
		return intval($parentLevel) + 1;
	}

	/**
	 * 获取当前部门排序值
	 */
	protected function getTrain() {
		$pid = I('post.pid', 0, 'int');
		$map = array();
		$map['pid'] = $pid;
		$map['is_del'] = 0;
		$train = M('Department')->where($map)->max('train');
		return intval($train) + 1;
	}

}
?>