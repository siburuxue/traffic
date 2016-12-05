<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 检验鉴定机构
 */
class CheckupOrgController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		$this->display();
	}

	/**
	 * 表格
	 */
	public function indexTable() {
		// 列表信息
		$map['status'] = 1;
		$Model = D('CheckupOrgView');
		$count = $Model->where($map)->count('distinct CheckupOrg.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->group('CheckupOrg.id')->distinct(true)->order('createtime desc')->limit($page->firstrow . ',' . $page->rows)->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->display('CheckupOrg/index/table');
	}

	/**
	 * 作废界面
	 */
	public function void() {
		$this->display();
	}

	/**
	 * 作废表格
	 */
	public function voidTable() {
		// 列表信息
		$map['status'] = 0;
		$Model = D('CheckupOrgView');
		$count = $Model->where($map)->count('distinct CheckupOrg.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->group('CheckupOrg.id')->order('createtime desc,id desc')->limit($page->firstrow . ',' . $page->rows)->select();

		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->display('CheckupOrg/void/table');
	}

	/**
	 * 新增
	 */
	public function add() {
		$map['cat'] = 2;
		$map['is_del'] = 0;
		$department = M('department')->where($map)->select();
		$map1['is_del'] = 0;
		$checkup = M('CheckupOrgItem')->where($map1)->select();
		$level = get_custom_config('checkup_org_obj');

		$this->assign('level', $level);
		$this->assign('department', $department);
		$this->assign('checkup', $checkup);
		$this->display();
	}

	/**
	 * 执行新增
	 */
	public function insert() {
		$Model = D('CheckupOrg');
		$checkupOrgDepartment = D('CheckupOrgDepartment');
		$checkupOrgItemAccess = D('CheckupOrgItemAccess');
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
		//可委托部门
		$department_id = I('post.department_id');
		//可检验鉴定项目
		$checkup_org_item_id = I('post.checkup_org_item_id');
		if (is_array($department_id) && count($department_id) > 0) {
			$subData = array();
			foreach ($department_id as $k => $v) {
				$subItem = $checkupOrgDepartment->create($v);
				if ($subItem === false) {
					$Model->rollback();
					$this->error($checkupOrgDepartment->getError());
				}
				$subItem['checkup_org_id'] = $id;
				$subData[] = $subItem;
			}
			$res = M('CheckupOrgDepartment')->addAll($subData);

			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		} else {
			$this->error('请选择可委托部门');
		}
		if (is_array($checkup_org_item_id) && count($checkup_org_item_id) > 0) {
			$subData = array();
			foreach ($checkup_org_item_id as $k => $v) {
				$subItem = $checkupOrgItemAccess->create($v);
				if ($subItem === false) {
					$Model->rollback();
					$this->error($checkupOrgItemAccess->getError());
				}
				$subItem['checkup_org_id'] = $id;
				$subData[] = $subItem;
			}
			$res = M('CheckupOrgItemAccess')->addAll($subData);

			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		} else {
			$this->error('请选择可检验鉴定项目');
		}
		// 成功
		$Model->commit();
		$this->success('新增成功');
	}

	/**
	 * 编辑
	 */
	public function edit() {
		$id = I('get.id');

		$map['checkup_org_id'] = $id;
		$departmentList = M('CheckupOrgDepartment')->where($map)->select();
		$checkupItemList = M('CheckupOrgItemAccess')->where($map)->select();

		$map1['id'] = $id;
		$map1['status'] = 1;
		$checkupInfo = M('CheckupOrg')->where($map1)->find();

		$map2['cat'] = 2;
		$map2['is_del'] = 0;
		$department = M('department')->where($map2)->select();
		$map3['is_del'] = 0;

		$checkup = M('CheckupOrgItem')->where($map3)->select();
		$level = get_custom_config('checkup_org_obj');

		$this->assign('level', $level);
		$this->assign('department', $department);
		$this->assign('checkup', $checkup);
		$this->assign('departmentList', json_encode($departmentList));
		$this->assign('checkupItemList', json_encode($checkupItemList));
		$this->assign('checkupInfo', $checkupInfo);
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		$id = I('post.id');
		$map1['checkup_org_id'] = $id;
		$Model = D('CheckupOrg');
		$checkupOrgDepartment = D('CheckupOrgDepartment');
		$checkupOrgItemAccess = D('CheckupOrgItemAccess');
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 开启事务
		$Model->startTrans();
		$rs = $Model->save($data);

		// 数据保存失败
		if ($rs === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		$rs1 = M('CheckupOrgDepartment')->where($map1)->delete();
		if ($rs1 === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		$rs2 = M('CheckupOrgItemAccess')->where($map1)->delete();
		if ($rs2 === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		//可委托部门
		$department_id = I('post.department_id');
		//可检验鉴定项目
		$checkup_org_item_id = I('post.checkup_org_item_id');
		if (is_array($department_id) && count($department_id) > 0) {
			$subData = array();
			foreach ($department_id as $k => $v) {
				$subItem = $checkupOrgDepartment->create($v);
				if ($subItem === false) {
					$Model->rollback();
					$this->error($checkupOrgDepartment->getError());
				}
				$subItem['checkup_org_id'] = $id;
				$subData[] = $subItem;
			}
			$res = M('CheckupOrgDepartment')->addAll($subData);

			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		} else {
			$this->error('请选择可委托部门');
		}
		if (is_array($checkup_org_item_id) && count($checkup_org_item_id) > 0) {
			$subData = array();
			foreach ($checkup_org_item_id as $k => $v) {
				$subItem = $checkupOrgItemAccess->create($v);
				if ($subItem === false) {
					$Model->rollback();
					$this->error($checkupOrgItemAccess->getError());
				}
				$subItem['checkup_org_id'] = $id;
				$subData[] = $subItem;
			}
			$res = M('CheckupOrgItemAccess')->addAll($subData);

			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		} else {
			$this->error('请选择可检验鉴定项目');
		}
		// 成功
		$Model->commit();
		$this->success('编辑成功');
	}

	/**
	 * 作废
	 */
	public function delete() {
		// 获取编号
		$id = I('post.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		// 作废缘由
		$delReason = I('post.del_reason', '', 'trim,htmlspecialchars');
		if ($delReason === '') {
			$this->error('作废缘由必须填写');
		}

		// 报警信息
		$info = M('CheckupOrg')->getById($id);
		if (empty($info)) {
			$this->error('检验鉴定机构不存在');
		}
		if ($info['status'] == 0) {
			$this->error('检验鉴定机构已作废');
		}

		// 更新数据
		$data = array();
		$data['id'] = $id;
		$data['status'] = 0;
		$data['del_reason'] = $delReason;
		$data['update_time'] = time();
		$data['update_user_id'] = $this->my['id'];

		$result = M('CheckupOrg')->field('id,status,del_reason,update_time,update_user_id')->save($data);

		// 返回结果
		if ($result) {
			$this->success('作废成功');
		} else {
			$this->error('数据保存失败');
		}
	}
}