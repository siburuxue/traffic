<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 内勤人员组别
 */
class DutyGroupController extends CommonController {
	/**
	 * 权限
	 */
	public function __construct() {
		parent::__construct();
		if (false === is_power($this->myPower, 'duty_group_normal,duty_group_advance', 'or')) {
			$this->error('没有权限');
		}

	}
	/**
	 * 首页界面
	 */
	public function index() {

		// 部门
		$map = array();
		$map['is_del'] = 0;
		$map['department_is_del'] = 0;

		$department = M('Department')->where($map)->select();
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);

		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

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
		$map['department_is_del'] = 0;
		$condition = get_condition();
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		//如果是普通权限 那么操作者只能看到自己大队下（$map['department_id']）人员的值班信息
		if (false === is_power($this->myPower, 'duty_group_advance') && true === is_power($this->myPower, 'duty_group_normal')) {
			// 全部部门 $department
			$mapDep = array();
			$mapDep['is_del'] = 0;
			$department = M('Department')->where($mapDep)->select();

			//获取大队ID
			$myBrigade = $this->getMyBrigade();
			$pid = $myBrigade['id'];
			//该部门以及该部门下所有子部门的主键id $allChild
			$allChild = get_all_child($department, $pid);
			$map['department_id'] = array('in', $allChild);
		}

		isset($condition['name']) && $map['name'] = $condition['name'];
		isset($condition['group_type']) && $map['group_type'] = $condition['group_type'];
		// 列表信息
		$Model = D('DutyGroupView');
		$count = $Model->where($map)->count('distinct DutyGroup.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('DutyGroup.id')->select();
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
		$this->display('DutyGroup/index/table');
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
		$info = D('DutyGroupView')->getById($id);
		if (empty($info)) {
			$this->error('组别不存在');
		}

		$info['group_type_name'] = get_custom_config('duty_group_type.' . $info['group_type']);
		$this->assign('info', $info);

		// 部门
		$map = array();
		$map['is_del'] = 0;

		$department = M('Department')->where($map)->select();
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('DutyGroup');

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
			$this->error('用户数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('保存成功');
	}

}
