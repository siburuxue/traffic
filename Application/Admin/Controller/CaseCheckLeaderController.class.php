<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 办案人 案件
 */
class CaseCheckLeaderController extends CommonController {

	/**
	 * 待办工作
	 */
	public function pending() {
		$departmentMap = array();
		$departmentMap['cate'] = 2;
		$departmentMap['is_del'] = 0;
		$departmentList = M('Department')->where($departmentMap)->select();
		$this->assign('accidentType', get_custom_config('accident_type'));
		$this->assign('checkType', get_custom_config('check_type'));
		$this->assign('departmentList', $departmentList);
		$this->display();
	}

	/**
	 * 待办工作 表格
	 */
	public function pendingTable() {
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
		case 'time_limit':
			$orderby = 'CaseInfo.create_time';
			if ($orderSort == 1) {
				$time_limit = 1;
			} else {
				$time_limit = 2;
			}

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

		$levelList = array('一级', '二级', '三级');
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['CaseInfo.is_del'] = 0;
		//只显示未审批的案件
		$map['CaseCheck.status'] = 0;
		//显示指派给当前领导的案件
		$map['CaseCheck.check_user_id'] = $this->my['id'];
		//有效的指派信息
		$map['CaseCheck.is_del'] = 0;
		//当前办案人
		$map['CaseHandle.is_now'] = 1;

		//查询条件
		$condition = get_condition();
		isset($condition['code']) && $map['code'] = array('LIKE', '%' . $condition['code'] . "%");
		isset($condition['accident_type']) && $map['accident_type'] = $condition['accident_type'];
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		isset($condition['cate']) && $map['cate'] = $condition['cate'];
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}

		// 列表信息
		$Model = D('LeaderUncompletedCaseView');
		$count = $Model->where($map)->count('CaseInfo.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->select();
		// 查询扩展信息
		foreach ($list as $key => $value) {
			$list[$key]['accident_type_name'] = get_custom_config('accident_type.' . $value['accident_type']);
			$list[$key]['approve_name'] = get_custom_config('check_type.' . $value['cate']) . $levelList[(int) $value['level']] . "待审批";
		}
		$list = $this->calculateTimeLimit($list);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseCheckLeader/pending/table');
	}

	/**
	 * 已完成工作
	 */
	public function completed() {
		$departmentMap = array();
		$departmentMap['cate'] = 2;
		$departmentMap['is_del'] = 0;
		$departmentList = M('Department')->where($departmentMap)->select();
		$this->assign('accidentType', get_custom_config('accident_type'));
		$this->assign('checkType', get_custom_config('check_type'));
		$this->assign('departmentList', $departmentList);
		$this->assign('traffic_type', get_custom_config('traffic_type'));
		$this->display();
	}

