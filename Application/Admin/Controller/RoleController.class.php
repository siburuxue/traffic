<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 角色
 */
class RoleController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		// 渲染
		$this->display();
	}

	public function indexTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;

		// 列表信息
		$Model = M('Role');
		$count = $Model->where($map)->count();
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Role/index/table');
	}

	/**
	 * 新增界面
	 */
	public function add() {
		// 全部权限节点
		$map = array();
		$map['is_del'] = 0;
		$power = M('Power')->where($map)->order('train asc')->select();
		// $this->assign('power', $power);

		$powerCate = M('PowerCate')->order('id asc')->select();

		foreach ($powerCate as $key => &$value) {
			$value['_child'] = list_search($power, 'cate=' . $value['id']);
		}
		unset($value);

		$this->assign('powerCate', $powerCate);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('Role');

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

		// 权限
		$power = I('post.power');
		if (is_array($power) && count($power) > 0) {
			// 验证权限节点有效
			$map = array();
			$map['id'] = array('in', $power);
			$powerArr = M('Power')->where($map)->getField('id', true);
			if (count($power) !== count($powerArr)) {
				$Model->rollback();
				$this->error('请选择有效的权限节点');
			}
			// 整理数据
			$powerData = array();
			foreach ($powerArr as $v) {
				$powerItem = array();
				$powerItem['role_id'] = $id;
				$powerItem['power_id'] = $v;
				$powerData[] = $powerItem;
			}
			// 添加数据
			$result = M('RolePower')->addAll($powerData);
			if (!$result) {
				$Model->rollback();
				$this->error('数据写入失败');
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
		$info = M('Role')->getById($id);
		if (empty($info)) {
			$this->error('角色不存在');
		}
		$this->assign('info', $info);

		// 全部权限节点

		$map = array();
		$map['is_del'] = 0;
		$power = M('Power')->where($map)->order('train asc')->select();

		$powerCate = M('PowerCate')->order('id asc')->select();

		foreach ($powerCate as $key => &$value) {
			$value['_child'] = list_search($power, 'cate=' . $value['id']);
		}
		unset($value);

		$this->assign('powerCate', $powerCate);

		// 当前角色拥有权限
		$map = array();
		$map['role_id'] = $id;
		$thePower = M('RolePower')->where($map)->getField('power_id', true);
		$this->assign('thePower', $thePower);
		/*
			// 全部审核级别
			$map = array();
			$map['is_del'] = 0;
			$roleLevel = M('RoleLevel')->where($map)->select();
			$this->assign('roleLevel', $roleLevel);

			// 当前角色拥有审核级别
			$map = array();
			$map['role_id'] = $id;
			$theRoleLevel = M('RoleLevelAccess')->where($map)->getField('role_level_id', true);
			$this->assign('theRoleLevel', $theRoleLevel);
		*/
		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('Role');

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

		// 删除原权限关系
		$map = array();
		$map['role_id'] = $id;
		M('RolePower')->where($map)->delete();

		// 权限
		$power = I('post.power');
		if (is_array($power) && count($power) > 0) {
			// 验证权限节点有效
			$map = array();
			$map['id'] = array('in', $power);
			$powerArr = M('Power')->where($map)->getField('id', true);
			if (count($power) !== count($powerArr)) {
				$Model->rollback();
				$this->error('请选择有效的权限节点');
			}
			// 整理数据
			$powerData = array();
			foreach ($powerArr as $v) {
				$powerItem = array();
				$powerItem['role_id'] = $id;
				$powerItem['power_id'] = $v;
				$powerData[] = $powerItem;
			}
			// 添加数据
			$result = M('RolePower')->addAll($powerData);
			if (!$result) {
				$Model->rollback();
				$this->error('数据写入失败');
			}
		}

		// 删除原权审核级别关系
		$map = array();
		$map['role_id'] = $id;
		M('RoleLevelAccess')->where($map)->delete();

		// 权限
		$roleLevel = I('post.role_level');
		if (is_array($roleLevel) && count($roleLevel) > 0) {
			// 验证权限节点有效
			$map = array();
			$map['id'] = array('in', $roleLevel);
			$roleLevelArr = M('RoleLevel')->where($map)->getField('id', true);
			if (count($roleLevel) !== count($roleLevelArr)) {
				$Model->rollback();
				$this->error('请选择有效的审核级别');
			}
			// 整理数据
			$roleLevelData = array();
			foreach ($roleLevelArr as $v) {
				$roleLevelItem = array();
				$roleLevelItem['role_id'] = $id;
				$roleLevelItem['role_level_id'] = $v;
				$roleLevelData[] = $roleLevelItem;
			}
			// 添加数据
			$result = M('RoleLevelAccess')->addAll($roleLevelData);
			if (!$result) {
				$Model->rollback();
				$this->error('数据写入失败');
			}
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

		// 更新锁定状态
		$map['id'] = $id;
		$result = M('Role')->where($map)->setField('is_del', 1);

		// 返回结果
		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

}
