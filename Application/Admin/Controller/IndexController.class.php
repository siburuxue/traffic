<?php
namespace Admin\Controller;

/**
 * 首页
 */
class IndexController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		$this->display();

	}

	/**
	 * 欢迎页
	 */
	public function main() {

		$myBrigade = $this->getMyBrigade();

		$this->display();

	}

	/**
	 * 侧边导航
	 */
	public function side() {
		$this->display();
	}

	public function password() {
		$this->display();
	}

	public function savePassword() {
		$oldPassword = I('post.oldPassword');
		$password = I('post.password');
		$password1 = I('post.password1');
		$model = M('User');
		$map = array();
		$userInfo = $model->getById($this->my['id']);
		if (empty($oldPassword)) {
			$this->error('请输入原密码');
		}
		if (empty($password)) {
			$this->error('请输入新密码');
		}
		if (empty($password1)) {
			$this->error('请输入确认新密码');
		}
		if (!is_password($password)) {
			$this->error('新密码格式错误');
		}
		if (!is_password($password1)) {
			$this->error('确认新密码格式错误');
		}
		if ($password != $password1) {
			$this->error('两次输入的密码一致');
		}
		if ($userInfo['password'] !== md5(md5($oldPassword) . $userInfo['salt'])) {
			$this->error('原密码不正确');
		}
		$newPassword = md5(md5($password) . $userInfo['salt']);
		if ($newPassword == $userInfo['password']) {
			$this->error('修改后密码与原密码一致，不能修改');
		}
		$map = array();
		$map['password'] = md5(md5(I('post.password')) . $userInfo['salt']);
		$map['id'] = $this->my['id'];
		$map['update_user_id'] = $this->my['id'];
		$map['update_time'] = time();
		$rs = $model->save($map);
		if ($rs === false) {
			$this->error('密码修改失败');
		}
		$this->success('密码修改成功', U('Sign/logout'));
	}
}