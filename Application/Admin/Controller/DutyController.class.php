<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 内勤人员值班
 */
class DutyController extends CommonController {
	/**
	 * 权限
	 */
	public function __construct() {
		parent::__construct();
		if (false === is_power($this->myPower, 'duty_normal,duty_advance', 'or')) {
			$this->error('没有权限');
		}

	}
	/**
	 * 首页界面
	 */
	public function index() {

		if (true === is_power($this->myPower, 'duty_advance')) {
			// 部门
			$department = $this->allDepartment;
			$department = list_to_tree($department);
			$department = tree_to_array($department);
			$this->assign('department', $department);
		}
		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'Duty.start_time';
			break;
		default:
			$orderby = 'Duty.create_time';
			break;
		}
		if ($orderSort == 1) {
			$orderby .= ' asc';
		} else {
			$orderby .= ' desc';
		}
		$this->assign('orderField', $orderField);
		$this->assign('orderSort', $orderSort);
		//排序end

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['department_is_del'] = 0;
		$map['user_is_del'] = 0;
		$condition = get_condition();

		if (true === is_power($this->myPower, 'duty_advance')) {
			isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		} else {
			$myBrigade = $this->getMyBrigade();
			$map['department_id'] = $myBrigade['id'];
		}

		isset($condition['true_name']) && $map['true_name'] = $condition['true_name'];
		isset($condition['duty_group_id']) && $map['duty_group_id'] = $condition['duty_group_id'];

		isset($condition['start_time']) && $map['end_time'] = array('egt', strtotime($condition['start_time']));
		isset($condition['end_time']) && $map['start_time'] = array('elt', strtotime($condition['end_time']));

		// 列表信息
		$Model = D('DutyView');
		$count = $Model->where($map)->count('distinct Duty.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderSort)->limit($page->firstrow . ',' . $page->rows)->group('Duty.id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

		// 渲染
		$this->display('Duty/index/table');
	}

