<?php
namespace Admin\Model;

class UserModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('user_name', 'is_user_name', '登录名格式错误', 0, 'function'),
		array('user_name', 'checkUserNameUnique', '登录名已存在', 0, 'callback'),
		array('true_name', 'is_true_name', '请正确输入真实姓名', 0, 'function'),
		array('police_no', 'require', '警号必须填写'),
		array('department_id', 'number', '请正确选择队别'),
		array('traffic_level_id', 'number', '请正确选择事故处理等级'),
		array('password', 'require', '密码必须填写', 1, 'regex', 1),
		array('password', 'is_password', '密码格式错误', 2, 'function'),
		array('re_password', 'password', '两次输入密码不一致', 0, 'confirm'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('login_count', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_locked', 0),
		array('is_del', 0),
	);

	/**
	 * 验证用户名是否唯一
	 */
	protected function checkUserNameUnique() {
		$id = I('post.id', '', 'int');
		$userName = I('post.user_name', '', 'trim,htmlspecialchars');
		$map = array();
		$id === '' || $map['id'] = array('neq', $id);
		$map['user_name'] = $userName;
		$map['is_del'] = 0;
		$unique = $this->where($map)->find();
		return empty($unique) ? true : false;
	}

}
?>