<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 权限
 */
class PowerController extends CommonController {

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
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$condition = get_condition();
		isset($condition['name']) && $map['name'] = array('like', '%' . $condition['name'] . '%');
		isset($condition['title']) && $map['title'] = array('like', '%' . $condition['title'] . '%');

		// 列表信息
		$Model = D('Power');
		$count = $Model->where($map)->count();
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('name asc,id desc')->limit($page->firstrow . ',' . $page->rows)->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Power/index/table');
	}

	/**
	 * 新增
	 */
	public function add() {
		$this->display();
	}

	/**
	 * 插入
	 */
	public function insert() {
		// 实例化模型
		$Model = D('Power');

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

		// 用户信息
		$info = M('Power')->getById($id);
		if (empty($info)) {
			$this->error('权限节点不存在');
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
		$Model = D('Power');

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

	/**
	 * 删除
	 */
	public function delete() {
		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 实例化模型
		$Model = M('Power');

		// 开启事务
		$Model->startTrans();

		// 删除
		$map = array();
		$map['id'] = $id;
		$result = $Model->where($map)->delete();

		if (!$result) {
			$Model->rollback();
			$this->error('删除失败');
		}

		// 级联删除
		$map = array();
		$map['power_id'] = $id;
		M('RolePower')->where($map)->delete();

		// 成功
		$Model->commit();
		$this->success('删除成功');
	}
}