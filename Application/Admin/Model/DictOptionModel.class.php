<?php
namespace Admin\Model;

class DictOptionModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('dict_id', 'checkDictId', '字段名格式错误', 0, 'callback'),
		array('name', 'require', '属性名称必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('train', 'getTrain', 1, 'callback'),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

	/**
	 * 验证字典项
	 */
	protected function checkDictId() {
		$dictId = I('post.dict_id', '', 'int');
		if ($dictId === '') {
			return false;
		}
		$dict = M('Dict')->getById($dictId);
		if (empty($dict)) {
			return false;
		}
		return true;
	}

	/**
	 * 获取排序值
	 */
	protected function getTrain() {
		$dictId = I('post.dict_id', '', 'int');
		$map = array();
		$map['dict_id'] = $dictId;
		$map['is_del'] = 0;
		$train = M('DictOption')->where($map)->max('train');
		return intval($train) + 1;
	}

}
?>