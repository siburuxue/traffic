<?php
namespace Admin\Controller;

/**
 * 案件
 */
class CaseInfoController extends CommonController {
	/**
	 * 案件详情 办理页面
	 */
	public function detail() {
		// 案件编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		if ($info['is_del'] == 1) {
			$this->error('案件已作废');
		}
		$this->assign('info', $info);

		// 事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		// 事故初查
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		// 财产损失
		$this->assign('propertyLoss', get_dict('property_loss'));
        $timeRs = $this->calculateCaseTimeLimit($id);
        $this->assign('timeRs',$timeRs);
		// 渲染
		$this->display();
	}

	/**
	 * 获取案件基本信息
	 */
	public function getCaseInfo() {
		/*******************************************/
		$data = array();

		/*******************************************/
		// 案件信息
		$caseId = I('post.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作');
		}
		$map = array();
		$map['id'] = $caseId;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在');
		}
		$case['accident_time'] = date('Y-m-d H:i', $case['accident_time']);
		$case['first_cognizance'] = empty($case['first_cognizance']) ? array() : explode(',', $case['first_cognizance']);

		$data['case'] = $case;
		/*******************************************/
		// 案件关联报警信息
		$map = array();
		$map['case_id'] = $caseId;
		$caseAlarm = D('CaseAlarmView')->where($map)->field('alarm_id,alarm_alarm_name,alarm_alarm_time')->order('create_time desc,alarm_id desc')->limit(2)->group('CaseAlarm.alarm_id')->select();
		foreach ($caseAlarm as $key => &$value) {
			$value['alarm_alarm_time'] = date('Ymd', $value['alarm_alarm_time']);
		}
		unset($value);

		$data['alarm'] = $caseAlarm;
		/*******************************************/
		// 受案登记记录
		$map = array();
		$map['case_id'] = $caseId;
		$caseAccept = M('CaseAccept')->where($map)->field('id,reason')->find();
		if (empty($caseAccept)) {
			$caseAccept = array();
		} else {
			$caseAccept['reason'] = left_str($caseAccept['reason'], 20);
		}
		$data['accept'] = $caseAccept;
		/*******************************************/
		// 现场勘察
		$map = array();
		$map['case_id'] = $caseId;
		$caseSurvey = M('CaseSurvey')->where($map)->field('id')->find();

		if (empty($caseSurvey)) {
			$data['survey'] = array();
			$data['surveyStatus'] = array();
		} else {
			$caseSurveyStatus = array();
			$map = array();
			$map['case_id'] = $caseId;
			$map['ext_ida'] = $caseSurvey['id'];

			$map['cate'] = 3;
			$caseSurveyStatus[] = M('CasePhoto')->where($map)->count();

			$map['cate'] = array('in', array('5', '6', '7', '8'));
			$caseSurveyStatus[] = M('CasePhoto')->where($map)->count();

			$map['cate'] = 9;
			$caseSurveyStatus[] = M('CasePhoto')->where($map)->count();

			$map['cate'] = 10;
			$caseSurveyStatus[] = M('CasePhoto')->where($map)->count();

			$data['survey'] = $caseSurvey;
			$data['surveyStatus'] = $caseSurveyStatus;
		}
		/*******************************************/
		// 当事人
		$map = array();
		$map['case_id'] = $caseId;
		$caseClient = M('CaseClient')->where($map)->field('id,name,detain_time,is_escape')->select();

		foreach ($caseClient as $key => &$value) {
			$value['detain_car_status'] = $value['detain_time'] == 0 ? '未扣车' : '已扣车';

			$map = array();
			$map['case_id'] = $caseId;
			$map['case_client_id'] = $value['id'];
			$map['name_id'] = 1;
			$map['status'] = 0;
			$unique = M('CaseClientDetain')->where($map)->find();
			$value['detain_licence_status'] = empty($unique) ? '未扣留驾驶证' : '已扣留驾驶证';

			$value['escape_status'] = $value['is_escape'] == 1 ? '逃逸' : '未逃逸';
		}
		unset($value);

		$data['client'] = $caseClient;
		/*******************************************/
		// 询问笔录
		$caseRecordData = array();
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		// 当事人
		$map['user_type'] = 0;
		$caseRecord = M('CaseRecord')->where($map)->field('id,name,record_type,record_count,user_type')->order('create_time desc')->limit(2)->select();

		foreach ($caseRecord as $key => $value) {
			switch ($value['record_type']) {
			case 0:
				$value['record_type_name'] = '询问笔录';
				break;
			case 1:
				$value['record_type_name'] = '讯问笔录 ';
				break;
			case 2:
				$value['record_type_name'] = '谈话记录';
				break;
			}
			$value['user_type_name'] = '当事人';
			$caseRecordData[] = $value;
		}
		// 证人
		$map['user_type'] = 2;
		$caseRecord = M('CaseRecord')->where($map)->field('id,name,record_type,record_count,user_type')->order('create_time desc')->limit(1)->select();

		foreach ($caseRecord as $key => $value) {
			switch ($value['record_type']) {
			case 0:
				$value['record_type_name'] = '询问笔录';
				break;
			case 1:
				$value['record_type_name'] = '讯问笔录 ';
				break;
			case 2:
				$value['record_type_name'] = '谈话记录';
				break;
			}
			$value['user_type_name'] = '证人';
			$caseRecordData[] = $value;
		}
		$data['record'] = $caseRecordData;
		/*******************************************/
		// 检验鉴定
		$caseCheckupData = array();

		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$map['is_cancel'] = 0;
		$caseCheckup = M('CaseCheckup')->where($map)->field('id,checkup_org_item_id,pid')->order('create_time desc')->select();

		foreach ($caseCheckup as $key => $value) {
			if (count($caseCheckupData) >= 3) {
				break;
			}
			if ($value['pid'] == 0) {
				$child = list_search($caseCheckup, 'pid=' . $value['id']);
				$value['is_red'] = empty($child) ? 0 : 1;

				$map = array();
				$map['id'] = $value['checkup_org_item_id'];
				$value['item_name'] = M('CheckupOrgItem')->where($map)->getField('name');
				$caseCheckupData[] = $value;
			}
		}

		$data['checkup'] = $caseCheckupData;
		/*******************************************/
		// 处罚
		$map = array();
		$map['case_id'] = $caseId;
		$casePunish = D('CasePunishView')->where($map)->group('CasePunish.id')->limit(3)->select();

		foreach ($casePunish as $key => &$value) {
			$punishArr = array();
			if ($value['punish_is_warning']) {
				$punishArr[] = '警告';
			}
			if ($value['punish_is_fine']) {
				$punishArr[] = '罚款' . $value['punish_fine_money'] . '元，扣' . $value['punish_fine_score'] . '分';
			}
			if ($value['punish_is_seize']) {
				$punishArr[] = '暂扣' . $value['punish_seize_date'];
			}
			if ($value['punish_is_revoke']) {
				$punishArr[] = '吊销' . get_custom_config('revoke_years.' . $value['punish_revoke_date']);
			}
			if ($value['punish_is_detain']) {
				$punishArr[] = '拘留' . $value['punish_detain_date'] . '天';
			}
			if ($value['criminal_is_detain']) {
				$punishArr[] = '刑事拘留';
			}
			if ($value['criminal_is_arrest']) {
				$punishArr[] = '逮捕';
			}
			if ($value['criminal_is_remand']) {
				$punishArr[] = '取保候审';
			}
			if ($value['criminal_is_sue']) {
				$punishArr[] = '移送起诉';
			}
			$value['notice'] = empty($punishArr) ? '无处罚' : implode('；', $punishArr);

		}
		unset($value);

		$data['punish'] = $casePunish;
		/*******************************************/
		// 调解
		$map = array();
		$map['case_id'] = $caseId;
		$caseMediate = M('CaseMediateApply')->where($map)->group('case_client_id')->getField('case_client_id', true);

		$data['mediate'] = empty($caseMediate) ? array() : $caseMediate;

		/*******************************************/
		// 事故认定
		//是否有有效的事故认定
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_back'] = 0;
		$caseCognizance = M('CaseCognizance')->where($map)->find();

		//事故认定是无法认定还是一般认定 0：一般认定
		$map = array();
		$map['case_id'] = $caseId;
		$map['traffic_type'] = array('neq', 8);
		$map['blame_type'] = 6;
		$map['is_del'] = 0;
		$caseClientTypeList = M('CaseClient')->where($map)->find();

		// 是否有逃逸
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_escape'] = 1;
		$map['is_del'] = 0;
		$caseClientEscape = M('CaseClient')->where($map)->find();

		$map = array();
		$map['case_id'] = $caseId;
		$caseClientCoescape = M('CaseCoescape')->where($map)->find();

		//历史简易事故认定列表
		$map = array();
		$map['case_id'] = $caseId;
		$map['cognizance_type'] = 0;
		$simpleList = M('CaseCognizance')->where($map)->order('id desc')->select();

		//调查报告列表
		$map = array();
		$map['case_id'] = $caseId;
		$map['cognizance_type'] = 1;
		$map['is_last'] = 1;
		$normalList = D('CaseCognizanceReportView')->where($map)->order('id desc')->select();

		//按照时间倒序
		$caseCognizanceList = array_merge($simpleList, $normalList);
		$caseCognizanceList = list_sort_by($caseCognizanceList, 'update_time', 'desc');

		$data['cognizance'] = array(
			'is_exist' => empty($caseCognizance) ? 0 : 1,
			'is_sure' => empty($caseClientTypeList) ? 1 : 0,
			'is_escape' => empty($caseClientEscape) && empty($caseClientCoescape) ? 0 : 1,
			'list' => $caseCognizanceList,
		);
		/*******************************************/
		// 领导指示
		$map = array();
		$map['case_id'] = $caseId;
		$caseDirective = D('CaseDirectiveView')->where($map)->order('create_time desc')->group('CaseDirective.id')->find();

		$data['directive'] = empty($caseDirective) ? '无' : $caseDirective['content'];
		/*******************************************/
		// 集体研究
		$map = array();
		$map['case_id'] = $caseId;
		$caseDiscuss = D('CaseDiscussView')->where($map)->order('create_time desc')->group('CaseDiscuss.id')->find();
		if (empty($caseDiscuss)) {
			$data['discuss'] = '无';
		} else {
			$data['discuss'] = date('Y-m-d H:i', $caseDiscuss['discuss_time']) . '集体研究';
		}
		/*******************************************/
		// 案件状态
		$caseStatus = new \Lib\CaseStatus($caseId);
		$data['statusText'] = $caseStatus->getStatus();
		/*******************************************/
		// 案件复核信息
		$map = array();
		$map['case_id'] = $caseId;
		$caseReview = M('CaseReview')->where($map)->order('create_time desc')->find();
		$data['caseReview'] = empty($caseReview) ? array() : $caseReview;
		/*******************************************/
		$this->success($data);
	}

}