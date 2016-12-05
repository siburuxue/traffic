<?php
namespace Home\Controller;

class CommonController extends PublicController {

	public function __construct() {

		parent::__construct();

		// 判断登录
		$userid = session('userid');
		if (empty($userid)) {
			// $this->redirect('Sign/logout');
		}
/*
// 当前用户信息
$map['id'] = $userid;
$user = M('User')->where($map)->find();
unset($map);
if (empty($user)) {
$this->error('用户不存在，请重新登录', U('Sign/logout'));
}
if ($user['status'] == 0) {
$this->error('用户被锁定', U('Sign/logout'));
}
$this->my = $user;
 */
	}

}