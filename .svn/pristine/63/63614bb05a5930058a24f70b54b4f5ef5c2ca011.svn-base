<?php
namespace Admin\Controller;

/**
 * 检验鉴定机构-可进行检验鉴定项目
 */
class CheckupOrgItemController extends CommonController {

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
		$map = array('is_del' => 0);
		$list = M('CheckupOrgItem')->field('id,name,checkup_org_obj')->where($map)->order('checkup_org_obj asc,train desc')->select();
		$dict = get_custom_config('checkup_org_obj');
		$this->assign('list', $list);
		$this->assign('dict', $dict);
		$this->display('CheckupOrgItem/index/table');
	}

	/**
	 * 新增
	 */
	public function add() {
		$rs = get_custom_config('checkup_org_obj');
		$this->assign('level', $rs);
		$this->display();
	}

	/**
	 * 执行新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('CheckupOrgItem');

		// 创建数据
		$data = $Model->create();

		if (false === $data) {
			$this->error($Model->getError());
		}
		$id = $Model->add($data);
		// 数据保存失败
		if (!$id) {
			$this->error('数据保存失败');
		}
		$this->success('新增成功');
	}

	/**
	 * 编辑
	 */
	public function edit() {
		$id = I('get.id', '', 'int');
		if ($id === "") {
			$this->error('非法操作');
		}
		$map = array('id' => $id, 'is_del' => 0);
		$rs = M('CheckupOrgItem')->where($map)->find();
		$level = get_custom_config('checkup_org_obj');
		$this->assign('level', $level);
		$this->assign('info', $rs);
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('CheckupOrgItem');

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
		$Model->commit();
		$this->success('数据保存成功');
	}

	/**
	 * 删除
	 */
	public function delete() {
		$id = I('get.id');
		$map = array('id' => $id);
		$rs = M('CheckupOrgItem')->where($map)->setField('is_del', 1);
		if ($rs === false) {
			$this->error('操作失败');
		}
		$this->success('操作成功');
	}
}
?>