<?php
namespace Home\Model;
use Think\Model;

class UserAuthModel extends Model {
	protected $_validate = array(
		array('name', 'require', '姓名不能为空'),
		array('name', 'is_truename', '姓名格式错误', 0, 'function'),
		array('mobile', 'is_mobile', '手机号码格式错误', 0, 'function'),
		array('classesname', 'require', '班级名称必须填写'),
	);
	protected $_auto = array(
		array('userid', 'getUserId', 1, 'callback'),
		array('reply', ''),
		array('checkedtime', 0),
		array('checkeduserid', 0),
		array('createtime', 'time', 1, 'function'),
		array('status', 0),
	);

	protected function getUserId() {
		return session('userid');
	}

}
?>