	/**
	 * 已完成工作 表格
	 */
	public function completedTable() {

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
		case 'time_limit':
			$orderby = 'CaseInfo.create_time';
			if ($orderSort == 1) {
				$time_limit = 1;
			} else {
				$time_limit = 2;
			}

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

		$levelList = array('一级', '二级', '三级');
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['CaseInfo.is_del'] = 0;
		//只显示审批的案件
		$map['CaseCheck.status'] = array('neq', 0);
		//显示指派给当前领导的案件
		$map['CaseCheck.check_user_id'] = $this->my['id'];
		//有效的指派信息
		$map['CaseCheck.is_del'] = 0;
		//当前办案人
		$map['CaseHandle.is_now'] = 1;
		//查询条件
		$condition = get_condition();
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		isset($condition['accident_type']) && $map['accident_type'] = $condition['accident_type'];
		isset($condition['client']) && $map['CaseClient.name'] = array('LIKE', '%' . $condition['client'] . '%');
		isset($condition['car_no']) && $map['CaseClient.car_no'] = array('LIKE', '%' . $condition['car_no'] . '%');
		isset($condition['traffic_type']) && $map['CaseClient.traffic_type'] = $condition['traffic_type'];
		isset($condition['idno']) && $map['CaseClient.idno'] = array('LIKE', '%' . $condition['idno'] . '%');
		isset($condition['user']) && $map['CaseUser.true_name'] = array('LIKE', '%' . $condition['user'] . '%');

		// 列表信息
		$Model = D('LeaderCompletedCaseView');
		$count = $Model->where($map)->count('CaseInfo.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->select();

		// 查询扩展信息
		foreach ($list as $key => $value) {
			$list[$key]['accident_type_name'] = get_custom_config('accident_type.' . $value['accident_type']);
			$list[$key]['approve_name'] = get_custom_config('check_type.' . $value['cate']) . $levelList[(int) $value['level']] . "已审批";
		}
		$list = $this->calculateTimeLimit($list, 'id', $time_limit);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->display('CaseCheckLeader/completed/table');
	}

	/**
	 * 受案登记详细信息
	 */
	public function CaseAcceptInfo() {
		$id = I('get.id', '', 'int');
		$checkRs = M('CaseCheck')->getById($id);
		$itemId = $checkRs['item_id'];
		$map = array();
		$map['id'] = $itemId;
		$info = M('CaseAccept')->where($map)->find();
		//案件来源
		$this->assign('caseSource', get_dict('case_source'));
		$this->assign('info', $info);
		$this->assign('checkRs', $checkRs);
		$this->assign('id', $id);
		$this->assign('action', I('get.action'));
		$this->display('CaseCheckLeader/Info/CaseAcceptInfo');
	}

	/**
	 * 调查报告详细信息
	 */
	public function reportInfo() {
		$id = I('get.id', '', 'int');
		$rs = M('CaseCheck')->getById($id);
		if ($rs['cate'] == '2') {
			$normalInfo = M('CaseCognizanceNormal')->getById($rs['item_id']);
			$info = M('CaseCognizanceReport')->getById($normalInfo['case_cognizance_report_id']);
		} else {
			$normalInfo = M('CaseCognizanceProve')->getById($rs['item_id']);
			$info = M('CaseCognizanceReport')->getById($normalInfo['case_cognizance_report_id']);
		}
		//案件信息
		$map = array();
		$map['id'] = $rs['case_id'];
		$map['is_del'] = 0;
		$caseInfo = M('Case')->where($map)->find();
		//审批人列表
		if ($rs['level'] == '0') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_2");
		} else if ($rs['level'] == '1') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_3");
		}
		$this->assign('id', $id);
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->assign('checkRs', $rs);
		$this->assign('caseInfo', $caseInfo);
		$this->display('CaseCheckLeader/Info/CaseReportInfo');
	}

	/**
	 * 事故认定详细信息
	 */
	public function normalInfo() {
		$id = I('get.id', '', 'int');
		$rs = M('CaseCheck')->getById($id);
		$info = M('CaseCognizanceNormal')->getById($rs['item_id']);
		//案件信息
		$map = array();
		$map['id'] = $rs['case_id'];
		$map['is_del'] = 0;
		$caseInfo = M('Case')->where($map)->find();
		//审批人列表
		if ($rs['level'] == '0') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_2");
		} else if ($rs['level'] == '1') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_3");
		}
		$this->assign('id', $id);
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->assign('checkRs', $rs);
		$this->assign('caseInfo', $caseInfo);
		$this->display('CaseCheckLeader/Info/CaseNormalInfo');
	}

	/**
	 * 事故交通道路证明信息
	 */
	public function proveInfo() {
		$id = I('get.id', '', 'int');
		$rs = M('CaseCheck')->getById($id);
		$info = M('CaseCognizanceProve')->getById($rs['item_id']);
		//案件信息
		$map = array();
		$map['id'] = $rs['case_id'];
		$map['is_del'] = 0;
		$caseInfo = M('Case')->where($map)->find();
		//审批人列表
		if ($rs['level'] == '0') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_2");
		} else if ($rs['level'] == '1') {
			$list = $this->getCheckUserList("case_cognizance_accident_identification_3");
		}
		$this->assign('id', $id);
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->assign('checkRs', $rs);
		$this->assign('caseInfo', $caseInfo);
		$this->display('CaseCheckLeader/Info/CaseProveInfo');
	}

	/**
	 * 呈请事故中止详细信息
	 */
	public function terminationInfo() {
		$id = I('get.id', '', 'int');
		$rs = M('CaseCheck')->getById($id);
		$info = M('CaseCognizanceStop')->getById($rs['item_id']);
		//审批人列表
		if ($rs['level'] == '0') {
			$list = $this->getCheckUserList("case_cognizance_stop_2");
		} else if ($rs['level'] == '1') {
			$list = $this->getCheckUserList("case_cognizance_stop_3");
		}
		$this->assign('id', $id);
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->assign('checkRs', $rs);
		$this->display('CaseCheckLeader/Info/CaseTerminationInfo');
	}

	/**
	 * 检验鉴定相关审批 详细信息
	 */
	public function CaseCheckupInfo() {

		$id = I('get.id', '', 'int');
		$checkRs = M('CaseCheck')->getById($id);
		$itemId = $checkRs['item_id'];
		$level = (int) $checkRs['level'];
		$levelUp = (int) $checkRs['level'] + 1;
		/*    检验鉴定审批类型  custom.php
			*	10 => "检验鉴定-延期",
				11 => "检验鉴定-超期",
				12 => "检验鉴定-延期并超期",
				13 => "检验鉴定-重新申请检验鉴定",
			*
		*/
		$power = $this->myPower;
		//判断审批信息类型 超期 延期 超期并延期
		//延期
		if ($checkRs['cate'] == 10) {
			if (false === is_power($this->myPower, "case_checkup_check_delay_" . $level)) {
				$this->error('没有权限');
			}
			//审批人列表
			$list = $this->getCheckUserList("case_checkup_check_delay_" . $levelUp);
		}
		//超期
		if ($checkRs['cate'] == 11) {
			if (false === is_power($this->myPower, "case_checkup_check_out_" . $level)) {
				$this->error('没有权限');
			}
			//审批人列表
			$list = $this->getCheckUserList("case_checkup_check_out_" . $levelUp);
		}
		//超期并延期
		if ($checkRs['cate'] == 12) {
			if (false === is_power($this->myPower, "case_checkup_check_delay_" . $level)) {
				$this->error('没有权限');
			}
			if (false === is_power($this->myPower, "case_checkup_check_out_" . $level)) {
				$this->error('没有权限');
			}
			//审批人列表
			$list = $this->getCheckUserComplexList("case_checkup_check_delay_" . $levelUp, "case_checkup_check_out_" . $levelUp);
		}
		//重新检验鉴定
		if ($checkRs['cate'] == 13) {
			if (false === is_power($this->myPower, "case_checkup_check_again_" . $level)) {
				$this->error('没有权限');
			}
			//审批人列表
			$list = $this->getCheckUserList("case_checkup_check_again_" . $levelUp);
		}
		$this->assign('list', $list);

		//鉴定对象一级选项
		$case_checkup_obj_type = C('custom.case_checkup_obj_type');
		$this->assign('case_checkup_obj_type', $case_checkup_obj_type);

		$rs = M('CaseCheck')->getById($id);
		//检验鉴定审核信息CaseCheckupReview
		$map = array();
		//$map['case_checkup_id'] = $itemId;
		$map['id'] = $checkRs['origin_id'];
		//$map['status'] = 0;
		//$map['create_user_id'] = $rs['create_user_id'];
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->order('create_time desc')->find();
		$this->assign('caseCheckupReviewData', $caseCheckupReviewData);

		$info = M('CaseCognizanceNormal')->getById($rs['item_id']);
		//案件信息
		$map = array();
		$map['id'] = $rs['case_id'];
		$map['is_del'] = 0;
		$caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();

		//案件信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $checkRs['case_id'];
		$caseData = D('Case')->where($map)->find();
		$this->assign('caseData', $caseData);

		//获取该案件办案人所在大队信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $caseData['create_user_id'];
		$caseDealUserData = D('UserView')->where($map)->find();
		if (!$caseDealUserData) {
			$this->error('办案人信息获取失败');
		}
		$this->assign('caseDealUserData', $caseDealUserData);

		//检验鉴定信息
		$map = array();
		$map['id'] = $itemId;
		$info = D('CaseCheckupClientView')->where($map)->find();

		//检验鉴定委托信息
		//读取检验鉴定委托信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $info['id'];
		$map['case_id'] = $info['case_id'];
		$caseCheckupEntrustData = D('CaseCheckupEntrustOrgView')->where($map)->order('create_time desc')->find();
		$this->assign('caseCheckupEntrustData', $caseCheckupEntrustData);

		//案件来源
		$this->assign('caseSource', get_dict('case_source'));
		$this->assign('info', $info);
		$this->assign('caseCheckupData', $info);
		$this->assign('caseCheckupEntrustData', $caseCheckupEntrustData);
		$this->assign('checkRs', $checkRs);
		$this->assign('id', $id);
		$this->assign('type', I('get.type'));
		$this->display('CaseCheckLeader/Info/CaseCheckupInfo');
	}

	/**
	 * 确定并提审
	 */
	public function checkApprove() {
		$check_user_id = I('post.check_user_id', '', 'int');
		if ($check_user_id == '') {
			$this->error('审批人不能为空');
		}
		$id = I('post.id', '', 'int');
		$info = M('CaseCheck')->getbyId($id);
		$data = array();
		$data['case_id'] = $info['case_id'];
		$data['cate'] = $info['cate'];
		$data['item_id'] = $info['item_id'];
		$data['origin_id'] = $info['origin_id'];
		$data['check_user_id'] = $check_user_id;
		$data['pid'] = $id;
		$data['level'] = (int) $info['level'] + 1;
		$map = array();
		$map['id'] = $id;
		$map['status'] = 1;
		$map['remark'] = I('post.remark');
		$map['check_time'] = time();
		$model = D('CaseCheck');
		$model->startTrans();
		$rs = $model->save($map);
		if ($rs === false) {
			$model->rollback();
			$this->error('操作失败');
		}
		$data = $model->create($data);
		$rs1 = $model->add($data);
		if ($rs1 === false) {
			$model->rollback();
			$this->error('操作失败');
		} else {
			$model->commit();
			$this->success('操作成功');
		}
	}

	/**
	 * 审批
	 */
	public function checkCaseInfo() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$_POST['check_time'] = time();
		$model = D('CaseCheck');
		$rs = $model->save($_POST);
		if ($rs === false) {
			$model->rollback();
			$this->error('审批失败');
		} else {
			$id = I('post.id', '', 'int');
			$info = M('CaseCheck')->getById($id);
			$status = I('post.status', '', 'int');
			$cate = I('post.cate', '', 'int');
			if ($cate == 0) {
				//受案登记
				$map = array();
				if ($status === 2) {
					$map['status'] = 0;
				}
				$map['id'] = $info['item_id'];
				$map['check_status'] = $status;
				$result = D('CaseAccept')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
				$map = array();
				$map['id'] = $info['case_id'];
				if ($status == '1') {
					$map['accept_check_status'] = 2;
				} else if ($status == '2') {
					$map['accept_check_status'] = 3;
				}
				$map['update_time'] = time();
				$map['update_user_id'] = $this->my['id'];
				$rs = D('Case')->save($map);
				if ($rs === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
			} else if ($cate == 2) {
				$terminationInfo = M('CaseCognizanceNormal')->getById($info['item_id']);
				//事故认定
				$map = array();
				$map['check_status'] = $status;
				if ($status === 2) {
					$map['is_submit'] = 0;
					$map['submit_time'] = 0;
					$map['submit_user_id'] = 0;
				}
				$map['id'] = $terminationInfo['case_cognizance_id'];
				$result = D('CaseCognizance')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
				$map = array();
				$map['id'] = $terminationInfo['case_cognizance_report_id'];
				$map['check_status'] = $status;
				$result = D('CaseCognizanceReport')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
				//更新主表状态
				$map = array();
				$map['id'] = $terminationInfo['case_id'];
				if ($status == '1') {
					$map['cognizance_check_status'] = 2;
				} else if ($status == '2') {
					$map['cognizance_check_status'] = 3;
				}
				$map['update_time'] = time();
				$map['update_user_id'] = $this->my['id'];
				$rs = D('Case')->save($map);
				if ($rs === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
				//操作日志
				$check = M('CaseCheck')->getById(I('post.id'));
				$content = date('Y-m-d H:i') . "调查报告审批完成";
				$this->saveCaseLog($check['case_id'], $content);
			} else if ($cate == 3) {
				//呈请事故中止
				$map = array();
				$map['check_status'] = $status;
				if ($status === 2) {
					$map['is_submit'] = 0;
				}
				$map['id'] = $info['item_id'];
				$result = D('CaseCognizanceStop')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
			} else if ($cate == 4) {
				$proveInfo = M('CaseCognizanceProve')->getById($info['item_id']);
				//道路交通事故证明
				$map = array();
				$map['check_status'] = $status;
				if ($status === 2) {
					$map['is_submit'] = 0;
					$map['submit_time'] = 0;
					$map['submit_user_id'] = 0;
				}
				$map['id'] = $proveInfo['case_cognizance_id'];
				$result = D('CaseCognizance')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败1');
				}
				$map = array();
				$map['id'] = $proveInfo['case_cognizance_report_id'];
				$map['check_status'] = $status;
				$result = D('CaseCognizanceReport')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败2');
				}
				//更新主表状态
				$cognizanceInfo = M('CaseCognizance')->getById($proveInfo['case_cognizance_id']);
				$map = array();
				$map['id'] = $cognizanceInfo['case_id'];
				if ($status == '1') {
					$map['cognizance_check_status'] = 2;
				} else if ($status == '2') {
					$map['cognizance_check_status'] = 3;
				}
				$map['update_time'] = time();
				$map['update_user_id'] = $this->my['id'];
				$rs = D('Case')->save($map);
				if ($rs === false) {
					$Model->rollback();
					$this->error('操作失败3');
				}
				//操作日志
				$check = M('CaseCheck')->getById(I('post.id'));
				$content = date('Y-m-d H:i') . "调查报告审批完成";
				$this->saveCaseLog($check['case_id'], $content);
			} else if ($cate == 10 || $cate == 11 || $cate == 12 || $cate == 13) {
				$caseCheckupData = M('CaseCheckup')->getById($info['item_id']);
				//检验鉴定
				$map = array();
				//延期
				if ($cate == 10) {
					$map['delay_checked'] = $status;
				}
				//超期
				if ($cate == 11) {
					$map['out_checked'] = $status;
				}
				//超期并延期
				if ($cate == 12) {
					$map['out_checked'] = $status;
					$map['delay_checked'] = $status;
				}
				//重新检验鉴定
				if ($cate == 13) {
					$map2 = array();
					$map2['case_checkup_id'] = $caseCheckupData['id'];
					$map2['status'] = 0;
					$caseCheckAgainData = D('CaseCheckupAgain')->where($map2)->find();
					$newCaseCheckAgainData = array();
					$newCaseCheckAgainData['id'] = $caseCheckAgainData['id'];
					$newCaseCheckAgainData['status'] = $status;
					$newCaseCheckAgainData['update_time'] = time();
					$newCaseCheckAgainData['checked_time'] = time();
					$newCaseCheckAgainData['update_user_id'] = $this->my['id'];
					$result = D('CaseCheckupAgain')->save($newCaseCheckAgainData);
					if ($result === false) {
						$Model->rollback();
						$this->error('操作失败');
					}
				}
				$map['id'] = $caseCheckupData['id'];
				$map['update_time'] = time();
				$result = D('CaseCheckup')->save($map);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败');
				}
				$map1 = array();
				$map1['case_checkup_id'] = $caseCheckupData['id'];
				$map1['status'] = 0;
				$map1['cate'] = (int) $cate - 10;
				$reviewData = D('CaseCheckupReview')->where($map1)->find();
				$newReviewData = array();
				$newReviewData['status'] = $status;
				$newReviewData['id'] = $reviewData['id'];
				$newReviewData['update_time'] = time();
				$newReviewData['checked_time'] = time();
				$result = D('CaseCheckupReview')->save($newReviewData);
				if ($result === false) {
					$Model->rollback();
					$this->error('操作失败' . $newReviewData['id']);
				}
				//设置工作日志
				if ($cate == 10) {
					//在【呈请延期审批通过时间】对【鉴定对象】进行【委托类型】进行延期，约定完成时间为【约定完成时间】
					//查询 检验鉴定信息
					if (empty($caseCheckupData['id'])) {
						$Model->rollback();
						$this->error('操作失败');
					}
					$map = array();
					$map['id'] = $caseCheckupData['id'];
					$checkupData = D('CaseCheckupClientView')->where($map)->find();
					$L = '';
					$R = '';
					if ($checkupData['checkup_org_item_pid'] == 1) {
						$checkObj = "人员：" . $checkupData['case_client_name'];
					}
					if ($checkupData['checkup_org_item_pid'] == 2) {
						$checkObj = "车辆：" . $checkupData['case_client_car_no'];
					}
					if ($checkupData['checkup_org_item_pid'] == 3) {
						$checkObj = "其他：" . $checkupData['target_other'];
					}

					$content = '在' . $L . date('Y-m-d H:i', $newReviewData['checked_time']) . $R . '对' . $L . $checkObj . $R . '进行' . $L . $checkupData['checkup_org_item_name'] . $R . '进行延期，约定完成时间为' . $L . date('Y-m-d H:i', $checkupData['finish_time']) . $R;
					$case_id = $checkupData['case_id'];
					$this->saveCaseLog($case_id, $content);

				}

			}
			$Model->commit();

			$this->success('操作成功', U('pending'));
		}
	}

	/**
	 * 审批详细信息
	 */
	public function approvalList() {
		$this->assign('id', I('get.id'));
		$this->display();
	}

	/**
	 * 审批详细信息表格
	 */
	public function checkTable() {
		$id = I('get.id');
		$map = array();
		$map['id'] = $id;
		$model = D('CaseCheckView');
		$list = $model->where($map)->select();
		$array = array('待审核', '通过', '拒绝');
		foreach ($list as $key => $val) {
			$list[$key]['status'] = $array[$val['status']];
		}
		$this->assign('list', $list);
		$this->display('CaseCheckLeader/approvalList/checkTable');
	}

	/**
	 * 获取审批人员列表
	 * $powerName string 权限名称
	 */
	private function getCheckUserList($powerName = '') {
		$list = array();
		$myBrigade = $this->getMyBrigade();
		// 当前用户所在大队下所有子部门
		$departmentIds = get_all_child($this->allDepartment, $myBrigade['id']);

		// 查询有当前权限的角色
		$map = array();
		$map['name'] = $powerName;
		$map['is_del'] = 0;
		$roleIds = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);

		// 没有角色，人员必然是空
		if (empty($roleIds)) {
			return $list;
		}

		// 通过角色和所在部门查询用户
		$map = array();
		$map['role_id'] = array('in', $roleIds);
		$map['department_id'] = array('in', $departmentIds);
		$map['is_del'] = 0;
		$map['is_locked'] = 0;
		$list = D('UserView')->where($map)->group('User.id')->select();
		return $list;
	}

	/**
	 * 获取拥有两种权限交集的审批人员列表
	 * $powerName string 权限名称
	 */
	private function getCheckUserComplexList($powerName1 = '', $powerName2 = '') {
		$list = array();
		$myBrigade = $this->getMyBrigade();
		// 当前用户所在大队下所有子部门
		$departmentIds = get_all_child($this->allDepartment, $myBrigade['id']);

		// 查询有当前权限的角色
		$map = array();
		$map['name'] = $powerName1;
		$map['is_del'] = 0;
		$roleIds1 = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);
		// 查询有当前权限的角色
		$map = array();
		$map['name'] = $powerName2;
		$map['is_del'] = 0;
		$roleIds2 = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);

		$roleIds = array_intersect($roleIds1, $roleIds2);

		// 没有角色，人员必然是空
		if (empty($roleIds)) {
			return $list;
		}

		// 通过角色和所在部门查询用户
		$map = array();
		$map['role_id'] = array('in', $roleIds);
		$map['department_id'] = array('in', $departmentIds);
		$map['is_del'] = 0;
		$map['is_locked'] = 0;
		$list = D('UserView')->where($map)->group('User.id')->select();
		return $list;
	}

	public function test() {
		$map = array();
		$map['name'] = 'case_checkup_check_delay_0';
		$map['is_del'] = 0;
		$roleIds1 = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);
		// 查询有当前权限的角色
		$map = array();
		$map['name'] = 'case_checkup_check_out_0';
		$map['is_del'] = 0;
		$roleIds2 = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);

		$list = array_intersect($roleIds1, $roleIds2);

		dump($list);

	}
}