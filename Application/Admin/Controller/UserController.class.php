<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 用户
 */
class UserController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		// 事故处理等级
		$this->assign('trafficLevel', get_dict('traffic_level'));

		// 职务
		$map = array();
		$map['is_del'] = 0;
		$post = M('Post')->where($map)->select();
		$this->assign('post', $post);

		// 角色
		$map = array();
		$map['is_del'] = 0;
		$role = M('Role')->where($map)->select();
		$this->assign('role', $role);

		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);

		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$condition = get_condition();
		isset($condition['user_name']) && $map['user_name'] = $condition['user_name'];
		isset($condition['true_name']) && $map['true_name'] = $condition['true_name'];
		isset($condition['police_no']) && $map['police_no'] = $condition['police_no'];
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		isset($condition['post_id']) && $map['post_id'] = $condition['post_id'];
		isset($condition['role_id']) && $map['role_id'] = $condition['role_id'];
		isset($condition['tel']) && $map['tel'] = $condition['tel'];
		isset($condition['traffic_level_id']) && $map['traffic_level_id'] = $condition['traffic_level_id'];

		// 列表信息
		$Model = D('UserView');
		$count = $Model->where($map)->count('distinct User.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('User.id')->select();

		// 查询扩展信息
		foreach ($list as $key => $value) {
			// 职务
			$where = array();
			$where['user_id'] = $value['id'];
			$where['is_del'] = 0;
			$postName = D('PostView')->where($where)->getField('name', true);
			$list[$key]['post_name'] = empty($postName) ? '' : implode('，', $postName);
			// 角色
			$where = array();
			$where['user_id'] = $value['id'];
			$where['is_del'] = 0;
			$roleName = D('RoleView')->where($where)->getField('name', true);
			$list[$key]['role_name'] = empty($roleName) ? '' : implode('，', $roleName);
		}
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('User/index/table');
	}

	/**
	 * 新增界面
	 */
	public function add() {
		// 事故处理等级
		$this->assign('trafficLevel', get_dict('traffic_level'));

		// 职务
		$map = array();
		$map['is_del'] = 0;
		$post = M('Post')->where($map)->select();
		$this->assign('post', $post);

		// 角色
		$map = array();
		$map['is_del'] = 0;
		$role = M('Role')->where($map)->select();
		$this->assign('role', $role);

		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('User');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 密码
		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5($data['password']) . $data['salt']);

		// 开启事务
		$Model->startTrans();
		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('用户数据保存失败');
		}

		// 保存职务
		$post = I('post.post');
		if (is_array($post) && !empty($post)) {
			$map = array();
			$map['id'] = array('in', $post);
			$map['is_del'] = 0;
			$postIds = M('Post')->where($map)->getField('id', true);
			if (count($post) !== count($postIds)) {
				$Model->rollback();
				$this->error('请正确选择职务');
			}
			$postData = array();
			foreach ($postIds as $value) {
				$postData[] = array('post_id' => $value, 'user_id' => $id);
			}
			$result = M('UserPost')->addAll($postData);
			if (!$result) {
				$Model->rollback();
				$this->error('用户职务数据保存失败');
			}
		}

		// 保存角色
		$role = I('post.role');
		if (is_array($role) && !empty($role)) {
			$map = array();
			$map['id'] = array('in', $role);
			$map['is_del'] = 0;
			$roleIds = M('Role')->where($map)->getField('id', true);
			if (count($role) !== count($roleIds)) {
				$Model->rollback();
				$this->error('请正确选择用户角色');
			}
			$roleData = array();
			foreach ($roleIds as $value) {
				$roleData[] = array('role_id' => $value, 'user_id' => $id);
			}
			$result = M('UserRole')->addAll($roleData);
			if (!$result) {
				$Model->rollback();
				$this->error('用户角色数据保存失败');
			}
		}

		// 成功
		$Model->commit();
		$this->success('新增成功');
	}

	/**
	 * 编辑界面
	 */
	public function edit() {
		// 获取用户编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 用户信息
		$info = M('User')->getById($id);
		if (empty($info)) {
			$this->error('用户不存在');
		}
		$this->assign('info', $info);

		// 当前帐号的职务
		$map = array();
		$map['user_id'] = $id;
		$myPost = M('UserPost')->where($map)->getField('post_id', true);
		$this->assign('my_post', $myPost);

		// 当前帐号角色
		$map = array();
		$map['user_id'] = $id;
		$myRole = M('UserRole')->where($map)->getField('role_id', true);
		$this->assign('my_role', $myRole);

		// 事故处理等级
		$this->assign('trafficLevel', get_dict('traffic_level'));

		// 职务
		$map = array();
		$map['is_del'] = 0;
		$post = M('Post')->where($map)->select();
		$this->assign('post', $post);

		// 角色
		$map = array();
		$map['is_del'] = 0;
		$role = M('Role')->where($map)->select();
		$this->assign('role', $role);

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('User');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 密码
		if (empty($data['password'])) {
			unset($data['password']);
		} else {
			$data['salt'] = \Org\Util\String::randString(6);
			$data['password'] = md5(md5($data['password']) . $data['salt']);
		}

		// 开启事务
		$Model->startTrans();
		$result = $Model->save($data);

		// 数据保存失败
		if (!$result) {
			$Model->rollback();
			$this->error('用户数据保存失败');
		}

		// 获取编号
		$id = $data['id'];

		// 删除职务和角色
		$map = array();
		$map['user_id'] = $id;
		M('UserPost')->where($map)->delete();
		M('UserRole')->where($map)->delete();

		// 保存职务
		$post = I('post.post');
		if (is_array($post) && !empty($post)) {
			$map = array();
			$map['id'] = array('in', $post);
			$map['is_del'] = 0;
			$postIds = M('Post')->where($map)->getField('id', true);
			if (count($post) !== count($postIds)) {
				$Model->rollback();
				$this->error('请正确选择职务');
			}
			$postData = array();
			foreach ($postIds as $value) {
				$postData[] = array('post_id' => $value, 'user_id' => $id);
			}
			$result = M('UserPost')->addAll($postData);
			if (!$result) {
				$Model->rollback();
				$this->error('用户职务数据保存失败');
			}
		}

		// 保存角色
		$role = I('post.role');
		if (is_array($role) && !empty($role)) {
			$map = array();
			$map['id'] = array('in', $role);
			$map['is_del'] = 0;
			$roleIds = M('Role')->where($map)->getField('id', true);
			if (count($role) !== count($roleIds)) {
				$Model->rollback();
				$this->error('请正确选择用户角色');
			}
			$roleData = array();
			foreach ($roleIds as $value) {
				$roleData[] = array('role_id' => $value, 'user_id' => $id);
			}
			$result = M('UserRole')->addAll($roleData);
			if (!$result) {
				$Model->rollback();
				$this->error('用户角色数据保存失败');
			}
		}

		// 成功
		$Model->commit();
		$this->success('保存成功');
	}

	/**
	 * 重置密码
	 */
	public function resetPassword() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 更新密码
		$data['id'] = $id;
		$data['salt'] = \Org\Util\String::randString(6);
		$data['password'] = md5(md5('123456') . $data['salt']);
		$result = M('User')->save($data);

		// 返回结果
		if ($result) {
			$this->success('重置成功');
		} else {
			$this->error('重置失败');
		}
	}

	/**
	 * 删除
	 */
	public function delete() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 更新锁定状态
		$map['id'] = $id;
		$result = M('User')->where($map)->setField('is_del', 1);

		// 返回结果
		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

	/**
	 * 激活
	 */
	public function resume() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 更新锁定状态
		$map['id'] = $id;
		$result = M('User')->where($map)->setField('is_locked', 0);

		// 返回结果
		if ($result) {
			$this->success('激活成功');
		} else {
			$this->error('激活失败');
		}
	}

	/**
	 * 禁用
	 */
	public function forbid() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 更新锁定状态
		$map['id'] = $id;
		$result = M('User')->where($map)->setField('is_locked', 1);

		// 返回结果
		if ($result) {
			$this->success('禁用成功');
		} else {
			$this->error('禁用失败');
		}
	}

	/**
	 * 选择用户
	 */
	public function select() {
		$selectedUserIds = I('request.ids', '', 'trim');
		$ids = explode(',', $selectedUserIds);

		$map = array();
		$map['id'] = array('in', $ids);
		$selectedUser = M('User')->where($map)->select();
		$this->assign('selectedUser', $selectedUser);

		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);
		// dump($department);
		$this->display();

	}

	/**
	 * 获取用户列表
	 */
	public function getUser() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$condition = get_condition();
		isset($condition['true_name']) && $map['true_name'] = array('like', '%' . $condition['true_name'] . '%');
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];

		// 列表信息
		$Model = D('UserView');
		$list = $Model->where($map)->order('create_time desc,id desc')->group('User.id')->getField('id,true_name');
		$list = empty($list) ? array() : $list;

		$this->success($list);

	}
}
