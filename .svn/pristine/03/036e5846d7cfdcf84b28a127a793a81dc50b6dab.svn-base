<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model {
	protected $_validate = array(
		array('email', 'is_email', '邮箱地址格式错误', 0, 'function'),
		array('email', '', '邮箱地址已被注册', 0, 'unique'),
		array('password', 'is_password', '密码格式错误', 0, 'function'),
		array('repassword', 'password', '两次密码输入不一致', 0, 'confirm'),
		array('schoolid', '', '数字校园帐号已被绑定', 0, 'unique'),
		array('truename', 'is_truename', '姓名错误', 0, 'function'),
		array('nickname', 'is_nickname', '昵称错误', 0, 'function'),
		array('mobile', 'is_mobile', '手机号码格式错误', 2, 'function'),
		array('qcid', 'require', '非法操作'),
		array('qctoken', 'require', '非法操作'),
		array('qcid', '', '该QQ帐号已被绑定', 0, 'unique'),
		array('wxid', 'require', '非法操作'),
		array('wxtoken', 'require', '非法操作'),
		array('wxid', '', '该微信帐号已被绑定', 0, 'unique'),
		array('wbid', 'require', '非法操作'),
		array('wbtoken', 'require', '非法操作'),
		array('wbid', '', '该微博帐号已被绑定', 0, 'unique'),
	);
	protected $_auto = array(
		array('regdate', 'time', 1, 'function'),
		array('regip', 'get_client_ip', 1, 'function'),
		array('updatetime', 'time', 3, 'function'),
		array('status', 1),
	);

}
?>