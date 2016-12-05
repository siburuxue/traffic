<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 短语模板
 */
class PhraseTemplateController extends CommonController {
	/**
	 * 权限
	 */
	public function __construct() {
		parent::__construct();
		// if (false === is_power($this->myPower, 'duty_normal,duty_advance', 'or')) {
		// 	$this->error('没有权限');
		// }

	}

	/**
	 * 首页界面
	 */
	public function index() {
		//模板分类
		$phrase_cate_array = C('custom.phrase_tamplate_cate');
		$phrase_cate = list_to_tree($phrase_cate_array);
		$phrase_cate = tree_to_array($phrase_cate);
		//筛选分类 $target_id均为非末级分类
		$target_id = array();
		foreach ($phrase_cate_array as $key => $val) {
			$target_id[] = $phrase_cate_array[$key]['pid'];
		}
		$target_id = array_unique($target_id);
		$more_target = array('32', '33', '37', '38', '39', '40');
		$target_id = array_merge($target_id, $more_target);

		$this->assign('target_id', $target_id);
		$this->assign('phrase_cate', $phrase_cate);
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
		$condition = get_condition();
		isset($condition['cate']) && $map['cate'] = $condition['cate'];

		// 列表信息
		$Model = D('PhraseTemplateView');
		$count = $Model->where($map)->count('distinct PhraseTemplate.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('PhraseTemplate.id')->select();

		foreach ($list as $key => $val) {
			$phrase_cate = C('custom.phrase_tamplate_cate');
			foreach ($phrase_cate as $key1 => $val1) {
				if ($list[$key]['cate'] == $phrase_cate[$key1]['id']) {
					$list[$key]['cate_name'] = $phrase_cate[$key1]['name'];
				}
			}
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
		$this->display('PhraseTemplate/index/table');
	}

	/**
	 * 新增界面
	 */
	public function add() {
		//短语模板分类
		$phrase_cate_array = C('custom.phrase_tamplate_cate');
		$phrase_cate = list_to_tree($phrase_cate_array);
		$phrase_cate = tree_to_array($phrase_cate);
		$this->assign('phrase_cate', $phrase_cate);
		//筛选分类 $target_id均为非末级分类
		$target_id = array();
		foreach ($phrase_cate_array as $key => $val) {
			$target_id[] = $phrase_cate_array[$key]['pid'];
		}
		$target_id = array_unique($target_id);
		$more_target = array('32', '33', '37', '38', '39', '40');
		$target_id = array_merge($target_id, $more_target);

		$this->assign('target_id', $target_id);
		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('PhraseTemplate');

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
			$this->error('模板数据添加失败');
		}

		// 成功
		$Model->commit();
		$this->success('新增模板数据成功');
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
		$info = M('PhraseTemplate')->getById($id);
		if (empty($info)) {
			$this->error('模板不存在');
		}
		$this->assign('info', $info);
		//短语模板分类
		$phrase_cate_array = C('custom.phrase_tamplate_cate');
		$phrase_cate = list_to_tree($phrase_cate_array);
		$phrase_cate = tree_to_array($phrase_cate);
		$this->assign('phrase_cate', $phrase_cate);
		//筛选分类 $target_id均为非末级分类
		$target_id = array();
		foreach ($phrase_cate_array as $key => $val) {
			$target_id[] = $phrase_cate_array[$key]['pid'];
		}
		$target_id = array_unique($target_id);
		$more_target = array('32', '33', '37', '38', '39', '40');
		$target_id = array_merge($target_id, $more_target);

		$this->assign('target_id', $target_id);

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('PhraseTemplate');

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
			$this->error('模板数据保存失败');
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
		$result = M('PhraseTemplate')->where($map)->setField('is_del', 1);

		// 返回结果
		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

}
