<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 字典项
 */
class DictController extends CommonController {

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
		isset($condition['is_custom']) && $map['is_custom'] = $condition['is_custom'];

		// 列表信息
		$Model = M('Dict');
		$count = $Model->where($map)->count();
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('id asc')->limit($page->firstrow . ',' . $page->rows)->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		$this->display('Dict/index/table');
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
		$Model = D('Dict');

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
		$info = M('Dict')->getById($id);
		if (empty($info)) {
			$this->error('字典项不存在');
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
		$Model = D('Dict');

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
		$Model = M('Dict');

		// 开启事务
		$Model->startTrans();

		// 更新锁定状态
		$map = array();
		$map['id'] = $id;
		$result = $Model->where($map)->setField('is_del', 1);

		if (!$result) {
			$Model->rollback();
			$this->error('删除失败');
		}

		// 级联删除
		$map = array();
		$map['dict_id'] = $id;
		M('DictOption')->where($map)->setField('is_del', 1);

		// 成功
		$Model->commit();
		$this->success('删除成功');
	}

}