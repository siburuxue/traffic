<?php
namespace Home\Controller;

class AccountController extends CommonController {

	// 帐号首页界面
	public function index() {
		$this->display();
	}

	// 设置用户帐号界面
	public function setAccount() {
		$user = $this->my;
		if (!empty($user['email'])) {
			$this->error('帐号已设置', U('index'));
		}
		$this->display();
	}

	// 更新用户帐号
	public function AccountUpdate() {
		// 采集数据
		$user = $this->my;
		$email = I('post.email', '', 'trim,htmlspecialchars');
		$password = I('post.password', '', 'trim,htmlspecialchars');
		$repassword = I('post.repassword', '', 'trim,htmlspecialchars');

		// 是否已设置帐号
		if (!empty($user['email'])) {
			$this->error('帐号已设置', U('index'));
		}
		// 验证邮箱格式
		if (false === is_email($email)) {
			$this->error('邮箱地址格式错误');
		}
		// 验证密码格式
		if (false === is_password($password)) {
			$this->error('密码格式错误');
		}
		// 验证重复密码
		if ($password !== $repassword) {
			$this->error('两次密码输入不一致');
		}
		// 验证邮箱地址唯一性
		$map['email'] = $email;
		$map['id'] = array('neq', $user['id']);
		$unique = M('User')->where($map)->find();
		unset($map);
		if (!empty($unique)) {
			$this->error('邮箱地址已注册');
		}

		// 创建验证邮件
		$ve = new \Common\Com\VerificationEmail();
		$emailData = $ve->create($email, 'reg', array('username' => $email));
		if (false === $emailData) {
			$this->error('注册失败<br>验证邮件创建失败<br>原因：' . $ve->getError());
		}

		// 更新数据
		$data['id'] = $user['id'];
		$data['email'] = $email;
		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5($password) . $data['salt']);
		$data['emailchecked'] = 0;
		$data['updatetime'] = time();
		$result = M('User')->save($data);

		if ($result) {
			// 发送注册邮件
			$aliMail = new \Lib\AliMail();
			$send = $aliMail->exec($email, $emailData['subject'], $emailData['content']);
			$this->success('登录帐号设置成功<br>我们已将注册确认信发送至：' . $email . '<br>请查收邮件并验证激活', U('index'));
		} else {
			$this->error('数据写入异常<br>请联系管理员<br>' . C('custom.adminEmail'));
		}
	}

	// 编辑用户信息界面
	public function edit() {
		$this->display();
	}

	// 更新用户信息
	public function update() {
		$_POST['truename'] = I('post.truename', '', 'trim,htmlspecialchars');
		$_POST['nickname'] = I('post.nickname', '', 'trim,htmlspecialchars');
		$_POST['mobile'] = I('post.mobile', '', 'trim,htmlspecialchars');
		$_POST['id'] = $this->my['id'];

		$Model = D('User');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$result = $Model->save($data);
		if ($result) {
			$this->success('更新成功');
		} else {
			$this->error('数据保存异常');
		}
	}

	// 修改密码界面
	public function password() {
		if (empty($this->my['email'])) {
			$this->error('请先设置登录帐号', U('setAccount'));
		}
		$this->display();
	}

	// 更新密码
	public function passwordUpdate() {
		$user = $this->my;
		$oldpassword = I('post.oldpassword', '', 'trim,htmlspecialchars');
		$newpassword = I('post.newpassword', '', 'trim,htmlspecialchars');
		$repassword = I('post.repassword', '', 'trim,htmlspecialchars');

		if (empty($user['email'])) {
			$this->error('请先设置登录帐号', U('setAccount'));
		}

		if (false === is_password($oldpassword) || $user['password'] !== md5(md5($oldpassword) . $user['salt'])) {
			$this->error('原密码错误');
		}
		if (false === is_password($newpassword)) {
			$this->error('新密码格式错误');
		}
		if ($oldpassword === $newpassword) {
			$this->error('新密码不能与旧密码相同');
		}
		if ($newpassword !== $repassword) {
			$this->error('两次密码输入不一致');
		}

		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5($newpassword) . $data['salt']);
		$data['updatetime'] = time();
		$map['id'] = $user['id'];
		$result = M('User')->where($map)->setField($data);
		if ($result) {
			session(null);
			$this->success('密码重置成功<br>请重新登录', U('Sign/logout'));
		} else {
			$this->error('数据保存异常');
		}

	}

	// 身份认证界面
	public function auth() {
		if ($this->my['ischecked'] == 1) {
			$this->redirect('Index/index');
		}
		// 是否在人工认证中
		$map['userid'] = $this->my['id'];
		$userAuth = M('UserAuth')->where($map)->find();
		$this->assign('userAuth', $userAuth);

		$this->display();
	}

	// 插入身份认证
	public function insertAuth() {

		$map['userid'] = $this->my['id'];
		$map['status'] = array('neq', 2);
		$userAuth = M('UserAuth')->where($map)->find();
		unset($map);
		if (!empty($userAuth)) {
			$this->error('暂不可提交新的申请');
		}

		$Model = D('UserAuth');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$Model = new \Think\Model();
		$Model->startTrans();

		$map['userid'] = $this->my['id'];
		$Model->table(C('DB_PREFIX') . 'user_auth')->where($map)->delete();
		unset($map);

		$id = $Model->table(C('DB_PREFIX') . 'user_auth')->add($data);

		if ($id) {
			$Model->commit();
			$this->success('申请提交成功');
		} else {
			$Model->rollback();
			$this->error('申请提交失败');
		}
	}

	public function bind() {
		$user = $this->my;
		if (!empty($user['qcid'])) {
			$qc = new \Lib\QC\QC($user['qctoken'], $user['qcid']);
			$qcinfo = $qc->get_user_info();
			$this->assign('qcinfo', $qcinfo);
		}
		if(!empty($user['wbid'])) {
			$weibo = new \Lib\Weibo\SaeTClientV2(C('weibo.appid'),C('weibo.appkey'),$user['wbtoken']);
			$wbinfo = $weibo->show_user_by_id($user['wbid']);
			$this->assign('wbinfo', $wbinfo);
		}
		$this->display();
	}

}