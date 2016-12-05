<?php
namespace Admin\Controller;

/**
 * 组织机构维护
 */
class DepartmentController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {

		$this->display();
	}

	/**
	 * 表格
	 */
	public function indexTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;

		// 列表信息
		$Model = D('Department');
		$list = $Model->where($map)->order('train asc')->select();

		$list = list_to_tree($list);
		$list = tree_to_array($list);
		$this->assign('list', $list);

		// 部门类型
		$this->assign('departmentType', get_custom_config('department_type'));

		// 渲染
		$this->display('Department/index/table');
	}

	/**
	 * 新增
	 */
	public function add() {
		// 部门
		$map = array();
		$map['is_del'] = 0;
		$department = M('Department')->where($map)->order('train asc')->select();
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);

		// 部门类型
		$this->assign('departmentType', get_custom_config('department_type'));

		// 渲染
		$this->display();
	}

	/**
	 * 插入
	 */
	public function insert() {
		// 实例化模型
		$Model = D('Department');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 开启事务
		$Model->startTrans();
		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 如果新增部门是大队，插入6条记录到值班组
		if ($data['cate'] == 2) {
			$dutyGroupData = array();
			$dutyGroupType = get_custom_config('duty_group_type');
			foreach ($dutyGroupType as $key => $value) {
				$dutyGroupItem = array();
				$dutyGroupItem['group_type'] = $key;
				$dutyGroupItem['department_id'] = $id;
				$dutyGroupItem['create_time'] = time();
				$dutyGroupItem['create_user_id'] = $this->my['id'];
				$dutyGroupItem['update_time'] = time();
				$dutyGroupItem['update_user_id'] = $this->my['id'];
				$dutyGroupItem['is_del'] = 0;
				$dutyGroupData[] = $dutyGroupItem;
			}
			$res = M('DutyGroup')->addAll($dutyGroupData);
			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		}

		// 成功
		$Model->commit();
		$this->success('新增成功');
	}

	/**
	 * 编辑
	 */
	public function edit() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 信息
		$map = array();
		$map['id'] = $id;
		$map['is_del'] = 0;
		$info = M('Department')->where($map)->find();
		if (empty($info)) {
			$this->error('部门不存在');
		}
		$this->assign('info', $info);

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('Department');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 开启事务
		$Model->startTrans();
		$result = $Model->save($data);

		// 数据保存失败
		if (!$result) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 获取编号
		$id = $data['id'];

		// 成功
		$Model->commit();
		$this->success('保存成功');
	}

	public function delete() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 检查是否存在下级部门
		$map = array();
		$map['pid'] = $id;
		$map['is_del'] = 0;
		$child = M('Department')->where($map)->find();
		if (!empty($child)) {
			$this->error('请先清空下级部门');
		}

		// 检查是否存在部门人员
		$map = array();
		$map['department_id'] = $id;
		$map['is_del'] = 0;
		$user = M('User')->where($map)->find();
		if (!empty($user)) {
			$this->error('请先清空部门人员');
		}

		// 标记删除
		$data = array();
		$data['id'] = $id;
		$data['is_del'] = 1;
		$data['update_time'] = time();
		$data['update_user_id'] = $this->my['id'];
		$result = M('Department')->save($data);

		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('数据保存失败');
		}
	}

	/**
	 * 添加人员
	 */
	public function addUser() {
		$this->display();
	}

	/**
	 * 插入人员
	 */
	public function insertUser() {

	}

	/**
	 * 部门下人员列表
	 */
	public function user() {
		$this->display();
	}

	/**
	 * 部门下人员列表 表格
	 */
	public function userTable() {
		$this->display('Department/user/table');
	}

	/**
	 * 搜索人员列表
	 */
	public function searchUser() {
		$this->display();
	}

	/**
	 * 搜索人员列表 表格
	 */
	public function searchUserTable() {
		$this->display('Department/searchUser/table');
	}

	/**
	 * 将人员加入到当前部门
	 */
	public function bindUser() {

	}

	/**
	 * 将人员从当前部门移除
	 */
	public function unbindUser() {

	}
}