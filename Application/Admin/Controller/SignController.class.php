<?php
namespace Admin\Controller;

/**
 * 登录
 */
class SignController extends PublicController {

	/**
	 * 登录界面
	 */
	public function login() {

		$this->display();

	}

	/**
	 * 登录验证
	 */
	public function loginCheck() {

		$userName = I('post.user_name', '', 'trim,htmlspecialchars');
		$password = I('post.password', '', 'trim,htmlspecialchars');

		if (false === is_user_name($userName)) {
			$this->error('用户名错误');
		}
		if (false === is_password($password)) {
			$this->error('密码错误');
		}

		$map = array();
		$map['user_name'] = $userName;
		$map['is_del'] = 0;
		$user = M('User')->where($map)->find();

		if (empty($user)) {
			$this->error('帐号不存在');
		}

		if ($user['password'] !== md5(md5($password) . $user['salt'])) {
			$this->error('密码错误');
		}

		if ($user['is_locked'] == 1) {
			$this->error('帐号被锁定');
		}

		$user['last_login_time'] = time();
		$user['last_login_ip'] = get_client_ip();
		$user['login_count']++;
		M('User')->field('id,last_login_time,last_login_ip,login_count')->save($user);

		session('userid', $user['id']);

		$this->success('登录成功', U('Index/index'));
	}

	/**
	 * 退出登录
	 */
	public function logout() {
		session(null);
		cookie(null);
		$this->redirect('Sign/login');
	}

}