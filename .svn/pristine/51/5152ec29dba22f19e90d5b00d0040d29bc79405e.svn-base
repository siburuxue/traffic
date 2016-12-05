<?php
namespace Admin\Controller;

/**
 * 字典项
 */
class DictOptionController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		// 字典项
		$dictId = I('get.dict_id', '', 'int');
		if ($dictId === '') {
			$this->error('非法操作');
		}
		$dict = M('Dict')->getById($dictId);
		if (empty($dict)) {
			$this->error('字典项不存在');
		}
		$this->assign('dict', $dict);

		$this->display();
	}

	/**
	 * 表格
	 */
	public function indexTable() {
		// 字典项
		$dictId = I('post.dict_id', '', 'int');
		if ($dictId === '') {
			$this->error('非法操作');
		}
		$dict = M('Dict')->getById($dictId);
		if (empty($dict)) {
			$this->error('字典项不存在');
		}
		$this->assign('dict', $dict);

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['dict_id'] = $dictId;

		// 列表信息
		$Model = M('DictOption');
		$list = $Model->where($map)->order('train asc,id asc')->select();
		$this->assign('list', $list);

		$this->display('DictOption/index/table');
	}

	/**
	 * 新增
	 */
	public function add() {
		// 字典项
		$dictId = I('get.dict_id', '', 'int');
		if ($dictId === '') {
			$this->error('非法操作');
		}
		$dict = M('Dict')->getById($dictId);
		if (empty($dict)) {
			$this->error('字典项不存在');
		}
		$this->assign('dict', $dict);

		$this->display();
	}

	/**
	 * 插入
	 */
	public function insert() {
		// 实例化模型
		$Model = D('DictOption');

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
		$info = D('DictOptionView')->getById($id);
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
		$Model = D('DictOption');

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
		$Model = M('DictOption');

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

		// 成功
		$Model->commit();
		$this->success('删除成功');
	}

	/**
	 * 排序
	 */
	public function train() {
		$id = I('post.id', '', 'int');
		$train = I('post.train', '', 'int');
		if ($id === '' || $train === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['id'] = $id;
		$result = M('DictOption')->where($map)->setField('train', $train);

		if ($result) {
			$this->success('设置成功');
		} else {
			$this->error('设置失败');
		}
	}

}