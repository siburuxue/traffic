<?php
namespace Admin\Controller;

/**
 * 解析短语模板
 */
class ParsePhraseTemplateController extends CommonController {
	private $case = null;
	private $caseClient = null;
	private $caseCheckup = null;

	public function getContent() {
		$templateId = I('post.templateid', '', 'int');
		if ($templateId === '') {
			$this->error('非法操作');
		}

		$template = M('PhraseTemplate')->getById($templateId);
		if (empty($template)) {
			$this->error('非法操作');
		}

		$content = $template['content'];

		$cateId = $template['cate'];

		// 案件相关信息
		if (in_array($cateId, array(2, 3, 5, 6, 13, 14, 18, 19, 26, 27))) {
			$content = $this->parseCase($content);
		}

		// 当前大队
		if (in_array($cateId, array(8))) {
			$content = $this->parseNowDepartment($content);
		}

		// 当事人
		if (in_array($cateId, array(2, 3, 13, 14, 16, 17, 22))) {
			$content = $this->parseCaseClient($content);
		}

		// 处罚
		if (in_array($cateId, array(14, 23))) {
			$content = $this->parseCasePunish($content);
		}

		// 检验鉴定
		if (in_array($cateId, array(20))) {
			$content = $this->parseCaseCheckup($content);
		}

		// 调解
		if (in_array($cateId, array(26, 27))) {
			$content = $this->parseCaseMediate($content);
		}

		$this->success($content);

	}

	// 获取案件信息
	private function getCase() {
		$case = $this->case;

		if (!isset($case)) {

			$caseId = I('post.caseid', '', 'int');
			if ($caseId === '') {
				$this->error('未传入案件编号');
			}
			$case = M('Case')->getById($caseId);
			if (empty($case)) {
				$this->error('案件不存在');
			}
			$this->case = $case;
		}
		return $case;

	}

	// 获取案件全部有效当事人
	private function getCaseClient() {
		$caseClient = $this->caseClient;

		if (!isset($caseClient)) {
			$caseId = I('post.caseid', '', 'int');
			if ($caseId === '') {
				$this->error('未传入案件编号');
			}

			// 获取全部有效当事人
			$map = array();
			$map['case_id'] = $caseId;
			$map['is_del'] = 0;
			$map['traffic_type'] = array('neq', 8);
			$caseClient = M('CaseClient')->where($map)->select();

			$this->caseClient = empty($caseClient) ? array() : $caseClient;

		}

		return $caseClient;
	}

	// 解析案件相关字段
	private function parseCase($content) {

		$case = $this->getCase();

		$content = str_replace('{事故发生时间}', date('Y-m-d H:i', $case['accident_time']), $content);
		$content = str_replace(array('{事故地点}', '{事故发生地点}'), $case['accident_place'], $content);
		$content = str_replace(array('{死亡人数}', '{事故死亡人数}'), $case['death_num'], $content);
		$content = str_replace(array('{受伤人数}', '{事故受伤人数}'), $case['hurt_num'], $content);

		$propertyLoss = M('DictOption')->where('id=' . $case['property_loss'])->getField('name');
		$content = str_replace('{事故财产损失}', $propertyLoss, $content);

		return $content;
	}

	// 解析当前用户所在大队
	private function parseNowDepartment($content) {
		$userId = session('userid');

		$myBrigade = $this->getMyBrigade();

		$content = str_replace('{操作员所在大队名称}', $myBrigade['name'], $content);

		return $content;
	}

	// 解析案件当事人
	private function parseCaseClient($content) {
		$caseClient = $this->getCaseClient();

		$nameArr = array();
		$zerenArr = array();
		$qingArr = array();
		$cheArr = array();
		foreach ($caseClient as $key => $value) {
			$nameArr[] = $value['name'];
			$zerenArr[] = $value['name'] . '负' . get_custom_config('blame_type.' . $value['blame_type']) . '责任';

			$qing = $value['name'] . '，';
			$qing .= ($value['sex'] == 1 ? '男' : '女') . '，';
			$qing .= get_custom_config('certificate_type.' . $value['id_type']) . ' ' . $value['idno'] . '，';
			if ($value['traffic_type'] == 1) {
				$qing .= '行人';
			} else {
				$qing .= get_custom_config('traffic_type.' . $value['traffic_type']) . '，';
				$qing .= '车牌号' . $value['car_no'] . '车辆';
			}
			$qingArr[] = $qing;

			if ($value['traffic_type'] != 1) {
				$cheArr[] = $value['name'] . '，' . get_custom_config('traffic_type.' . $value['traffic_type']) . '，' . $value['car_no'] . '车辆';
			}
		}

		// 全部当事人姓名
		$str = '';
		if (!empty($nameArr)) {
			$lastName = array_pop($nameArr);
			if (!empty($nameArr)) {
				$str = implode('、', $nameArr) . '和';
			}
			$str .= $lastName;
		}
		$content = str_replace('{全部当事人姓名}', $str, $content);

		// 全部当事人负责
		$str = '';
		if (!empty($zerenArr)) {
			$str = implode('、', $zerenArr);
		}
		$content = str_replace('{全部当事人责任}', $str, $content);

		// 全部当事人情况
		$str = '';
		if (!empty($qingArr)) {
			$str = implode("；", $qingArr);
		}
		$content = str_replace('{全部当事人情况}', $str, $content);

		// 全部当事人车辆情况
		$str = '';
		if (!empty($cheArr)) {
			$str = implode("；", $cheArr);
		}
		$content = str_replace('{全部当事人车辆情况}', $str, $content);

		return $content;
	}

