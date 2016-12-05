<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 复核科 - 复核申请
 */
class CaseReviewApplyHandleInfoController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		// 复核信息编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('非法操作');
		}
		$this->assign('caseReview', $caseReview);

		$this->display();

	}

	/**
	 * 列表
	 */
	public function indexTable() {
		$caseReviewId = I('post.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('非法操作');
		}
		$this->assign('caseReview', $caseReview);

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['case_review_id'] = $caseReviewId;
		// 列表信息
		$Model = D('CaseReviewApply');
		$count = $Model->where($map)->count('distinct id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('id desc')->limit($page->firstrow . ',' . $page->rows)->group('id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseReviewApplyHandleInfo/index/table');

	}

	/**
	 * 新增
	 * 新增必要条件：复核未拒绝受理，复核未进入审核流程，复核未终止
	 */
	public function add() {
		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		/*
			if ($caseReview['accept_status'] == 2) {
				$this->error('复核已拒绝');
			}
			if ($caseReview['check_status'] != 0) {
				$this->error('复核已进入审核流程');
			}
			if ($caseReview['stop_status'] != 0) {
				$this->error('复核已终止');
			}
		*/
		$this->assign('caseReview', $caseReview);

		// 交通方式
		$this->assign('trafficType', get_custom_config('traffic_type'));

		$this->display();
	}

	/**
	 * 插入记录
	 */
	public function insert() {
		$Model = D('CaseReviewApply');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 复核信息
		$caseReviewId = $data['case_review_id'];
		$caseReview = M('CaseReview')->getById($caseReviewId);
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		/*
			if ($caseReview['accept_status'] == 2) {
				$this->error('复核已拒绝');
			}
		*/
		// 案件编号
		$data['case_id'] = $caseReview['case_id'];
		// 案件当事人编号
		$caseClientId = $data['case_client_id'];
		// 案件当事人相关人编号
		$caseClientRelaterId = $data['case_client_relater_id'];

		if ($caseClientRelaterId) {
			// 如果相关人编号有效
			$map = array();
			$map['id'] = $caseClientRelaterId;
			$map['case_client_id'] = $caseClientId;
			$caseClientRelater = M('CaseClientRelater')->where($map)->find();
			if (empty($caseClientRelater)) {
				$this->error('申请人信息不存在');
			}
			$data['name'] = $caseClientRelater['name'];
		} else {
			// 当事人
			$map = array();
			$map['id'] = $caseClientId;
			$map['is_del'] = 0;
			$caseClient = M('CaseClient')->where($map)->find();
			if (empty($caseClient)) {
				$this->error('申请人信息不存在');
			}
			$data['name'] = $caseClient['name'];
		}

		// 申请人姓名
		$applyName = $data['name'];

		// 开启事务
		$Model->startTrans();

		// 插入
		$id = $Model->add($data);
		if (!$id) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 更新复核主表信息
		$time = time();
		$userId = $this->my['id'];
		$data = array();
		$data['id'] = $caseReviewId;
		$data['apply_status'] = 1;
		$data['apply_time'] = $time;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('CaseReview')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 更新案件表
		$data = array();
		$data['id'] = $caseReview['case_id'];
		$data['review_submit_status'] = 1;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('Case')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$this->saveCaseLog($caseReview['case_id'], date('Y-m-d H:i', $time) . $applyName . '提请复核');

		// 提交数据
		$Model->commit();
		$this->success('新增成功');

	}

	/**
	 * 编辑
	 * 编辑必要条件：复核未拒绝受理，复核未进入审核流程，复核未终止
	 */
	public function edit() {
		// 复核申请编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 复核申请
		$map = array();
		$map['id'] = $id;
		$info = M('CaseReviewApply')->where($map)->find();
		if (empty($info)) {
			$this->error('申请不存在');
		}
		$this->assign('info', $info);

		// 复核信息
		$map = array();
		$map['id'] = $info['case_review_id'];
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		$this->assign('caseReview', $caseReview);

		// 交通方式
		$this->assign('trafficType', get_custom_config('traffic_type'));

		$this->display();

	}

	/**
	 * 更新
	 */
	public function update() {

		$Model = D('CaseReviewApply');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 插入
		$res = $Model->save($data);

		if (!$res) {
			$this->error('数据更新失败');
		}

		$this->success('更新成功');
	}

	/**
	 * 获取申请人列表
	 */
	public function getApplyer() {
		$applyer = array();

		// 案件编号
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '') {
			$this->success($applyer);
		}

		// 申请人 当事人
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->select();

		// 申请人 当事人相关人
		$map = array();
		$map['case_id'] = $caseId;
		$map['case_client_is_del'] = 0;
		$caseClientRelater = D('CaseClientRelaterView')->where($map)->select();

		foreach ($caseClient as $client) {
			$item = array();
			$item['case_client_id'] = $client['id'];
			$item['case_client_relater_id'] = 0;
			$item['name'] = $client['name'];
			$item['disabled'] = 0;
			$applyer[] = $item;

			$theCaseClientRelater = list_search($caseClientRelater, 'case_client_id=' . $client['id']);
			foreach ($theCaseClientRelater as $relater) {
				$item = array();
				$item['case_client_id'] = $relater['case_client_id'];
				$item['case_client_relater_id'] = $relater['id'];
				$item['name'] = '|--&nbsp;&nbsp;' . $relater['name'];
				$item['disabled'] = 0;
				$applyer[] = $item;
			}
		}

		$this->success($applyer);

	}

	/**
	 * 获取申请人信息
	 */
	public function getApplyerInfo() {
		$caseClientId = I('post.case_client_id', '', 'int');
		$caseClientRelaterId = I('post.case_client_relater_id', '', 'int');
		if ($caseClientId === '' || $caseClientRelaterId === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['id'] = $caseClientId;
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->find();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		$info = array();
		if ($caseClientRelaterId == 0) {
			$info['name'] = $caseClient['name'];
			$info['sex'] = $caseClient['sex'];
			$info['idno'] = $caseClient['idno'];
			$info['traffic_type'] = $caseClient['traffic_type'];
			$info['relation'] = '本人';

		} else {
			$map = array();
			$map['id'] = $caseClientRelaterId;
			$map['case_client_id'] = $caseClientId;
			$caseClientRelater = M('CaseClientRelater')->where($map)->find();
			if (empty($caseClientRelater)) {
				$this->error('当事人相关人员不存在');
			}
			$info['name'] = $caseClientRelater['name'];
			$info['sex'] = $caseClientRelater['sex'];
			$info['idno'] = $caseClientRelater['idno'];
			$info['traffic_type'] = '';
			$info['relation'] = get_custom_config('client_relation.' . $caseClientRelater['relation']);

		}

		$this->success($info);

	}

}