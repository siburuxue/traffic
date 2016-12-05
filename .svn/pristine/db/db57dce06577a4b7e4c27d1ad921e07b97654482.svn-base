<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 领导重点关注 案件
 */
class CaseAttentionController extends CommonController {

	/**
	 * 领导重点关注 案件
	 */
	public function pending() {
		$this->assign('accidentType', get_custom_config('accident_type'));

		$this->display();
	}

	/**
	 * 领导重点关注 案件 表格
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
		//查询该用户已经关注的案件
		$map = array();
		$map['user_id'] = $this->my['id'];
		$map['is_del'] = 0;
		//非快赔案件
		$map['cate'] = 0;
		$allAttentionCaseId = D('CaseAttention')->where($map)->getField('case_id', true);
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['is_del'] = 0;
		//非快赔案件
		$map['cate'] = 0;
		// 只能查看当前办案人的案件
		$map['case_handle_user_id'] = $this->my['id'];
		// 只能查看当前办案人所在大队的案件
		$map['department_id'] = $myBrigade['id'];
		if ($allAttentionCaseId) {
			$map['id'] = array('notin', $allAttentionCaseId);

		}

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
		$this->display('CaseAttention/pending/table');
	}

	/**
	 * 已关注案件
	 */
	public function completed() {
		$this->assign('accidentType', get_custom_config('accident_type'));
		$this->display();
	}

	/**
	 * 已关注案件 表格
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
		//查询该用户已经关注的案件
		$map = array();
		$map['user_id'] = $this->my['id'];
		$map['is_del'] = 0;
		//非快赔案件
		$map['cate'] = 0;
		$allAttentionCase = D('CaseAttention')->where($map)->select();
		//获取案件ID
		$allAttentionCaseId = array();
		foreach ($allAttentionCase as $key1 => $value1) {
			$allAttentionCaseId[] = $allAttentionCase[$key1]['case_id'];
		}
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['is_del'] = 0;
		//非快赔案件
		$map['cate'] = 0;
		// 只能查看当前办案人的案件
		$map['case_handle_user_id'] = $this->my['id'];
		// 只能查看当前办案人所在大队的案件
		$map['department_id'] = $myBrigade['id'];
		if ($allAttentionCaseId) {
			$map['id'] = array('in', $allAttentionCaseId);
		}

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
			//添加关注信息
			foreach ($allAttentionCase as $key2 => $value2) {
				if ($allAttentionCase[$key2]['case_id'] == $list[$key]['id']) {
					$list[$key]['attention'] = $allAttentionCase[$key2];
				}
			}
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
		if (!$allAttentionCaseId) {
			$list = "";
		}
		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->display('CaseAttention/completed/table');
	}

	/**
	 * AJAX添加关注案件
	 */

	public function setAttention() {
		$ids = I('post.ids', '', 'strip_tags');
		//$target_ids = rtrim($ids, ','); //去除两边的
		//$target_ids = substr($ids,0,strlen($str)-1);
		$target_ids = substr($ids, 0, -1);
		$target_ids_array = explode(',', $target_ids);
		$Model = D('CaseAttention');
		$Model->startTrans();
		$caseAttentionData = $Model->create();
		if (false === $caseAttentionData) {
			$Model->rollback();
			$this->error($Model->getError());
		}
		if (!$target_ids_array) {
			$Model->rollback();
			$this->error('关注失败');
		}
		foreach ($target_ids_array as $key => $value) {
			$data[$key] = $caseAttentionData;
			$data[$key]['case_id'] = $target_ids_array[$key];
			$result = $Model->add($data[$key]);
			if (!$result) {
				$Model->rollback();
				$this->error('关注失败');
			}
		}
		$Model->commit();
		$this->success('关注成功');

	}

	/**
	 * AJAX取消关注案件
	 */

	public function cancelAttention() {
		$ids = I('post.ids', '', 'strip_tags');
		//$target_ids = rtrim($ids, ','); //去除两边的
		//$target_ids = substr($ids,0,strlen($str)-1);
		$target_ids = substr($ids, 0, -1);
		$target_ids_array = explode(',', $target_ids);
		$Model = D('CaseAttention');
		$Model->startTrans();
		//$caseAttentionData = $Model->create();
		if (!$target_ids_array) {
			$Model->rollback();
			$this->error('取消关注失败');
		}
		foreach ($target_ids_array as $key => $value) {
			$data['id'] = $target_ids_array[$key];
			// $data['is_del'] = 1;
			// $data['update_time'] = time();
			$result = $Model->delete($data['id']);
			if (!$result) {
				$Model->rollback();
				$this->error('取消关注失败');
			}
		}
		$Model->commit();
		$this->success('取消关注成功');

	}

}