	// 解析案件处罚
	private function parseCasePunish($content) {
		$caseClient = $this->getCaseClient();

		$str = '';
		$arr = array();

		foreach ($caseClient as $key => $value) {
			$flag=0;
			$map = array();
			$map['case_client_id'] = $value['id'];
//			$punish = M('CasePunish')->where($map)->find();
			$punish=$value;
			$chufaArr = array();
			if (!empty($punish)) {
				if ($punish['punish_is_warning']) {
					$chufaArr[] = '警告';
					$flag++;
				}
				if ($punish['punish_is_fine']) {
					$chufaArr[] = '罚款' . $punish['punish_money'] . '元、扣' . $punish['punish_score'] . '分';
					$flag++;
				}
				if ($punish['punish_is_seize']) {
					$chufaArr[] = '暂扣' . $punish['punish_seize_time'];
					$flag++;
				}
				if ($punish['punish_is_revoke']) {
					$chufaArr[] = '吊销' . get_custom_config('revoke_years.' . $punish['punish_revoke_time']);
					$flag++;
				}
				if ($punish['punish_is_detain']) {
					$chufaArr[] = '拘留' . $punish['punish_detain_time'] . '天';
					$flag++;
				}
				if($flag){
					$arr[] = '拟对' . $value['name'] . '进行' . implode('，', $chufaArr);
				}
			}

		}
		$str = implode('；', $arr);

		$content = str_replace('{全部当事人拟进行的行政处罚}', $str, $content);

		return $content;
	}

	// 获取全部完成的检验鉴定
	private function getCaseCheckup() {
		$caseCheckup = $this->caseCheckup;

		if (!isset($caseCheckup)) {
			$caseId = I('post.caseid', '', 'int');
			if ($caseId === '') {
				$this->error('未传入案件编号');
			}

			$map = array();
			$map['case_id'] = $caseId;
			$map['is_over'] = 1;
			$map['is_del'] = 0;
			$map['is_cancel'] = 0;
			$caseCheckup = D('CaseCheckupView')->where($map)->group('CaseCheckup.id')->select();

			$this->caseCheckup = empty($caseCheckup) ? array() : $caseCheckup;
		}
		return $caseCheckup;
	}
	// 解析检验鉴定
	private function parseCaseCheckup($content) {
		$caseCheckup = $this->getCaseCheckup();

		$str = '';

		$arr = array();
		foreach ($caseCheckup as $key => $value) {
			$item = $value['checkup_org_name'] . '对';
			$item .= get_custom_config('checkup_org_obj.' . $value['checkup_org_item_pid']);

			if ($value['checkup_org_item_pid'] == 1) {

				$map = array();
				$map['id'] = $value['target_case_client_id'];
				$item .= M('CaseClient')->where($map)->getField('name');

			} elseif ($value['checkup_org_item_pid'] == 2) {
				$map = array();
				$map['id'] = $value['target_case_client_id'];
				$item .= M('CaseClient')->where($map)->getField('car_no');
			} else {
				$item .= $value['target_other'];
			}
			$item .= '实施' . $value['checkup_org_item_id_name'] . '，鉴定结果为' . $value['case_checkup_report_result'];
			$arr[] = $item;
		}

		$str = implode('；', $arr);

		$content = str_replace('{全部检验鉴定结果}', $str, $content);
		return $content;
	}

	private function parseCaseMediate($content) {
		$type = I('post.type', '', 'int');
		if ($type == 1) {
			$caseClientId = I('post.caseclientid', '', 'int');
			if ($caseClientId === '') {
				$this->error('未传入当事人编号');
			}
			$clients = array($caseClientId);
		} else {
			$caseMediateAcceptId = I('post.casemediateacceptid', '', 'int');
			if ($caseMediateAcceptId === '') {
				$this->error('未传入调解编号');
			}

			$map = array();
			$map['case_mediate_accept_id'] = $caseMediateAcceptId;
			$clients = M('CaseMediateApply')->where($map)->getField('case_client_id', true);
		}
		// 全部有效当事人
		$map = array();
		$map['id'] = array('in', $clients);
		$map['is_del'] = 0;
		$map['traffic_type'] = array('neq', 8);
		$caseClient = M('CaseClient')->where($map)->select();

		$qingArr = array();

		foreach ($caseClient as $key => $value) {

			$qing = $value['name'];

			if ($value['traffic_type'] == 1) {
				$qing .= '步行';
			} else {
				$qing .= get_custom_config('traffic_type.' . $value['traffic_type']);
				$qing .= '车牌号' . $value['car_no'] . '车辆';
			}
			$qingArr[] = $qing;

		}

		// 全部当事人情况
		$str = '';
		if (!empty($qingArr)) {
			$str = implode("；", $qingArr);
		}
		$content = str_replace(array('{参与调解当事人情况}', '{不调解当事人情况}'), $str, $content);

		return $content;
	}

}