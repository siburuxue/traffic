<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 领导案件移交
 */
class CaseTransferController extends CommonController {

	/**
	 * 领导案件移交 案件
	 */
	public function pending() {
		$this->assign('accidentType', get_custom_config('accident_type'));

		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		//查询当前操作人员是否可以将案件移交到别的大队
		if (true === is_power($this->myPower, 'case_transfer')) {
			// 部门
			$department = $this->allDepartment;
			$department = list_to_tree($department);
			$department = tree_to_array($department);
			$this->assign('department', $department);

		} else {
			$this->assign('myBrigade', $myBrigade);
		}

		$this->display();
	}

	/**
	 * 领导案件移交 案件 表格
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

		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}

		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['is_del'] = 0;
		// 只能查看当前办案人的案件
		$map['case_handle_user_id'] = $this->my['id'];
		// 只能查看当前办案人所在大队的案件
		$map['department_id'] = $myBrigade['id'];
		$map['cate'] = 0;

		$condition = get_condition();
		isset($condition['case_client_true_name']) && $map['case_client_true_name'] = $condition['case_client_true_name'];
		isset($condition['case_client_idno']) && $map['case_client_idno'] = $condition['case_client_idno'];

		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}

		isset($condition['accident_type']) && $map['accident_type'] = $condition['accident_type'];
		isset($condition['case_client_car_no']) && $map['case_client_car_no'] = $condition['case_client_car_no'];
		// isset($condition['qr_code']) && $map['qr_code'] = $condition['qr_code'];

		// 列表信息
		$Model = D('CaseView');
		$count = $Model->where($map)->count('distinct CaseInfo.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('CaseInfo.id')->select();
		// 查询扩展信息
		foreach ($list as $key => &$value) {
			// 事故类型
			$value['accident_type_name'] = get_custom_config('accident_type.' . $value['accident_type']);

			// 现场形态
			$value['case_survey_scene_state_name'] = get_custom_config('scene_state.' . $value['case_survey_scene_state']);

			// 当事人
			$map = array();
			$map['case_id'] = $value['id'];
			$caseClient = M('CaseClient')->where($map)->select();

			// 当事人姓名数组
			$caseClientNameArr = array();
			// 逃逸当事人数量
			$escapeNum = 0;
			// 抓获逃逸数量
			$catchNum = 0;

			foreach ($caseClient as $client) {
				if (isset($condition['case_client_true_name']) && $condition['case_client_true_name'] == $client['name']) {
					array_unshift($caseClientNameArr, $client['name']);
				} else {
					array_push($caseClientNameArr, $client['name']);
				}
				$escapeNum += $client['is_escape'];
				if ($client['is_escape'] == 1 && $client['escape_catch_man_time'] != 0) {
					$catchNum++;
				}
			}
			$value['case_client_names'] = empty($caseClientNameArr) ? '' : implode(',', $caseClientNameArr);
			$value['is_escape'] = $escapeNum > 0 ? 1 : 0;
			$value['is_catch'] = $escapeNum === $catchNum ? 1 : 0;

			// 案件状态
			$caseStatus = new \Lib\CaseStatus($value['id']);
			$value['case_status'] = $caseStatus->getStatus();

		}
		unset($value);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseTransfer/pending/table');
	}

	/**
	 * AJAX案件移交
	 */

	public function setTranser() {
		$ids = I('post.ids', '', 'strip_tags');
		$department_id = I('post.department_id', '', 'strip_tags');
		$user_id = I('post.user_id', '', 'strip_tags');
		//$target_ids = rtrim($ids, ','); //去除两边的
		//$target_ids = substr($ids,0,strlen($str)-1);
		$target_ids = substr($ids, 0, -1);
		$target_ids_array = explode(',', $target_ids);
		$Model = D('CaseHandle');
		$Model->startTrans();
		$caseHandleData = $Model->create();
		if (false === $caseHandleData) {
			$Model->rollback();
			$this->error($Model->getError());
		}
		//取消模拟案件值（模拟案件值用于create方法自动生成数据生成数据 避免自动验证提示 为空）
		unset($caseHandleData['case_id']);
		if (!$target_ids_array) {
			$Model->rollback();
			$this->error('转移失败');
		}
		foreach ($target_ids_array as $key => $value) {
			//更新主表case 数据
			$updateCaseData['id'] = $target_ids_array[$key];
			$updateCaseData['department_id'] = $department_id;
			$updateCaseData['update_time'] = time();
			$result = D('Case')->save($updateCaseData);
			if (!$result) {
				$Model->rollback();
				$this->error('转移失败');
			}
			//查询case_handle旧数据
			$map = array();
			$map['case_id'] = $target_ids_array[$key];
			$map['is_now'] = 1;
			$oldCaseHandleData = $Model->where($map)->find();
			if (!$oldCaseHandleData || $oldCaseHandleData['case_id'] != $map['case_id']) {
				$Model->rollback();
				$this->error('转移失败');
			}
			//更新case_handle数据
			$updateCaseHandleData['id'] = $oldCaseHandleData['id'];
			$updateCaseHandleData['is_now'] = 0;
			$updateCaseHandleData['end_time'] = time();
			$updateCaseHandleData['update_time'] = time();
			$result = $Model->save($updateCaseHandleData);
			if (!$result) {
				$Model->rollback();
				$this->error('转移失败');
			}
			//插入case_handle新数据
			$data[$key] = $caseHandleData;
			$data[$key]['case_id'] = $target_ids_array[$key];
			$data[$key]['user_id'] = $user_id;
			$result = $Model->add($data[$key]);
			if (!$result) {
				$Model->rollback();
				$this->error('转移失败');
			}
		}
		$Model->commit();
		$this->success('转移成功');

	}

	/**
	 * ajax读取该大队下所有可选值班人员
	 */
	public function getDepartmentUsers() {
		$pid = I('post.d_id', '', 'int');

		// 部门
		$department = $this->allDepartment;
		$this->assign('department', $department);

		//该部门以及该部门下所有子部门的主键id $allChild
		$allChild = get_all_child($department, $pid);

		// 部门（大队）下所属人员
		$map = array();
		$map['is_del'] = 0;
		$map['department_id'] = array('in', $allChild);
		//$map['id'] = array('neq', $this->my['id']);
		$allUsers = M('User')->field('id,true_name')->where($map)->select();
		foreach ($allUsers as $key => $value) {
			if ($allUsers[$key]['id'] == $this->my['id']) {
				unset($allUsers[$key]);
			}
		}
		$this->success(array_values($allUsers));
	}

}