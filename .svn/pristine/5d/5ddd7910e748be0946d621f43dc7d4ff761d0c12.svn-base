<?php
namespace Admin\Service;

class CaseInfoService extends CommonService {

	public function getCaseList($condition) {

		// 列表信息
		$Model = D('CaseView');
		$count = $Model->where($condition)->count('distinct CaseInfo.id');
		$page = new Page($count, 15);
		$list = $Model->where($condition)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseInfo.id')->select();

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
		$data['list'] = $list;

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$data['page'] = $pageInfo;

		return $data;
	}

	public function getCaseInfo($caseId) {

	}

}
?>