<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 角色的用户
 */
class RoleUserController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		$roleId = I('get.role_id', '', 'int');
		if ($roleId === '') {
			$this->error('非法操作');
		}

		// 角色信息
		$roleInfo = M('Role')->getById($roleId);
		if (empty($roleInfo)) {
			$this->error('非法操作');
		}
		$this->assign('roleInfo', $roleInfo);

		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
		// 搜索条件
		$roleId = I('post.condition_role_id', 0, 'int');
		$map = array();
		$map['role_id'] = $roleId;
		$map['is_del'] = 0;

		$this->assign('role_id', $roleId);

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
		$this->display('RoleUser/index/table');
	}

	/**
	 * 新增界面
	 */
	public function search() {
		$roleId = I('get.role_id', '', 'int');
		if ($roleId === '') {
			$this->error('非法操作');
		}

		// 角色信息
		$roleInfo = M('Role')->getById($roleId);
		if (empty($roleInfo)) {
			$this->error('非法操作');
		}
		$this->assign('roleInfo', $roleInfo);

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
	 * 新增 查询列表
	 */
	public function searchTable() {

		$roleId = I('post.role_id', '', 'int');
		if ($roleId === '') {
			$this->error('非法操作');
		}

		// 角色信息
		$roleInfo = M('Role')->getById($roleId);
		if (empty($roleInfo)) {
			$this->error('非法操作');
		}
		$this->assign('roleInfo', $roleInfo);

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

			$where = array();
			$where['user_id'] = $value['id'];
			$where['role_id'] = $roleInfo['id'];
			$selected = M('UserRole')->where($where)->find();
			$list[$key]['is_selected'] = empty($selected) ? 0 : 1;
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
		$this->display('RoleUser/search/table');
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 获取编号
		$roleId = I('get.role_id', '', 'int');
		$userId = I('get.user_id', '', 'int');
		if ($roleId === '' || $userId === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['role_id'] = $roleId;
		$map['user_id'] = $userId;

		// 判断是否已存在
		$unique = M('UserRole')->where($map)->find();
		if (!empty($unique)) {
			$this->error('已选取');
		}

		// 添加
		$result = M('UserRole')->add($map);

		// 返回结果
		if ($result) {
			$this->success('添加成功');
		} else {
			$this->error('添加失败');
		}
	}

	/**
	 * 删除
	 */
	public function delete() {
		// 获取编号
		$roleId = I('get.role_id', '', 'int');
		$userId = I('get.user_id', '', 'int');
		if ($roleId === '' || $userId === '') {
			$this->error('非法操作');
		}

		// 执行
		$map['role_id'] = $roleId;
		$map['user_id'] = $userId;
		$result = M('UserRole')->where($map)->delete();

		// 返回结果
		if ($result) {
			$this->success('取消授权成功');
		} else {
			$this->error('数据更新失败');
		}
	}

}
