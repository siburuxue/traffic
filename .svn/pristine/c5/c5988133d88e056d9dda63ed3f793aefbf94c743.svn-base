<?php
namespace Home\Controller;

class SignController extends PublicController {

	// 登录界面
	public function login() {
		$this->display();
	}

	// 登录验证
	public function loginCheck() {
		// 采集数据
		$email = I('post.email', '', 'trim,htmlspecialchars');
		$password = I('post.password', '', 'trim,htmlspecialchars');
		$checkcode = I('post.checkcode', '', 'trim,htmlspecialchars,strtolower');
		// 验证图形验证码
		$verify = new \Think\Verify();
		if (!$verify->check($checkcode, 'login')) {
			$this->error('验证码错误');
		}
		// 验证邮箱格式
		if (false === is_email($email)) {
			$this->error('邮箱地址错误');
		}
		// 验证密码格式
		if (false === is_password($password)) {
			$this->error('密码错误');
		}
		// 查询用户
		$map['email'] = $email;
		$user = M('User')->where($map)->find();
		unset($map);
		if (empty($user)) {
			$this->error('帐号不存在，请先<a href="' . U('Sign/reg') . '">注册</a>');
		}
		// 验证密码
		if ($user['password'] !== md5(md5($password) . $user['salt'])) {
			$this->error('密码错误');
		}
		// 验证状态
		if ($user['status'] == 0) {
			$this->error('帐号被锁定<br>如有疑问请联系管理员<br>' . C('custom.adminEmail'));
		}
		// 验证邮箱状态，如果邮箱未验证，跳转到验证邮箱页
		if ($user['emailchecked'] == 0) {
			$this->error('帐号未激活', U('Sign/activate?userid=' . $user['id']));
		}
		// 更新用户数据
		$user['lastlogintime'] = time();
		$user['lastloginip'] = get_client_ip();
		$user['logincount']++;
		M('User')->field('id,lastlogintime,lastloginip,logincount')->save($user);
		// 执行登录
		session('userid', $user['id']);
		// 返回结果
		$this->success('登录成功', U('Index/index'));
	}

	// 登出
	public function logout() {
		session(null);
		cookie(null);
		$this->redirect('login');
	}

	// 注册界面
	public function reg() {
		$this->display();
	}

	// 注册验证
	public function regCheck() {
		// 采集数据
		$email = I('post.email', '', 'trim,htmlspecialchars');
		$password = I('post.password', '', 'trim,htmlspecialchars');
		$repassword = I('post.repassword', '', 'trim,htmlspecialchars');
		$checkcode = I('post.checkcode', '', 'trim,htmlspecialchars,strtolower');
		// 验证图形验证码
		$verify = new \Think\Verify();
		if (!$verify->check($checkcode, 'reg')) {
			$this->error('验证码错误');
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
		$unique = M('User')->where($map)->find();
		unset($map);
		if (!empty($unique)) {
			$this->error('邮箱地址已注册');
		}
		// 创建数据库模型
		$Model = new \Think\Model();
		$Model->startTrans();
		// 插入数据
		$data['email'] = $email;
		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5($password) . $data['salt']);
		$data['schoolid'] = '';
		$data['isadmin'] = 0;
		$data['truename'] = '';
		$data['nickname'] = $email;
		$data['logincount'] = 0;
		$data['regdate'] = time();
		$data['regip'] = get_client_ip();
		$data['lastlogintime'] = 0;
		$data['lastloginip'] = '';
		$data['emailchecked'] = 0;
		$data['ischecked'] = 0;
		$data['updatetime'] = $data['regdate'];
		$data['status'] = 1;
		$userid = $Model->table(C('DB_PREFIX') . 'user')->add($data);
		if (!$userid) {
			$Model->rollback();
			$this->error('数据写入异常');
		}
		// 创建验证邮件
		$ve = new \Common\Com\VerificationEmail();
		$emailData = $ve->create($email, 'reg', array('username' => $email));
		if (false === $emailData) {
			$Model->rollback();
			$this->error('注册失败<br>验证邮件创建失败<br>原因：' . $ve->getError());
		}
		// 发送注册邮件
		$aliMail = new \Lib\AliMail();
		$send = $aliMail->exec($email, $emailData['subject'], $emailData['content']);
		$Model->commit();
		$this->success('注册成功', U('Sign/activate?userid=' . $userid));
	}

