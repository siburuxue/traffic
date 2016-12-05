<?php
namespace Home\Controller;
// 通过Email登录
// 每个登录链接只有一次有效
class EmailSignController extends PublicController {

	public function __construct() {

		parent::__construct();

		// 获取数据
		$id = I('get.id', '', 'trim,htmlspecialchars');
		$code = I('get.code', '', 'trim,htmlspecialchars');

		// 验证字段合法
		if (false === is_id($id) || $code === '') {
			$this->error('非法操作', U('Sign/logout'));
		}

		// 验证ID并获取邮件验证信息
		$map['id'] = $id;
		$emailSign = M('EmailSign')->where($map)->find();
		unset($map);
		if (empty($emailSign)) {
			$this->error('非法操作', U('Sign/logout'));
		}

		// 验证 1.链接是否已使用，2.是否有新的链接生成，3.是否已超出有效期
		if ($emailSign['ischecked'] == 1 || $emailSign['islast'] == 0 || time() - $emailSign['createtime'] > C('custom.emailSignLinkLifespan')) {
			$this->error('链接已失效', U('Sign/logout'));
		}

		// 验证用户是否存在，并获取用户信息
		$map['email'] = $emailSign['email'];
		$user = M('User')->where($map)->find();
		unset($map);
		if (empty($user)) {
			$this->error('链接已失效', U('Sign/logout'));
		}

		// 用户是否被禁用
		if ($user['status'] == 0) {
			$this->error('用户帐号已被锁定', U('Sign/logout'));
		}

		// 验证链接签名
		$data = array();
		$data['email'] = $emailSign['email'];
		$data['action'] = $emailSign['action'];
		$data['checkcode'] = $emailSign['checkcode'];
		$data['createtime'] = $emailSign['createtime'];

		ksort($data);
		reset($data);

		$dataStr = createLinkstringUrlencode($data);
		unset($data);

		if ($code !== md5($dataStr)) {
			$this->error('链接已失效', U('Sign/logout'));
		}

		// 链接设置为已使用
		$map['id'] = $id;
		$data['ischecked'] = 1;
		$data['checkedtime'] = time();
		M('EmailSign')->where($map)->setField($data);
		unset($map);

		$this->user = $user;
		$this->emailSign = $emailSign;
	}

	// 验证注册
	public function index() {
		$action = $this->emailSign['action'];
		if ($action != '' && method_exists($this, $action)) {
			call_user_func(array($this, $action));
		} else {
			$this->error('非法操作', U('Index/index'));
		}
	}

	private function reg() {
		$user = $this->user;
		// 登录
		$data = array();
		$data['emailchecked'] = 1;
		$data['updatetime'] = time();
		if (session('userid') != $user['id']) {
			$data['lastlogintime'] = $data['updatetime'];
			$data['lastloginip'] = get_client_ip();
			$data['logincount'] = $user['logincount'] + 1;
		}
		$map['id'] = $user['id'];
		$res = M('User')->where($map)->setField($data);
		unset($map);
		if ($res) {
			session('userid', $user['id']);
			$this->success('邮箱验证成功', U('Index/index'));
		} else {
			$this->error('数据写入异常');
		}
	}

	private function findpwd() {
		session(array('expire' => 600));
		session('findpwd_userid', $this->user['id']);
		$this->redirect('Sign/setPwd');
	}

}