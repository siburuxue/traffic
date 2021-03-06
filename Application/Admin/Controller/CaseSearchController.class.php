<?php
namespace Admin\Controller;
use Think\Controller;
use \Lib\Page;

/**
 * 案件资料录入员---案件查询
 */
class CaseSearchController extends CommonController {
	/**
	 * 列表页画面加载
	 */
	public function index() {
		$this->assign('accident_type', get_custom_config('accident_type'));
		$this->display();
	}

	/**
	 * 列表页表格
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'CaseInfo.accident_time';
			break;
		case 'case_id':
			$orderby = 'CaseInfo.code';
			break;
		default:
			$orderby = 'CaseInfo.create_time';
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

		$map = array();
		$condition = get_condition();
		isset($condition['name']) && $map['name'] = array('LIKE', '%' . $condition['name'] . '%');
		isset($condition['idno']) && $map['idno'] = array('LIKE', '%' . $condition['idno'] . '%');
		isset($condition['car_no']) && $map['car_no'] = $condition['car_no'];
		$clientModel = M('CaseClient');
		$caseIds = $clientModel->field('group_concat(distinct case_id) as case_id')->where($map)->group('case_id')->select();
		if (!isset($condition['name']) && !isset($condition['idno']) && !isset($condition['car_no'])) {
			$caseIds = M('Case')->field('group_concat(id) as case_id')->select();
		}
		$map1 = array();
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map1['CaseInfo.accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map1['CaseInfo.accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map1['CaseInfo.accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		isset($condition['accident_type']) && $map1['accident_type'] = $condition['accident_type'];
		$map1['CaseInfo.id'] = array('in', '-1,' . $caseIds[0]['case_id']);
		$map1['CaseInfo.cate'] = 0;
		$model = D('AccidentSearchLeaderView');
		$count = $model->where($map1)->count('DISTINCT CaseInfo.id');
		$page = new Page($count, 15);
		$list = $model->where($map1)->group('CaseInfo.id')->order($orderby)->limit($page->firstrow . ',' . $page->rows)->select();
		foreach ($list as $key => $val) {
			//逃逸是否抓获
			$map = array();
			$map['case_id'] = $val['id'];
			$map['escape_catch_man_time'] = 0;
			$map['is_escape'] = 1;
			$result = M('CaseClient')->where($map)->select();
			if (count($result) > 0) {
				$list[$key]['is_catch'] = 0;
			} else {
				$list[$key]['is_catch'] = 1;
			}
			// 案件状态
			$caseStatus = new \Lib\CaseStatus($val['id']);
			$list[$key]['case_status'] = $caseStatus->getStatus();
			//是否可以作废
			//受案登记信息
			$map = array();
			$map['case_id'] = $val['id'];
			$map['check_status'] = 0;
			$map['status'] = 1;
			$acceptInfo = M('CaseAccept')->where($map)->select();
			//检验鉴定信息
			$map = array();
			$map['case_id'] = $val['id'];
			$checkUpInfo = M('CaseCheckup')->where($map)->select();
			$map = array();
			$map['case_id'] = $val['id'];
			$map['case_checkup_id'] = $checkUpInfo['id'];
			$map['status'] = 0;
			$checkUpSubmitInfo = M('CaseCheckupReview')->where($map)->select();
			//事故认定、道路交通事故证明信息
			$map = array();
			$map['case_id'] = $val['id'];
			$map['check_status'] = 0;
			$map['is_submit'] = 1;
			$cognizanceInfo = M('CaseCognizance')->where($map)->select();
			//呈请中止信息
			$map = array();
			$map['case_id'] = $val['id'];
			$map['check_status'] = 0;
			$map['is_submit'] = 1;
			$cognizanceStopInfo = M('CaseCognizanceStop')->where($map)->select();
			//主表信息
			$map = array();
			$map['id'] = $val['id'];
			$map['is_over'] = 1;
			$CaseInfo = M('Case')->where($map)->select();
			if (count($cognizanceStopInfo) > 0 || count($cognizanceInfo) > 0) {
				$list[$key]['forbiddenMsg'] = '事故认定正在审批,不能作废';
			}
			if (count($checkUpSubmitInfo) > 0) {
				$list[$key]['forbiddenMsg'] = '检验鉴定正在审批,不能作废';
			}
			if (count($acceptInfo) > 0) {
				$list[$key]['forbiddenMsg'] = '受案登记正在审批,不能作废';
			}
			if (count($CaseInfo) > 0) {
				$list[$key]['forbiddenMsg'] = '该事故已经出具事故认定,不能作废';
			}
			//当事人信息
			$map = array();
			$map['case_id'] = $val['id'];
			$map['status'] = 0;
			$clientDetainList = M('CaseClientDetain')->where($map)->select();
			if (count($clientDetainList) > 0) {
				$list[$key]['forbiddenMsg'] = '当事人物品被扣押,不能作废';
			}
			//被作废的检验鉴定记录
			$map = array();
			$map['case_id'] = $val['id'];
			$map['is_cancel'] = 1;
			$checkUp = M('CaseCheckup')->where($map)->select();
			if (count($checkUpInfo) > 0) {
				if (count($checkUp) > 0) {
					$list[$key]['forbiddenMsg'] = '检验鉴定未作废,不能作废';
				}
			}
			//是否可以退回
			//没有事故认定不能退回
			$map = array();
			$map['case_id'] = $val['id'];
			$map['is_back'] = 0;
			$cognizanceInfo = M('CaseCognizance')->where($map)->select();
			if (count($cognizanceInfo) == 0) {
				$list[$key]['backMsg'] = '没有事故认定，不能退回';
			}
			//未制作不能退回
			$map = array();
			$map['case_id'] = $val['id'];
			$makeCognizanceInfo = M('CaseCognizance')->where($map)->select();
			if ($makeCognizanceInfo[0]['cognizance_type'] == '1') {
				if ($makeCognizanceInfo[0]['is_make'] == '0') {
					$list[$key]['backMsg'] = '事故认定未制作，不能退回';
				}
			}
			//有复核申请，已终止时可以退回
			$map = array();
			$map['case_id'] = $val['id'];
			$map['is_over'] = 0;
			$reviewInfo = M('CaseReview')->where($map)->select();
			if (count($reviewInfo) > 0) {
				$list[$key]['backMsg'] = '该事故认定已申请复核，不能退回';
			}
			$map = array();
			$map['case_id'] = $val['id'];
			$cognizanceList = M('CaseCognizance')->where($map)->order('id desc')->limit(1)->select();
			if ($cognizanceList[0]['is_back'] == 1) {
				$list[$key]['is_back'] = 1;
			} else {
				$list[$key]['is_back'] = 0;
			}
		}
		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('accident_type', get_custom_config('accident_type'));
		$this->assign('page', $pageInfo);
		$this->display('CaseSearch/index/table');
	}

}