	// 激活帐号界面
	public function activate() {
		$userid = I('get.userid', '', 'int');

		$map['id'] = $userid;
		$user = M('User')->where($map)->find();
		if (empty($user)) {
			$this->error('用户帐号不存在', U('Sign/login'));
		}
		if ($user['status'] == 0) {
			$this->error('用户帐号被锁定', U('Sign/login'));
		}
		if ($user['emailchecked'] == 1) {
			$this->error('邮箱地址已验证，现在登录', U('Sign/login'));
		}
		$this->assign('info', $user);
		$this->display();

	}

	// 重新发送注册确认信
	public function reSendRegMail() {
		$userid = I('post.userid', '', 'int');

		$map['id'] = $userid;
		$user = M('User')->where($map)->find();
		if (empty($user)) {
			$this->error('用户帐号不存在', U('Sign/login'));
		}
		if ($user['status'] == 0) {
			$this->error('用户帐号被锁定', U('Sign/login'));
		}
		if ($user['emailchecked'] == 1) {
			$this->error('邮箱地址已验证，现在登录', U('Sign/login'));
		}

		// 创建验证邮件
		$ve = new \Common\Com\VerificationEmail();
		$emailData = $ve->create($user['email'], 'reg', array('username' => $user['email']));
		if (false === $emailData) {
			$this->error($ve->getError());
		}

		$aliMail = new \Lib\AliMail();
		$send = $aliMail->exec($user['email'], $emailData['subject'], $emailData['content']);
		$send = true;
		if ($send) {
			$this->success('邮件发送成功');
		} else {
			$this->error('邮件发送失败');
		}
	}

	public function findPwd() {
		$this->display();
	}

	public function sendPwdMail() {
		$email = I('post.email', '', 'trim,htmlspecialchars');

		if (false === is_email($email)) {
			$this->error('邮箱地址格式错误');
		}

		$map['email'] = $email;
		$user = M('User')->where($map)->find();
		unset($map);
		if (empty($user)) {
			$this->error('帐号不存在');
		}

		if ($user['emailchecked'] == 0) {
			$this->error('帐号未验证邮箱地址，不能通过邮件找回密码');
		}

		if ($user['status'] == 0) {
			$this->error('帐号被锁定<br>如有疑问请联系管理员<br>' . C('custom.adminEmail'));
		}

		// 创建验证邮件
		$ve = new \Common\Com\VerificationEmail();
		$emailData = $ve->create($user['email'], 'findpwd', array('username' => $user['email']));
		if (false === $emailData) {
			$this->error($ve->getError());
		}

		$aliMail = new \Lib\AliMail();
		$send = $aliMail->exec($user['email'], $emailData['subject'], $emailData['content']);
		$send = true;
		if ($send) {
			$this->success('邮件发送成功<br>请查收验证邮件，并根据提示完成剩余步骤', U('Sign/login'));
		} else {
			$this->error('邮件发送失败');
		}

	}

	public function setPwd() {
		$userid = intval(session('findpwd_userid'));
		if (empty($userid)) {
			session(null);
			$this->error('链接已失效', U('Sign/logout'));
		}
		$map['id'] = $userid;
		$map['status'] = 1;
		$map['emailchecked'] = 1;
		$user = M('User')->where($map)->find();
		if (empty($user)) {
			session(null);
			$this->error('非法操作', U('Sign/logout'));
		}
		$this->display();
	}

	public function updatePwd() {
		$userid = intval(session('findpwd_userid'));
		$password = I('post.password', '', 'trim,htmlspecialchars');
		$repassword = I('post.repassword', '', 'trim,htmlspecialchars');

		if (empty($userid)) {
			session(null);
			$this->error('链接已失效', U('Sign/logout'));
		}
		$map['id'] = $userid;
		$map['status'] = 1;
		$map['emailchecked'] = 1;
		$user = M('User')->where($map)->find();
		unset($map);
		if (empty($user)) {
			session(null);
			$this->error('非法操作', U('Sign/logout'));
		}
		if (false === is_password($password)) {
			$this->error('新密码格式错误');
		}
		if ($password !== $repassword) {
			$this->error('两次密码输入不一致');
		}

		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5($password) . $data['salt']);
		$map['id'] = $userid;
		$result = M('User')->where($map)->setField($data);
		if ($result) {
			session(null);
			$this->success('密码重置成功', U('Sign/logout'));
		} else {
			$this->error('密码重置失败<br>数据写入异常<br>请联系管理员<br>' . C('custom.adminEmail'));
		}
	}

}