	/**
	 * 新增界面
	 */
	public function add() {

		//高级权限duty_advance 可选择部门（大队）--->部门（大队）下值班人员
		if (true === is_power($this->myPower, 'duty_advance')) {

			//所有部门（大队）
			$department = $this->allDepartment;
			$department = list_to_tree($department);
			$department = tree_to_array($department);
			$this->assign('department', $department);

		} else {

			//普通权限duty_normal可选择值班人员
			//所有部门（大队）
			$department = $this->allDepartment;
			//获取大队ID
			$myBrigade = $this->getMyBrigade();
			$pid = $myBrigade['id'];

			//该部门以及该部门下所有子部门的主键id $allChild
			$allChild = get_all_child($department, $pid);

			// 部门人员
			$map = array();
			$map['is_del'] = 0;
			$map['department_id'] = array('in', $allChild);
			$allUsers = M('User')->where($map)->select();

			$this->assign('allUsers', $allUsers);

		}

		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

		// $allDutyGroup = D('DutyGroupView')->select();
		// $this->assign('allDutyGroup', $allDutyGroup);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {

		$duty_group_id = I('post.duty_group_id', '', 'strip_tags');
		$department_id = I('post.department_id', '', 'strip_tags');
		$map = array();
		$map['department_id'] = $department_id;
		$map['group_type'] = $duty_group_id;
		$dutyGroupData = D('DutyGroup')->where($map)->find();
		if (!$dutyGroupData) {
			$this->error('组别未能找到');
		}
		$_POST['duty_group_id'] = $dutyGroupData['id'];
		$myBrigade = $this->getMyBrigade();

		// 实例化模型
		$Model = D('Duty');
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		//部门编号(大队编号)
		if (!isset($data['department_id']) || $data['department_id'] == "") {
			$data['department_id'] = $myBrigade['id'];
		}
		//起始 结束时间转化为时间戳
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		if ($data['start_time'] - time() < 0) {
			$this->error('值班起始时间不能早于当前时间' . date('Y-m-d H:i', time()));

		}
		if ($data['end_time'] - $data['start_time'] <= 0) {
			$this->error('终止时间' . date('Y-m-d H:i', $data['end_time']) . '不能小于起始时间' . date('Y-m-d H:i', $data['start_time']));
		}

		//判断添加的值班人员在同一组内的值日时间有没有重复
		$map = array();
		$map['department_id'] = $data['department_id'];
		$map['duty_group_id'] = $data['duty_group_id'];
		$map['user_id'] = $data['user_id'];
		$map['end_time'] = array('egt', $data['start_time']);
		$map['start_time'] = array('elt', $data['end_time']);
		$map['is_del'] = 0;
		$checkResult = $Model->where($map)->find();
		if ($checkResult) {
			$expression = '该值班人员在该组于' . date("Y-m-d H:i", $checkResult['start_time']) . "---" . date("Y-m-d H:i", $checkResult['end_time']) . "已有排班,不能在" . date("Y-m-d H:i", $data['start_time']) . "---" . date("Y-m-d H:i", $data['end_time']) . "重复排班";
			$this->error($expression);
		}
		// 开启事务
		$Model->startTrans();
		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('用户数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('新增成功');
	}

	/**
	 * 编辑界面
	 */
	public function edit() {
		// 获取值班编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		// 值班信息信息
		$info = D('DutyView')->getById($id);
		if (empty($info)) {
			$this->error('值班信息不存在');
		}
		//值班已经开始则不让修改
		if ($info['start_time'] < time()) {
			$this->error('值班已经开始请勿修改');
		}

		//高级权限duty_advance 可选择部门（大队）--->部门（大队）下值班人员
		if (true === is_power($this->myPower, 'duty_advance')) {

			//所有部门
			$department = $this->allDepartment;
			$department = list_to_tree($department);
			$department = tree_to_array($department);
			$this->assign('department', $department);
			//获取大队ID
			$pid = $info['department_id'];
			//该部门以及该部门下所有子部门的主键id $allChild
			$allChild = get_all_child($department, $pid);
			// 部门人员
			$map = array();
			$map['is_del'] = 0;
			$map['department_id'] = array('in', $allChild);
			$allUsers = M('User')->where($map)->select();

			$this->assign('allUsers', $allUsers);

		} else {
			//所有部门
			$department = $this->allDepartment;
			//获取大队ID
			$myBrigade = $this->getMyBrigade();
			$pid = $myBrigade['id'];

			//该部门以及该部门下所有子部门的主键id $allChild
			$allChild = get_all_child($department, $pid);

			// 部门人员
			$map = array();
			$map['is_del'] = 0;
			$map['department_id'] = array('in', $allChild);
			$allUsers = M('User')->where($map)->select();

			$this->assign('allUsers', $allUsers);

		}

		$this->assign('info', $info);

		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		$duty_group_id = I('post.duty_group_id', '', 'strip_tags');
		$department_id = I('post.department_id', '', 'strip_tags');
		$map = array();
		$map['department_id'] = $department_id;
		$map['group_type'] = $duty_group_id;
		$dutyGroupData = D('DutyGroup')->where($map)->find();
		if (!$dutyGroupData) {
			$this->error('组别未能找到');
		}
		$_POST['duty_group_id'] = $dutyGroupData['id'];

		$myBrigade = $this->getMyBrigade();

		// 实例化模型
		$Model = D('Duty');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		//部门编号(大队编号)
		if (!isset($data['department_id']) || $data['department_id'] == "") {
			$data['department_id'] = $myBrigade['id'];
		}
		//起始 结束时间转化为时间戳
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		if ($data['start_time'] - time() < 0) {
			$this->error('值班起始时间不能早于当前时间' . date('Y-m-d H:i', time()));

		}
		if ($data['end_time'] - $data['start_time'] <= 0) {
			$this->error('终止时间' . date('Y-m-d H:i', $data['end_time']) . '不能小于起始时间' . date('Y-m-d H:i', $data['start_time']));
		}

		//判断添加的值班人员在同一组内的值日时间有没有重复
		$map = array();
		$map['department_id'] = $data['department_id'];
		$map['duty_group_id'] = $data['duty_group_id'];
		$map['user_id'] = $data['user_id'];
		$map['end_time'] = array('egt', $data['start_time']);
		$map['start_time'] = array('elt', $data['end_time']);
		$map['is_del'] = 0;
		$checkResult = $Model->where($map)->find();
		if ($checkResult && $checkResult['id'] != $data['id']) {
			$expression = '该值班人员在该组于' . date("Y-m-d H:i", $checkResult['start_time']) . "---" . date("Y-m-d H:i", $checkResult['end_time']) . "已有排班,不能在" . date("Y-m-d H:i", $data['start_time']) . "---" . date("Y-m-d H:i", $data['end_time']) . "重复排班";
			$this->error($expression);
		}

		// 开启事务
		$Model->startTrans();
		$id = $Model->save($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('用户数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('保存成功');
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
		$info = M('Duty')->getById($id);
		//值班已经开始则不让删除
		if ($info['start_time'] < time()) {
			$this->error('值班已经开始请勿删除');
		}
		// 更新锁定状态
		$map['id'] = $id;
		$result = M('Duty')->where($map)->setField('is_del', 1);

		// 返回结果
		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
	/**
	 * ajax读取该大队下所有可选值班人员
	 */
	public function ajaxAllUsers() {
		$pid = I('post.uid', '', 'int');

		//所有部门
		$department = $this->allDepartment;
		$this->assign('department', $department);

		//该部门以及该部门下所有子部门的主键id $allChild
		$allChild = get_all_child($department, $pid);

		// 部门（大队）下所属人员
		$map = array();
		$map['is_del'] = 0;
		$map['department_id'] = array('in', $allChild);
		$allUsers = M('User')->where($map)->select();
		$this->success($allUsers);

	}

}
