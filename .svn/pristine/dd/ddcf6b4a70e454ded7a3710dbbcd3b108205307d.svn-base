<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 办案人 案件
 */
class CaseHandleController extends CommonController {

	/**
	 * 待办工作
	 */
	public function pending() {
		$this->assign('accidentType', get_custom_config('accident_type'));

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
			$time_limit = 3;
			break;
		case 'case_id':
			$orderby = 'CaseInfo.code';
			$time_limit = 3;
			break;
		case 'time_limit':
			$orderby = '1=1';
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
		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['is_del'] = 0;
		//未归档
		$map['catalog_status'] = 0;
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
		$list = $this->calculateTimeLimit($list, 'id', $time_limit);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseHandle/pending/table');
	}

	/**
	 * 已完成工作
	 */
	public function completed() {
		$this->assign('accidentType', get_custom_config('accident_type'));

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

		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['is_del'] = 0;
		//已归档
		$map['catalog_status'] = 1;

		// 只能查看当前办案人的案件
		$map['case_handle_user_id'] = $this->my['id'];
		// 只能查看当前办案人所在大队的案件
		$map['department_id'] = $myBrigade['id'];

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
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseInfo.id')->select();

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
		$list = $this->calculateTimeLimit($list, 'id', $time_limit);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		$this->display('CaseHandle/completed/table');
	}

	/**
	 * 新增案件
	 */
	public function add() {
		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		// 事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		// 事故初查
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		// 财产损失
		$this->assign('propertyLoss', get_dict('property_loss'));
		// 渲染
		$this->display();
	}

	/**
	 * 执行新增案件
	 */
	public function insert() {
		// 验证当前用户是否有权限创建案件

		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		if (empty($myBrigade['area_code'])) {
			$this->error('当前用户所在大队缺失行政区划代码');
		}

		// 实例化模型
		$Model = D('Case');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 案件序号
		$data['num'] = create_case_num($myBrigade, $data['create_time']);
		// 案件编号
		$data['code'] = create_case_code($myBrigade, $data['num'], $data['create_time']);
		// 普通案件处理
		$data['cate'] = 0;
		// 事故发生时间
		$data['accident_time'] = strtotime($data['accident_time']);
		// 死亡人数
		$data['death_num'] = empty($data['death_num']) ? 0 : $data['death_num'];
		// 受伤人数
		$data['hurt_num'] = empty($data['hurt_num']) ? 0 : $data['hurt_num'];
		// 将案件绑定到当前用户所在部门
		$data['department_id'] = $myBrigade['id'];
		// 事故初查
		$data['first_cognizance'] = '';
		$firstCognizance = I('post.first_cognizance');
		if (is_array($firstCognizance) && count($firstCognizance) > 0) {
			$map = array();
			$map['id'] = array('in', $firstCognizance);
			$firstCognizanceIds = M('DictOption')->where($map)->getField('id', true);
			if (count($firstCognizance) !== count($firstCognizanceIds)) {
				$this->error('请正确选择事故初查项');
			}
			$data['first_cognizance'] = implode(',', $firstCognizanceIds);
		} else {
			$this->error('事故初查至少选择一项');
		}

		// 开启事务
		$Model->startTrans();

		// 执行新增
		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 创建办案人
		$data = array();
		$data['case_id'] = $id;
		$data['user_id'] = $this->my['id'];

		$caseHandleModel = D('CaseHandle');
		$data = $caseHandleModel->create($data);
		if (false === $data) {
			$Model->rollback();
			$this->error($caseHandleModel->getError());
		}

		$res = $caseHandleModel->add($data);
		if (!$res) {
			$Model->rollback();
			$this->error('办案人数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('新增成功', U('detail?id=' . $id));
	}

	/**
	 * 编辑案件
	 */
	public function edit() {

		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		if ($info['is_del'] == 1) {
			$this->error('案件已作废');
		}
		if ($info['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		// 案件初查
		$info['first_cognizance'] = explode(',', $info['first_cognizance']);

		$this->assign('info', $info);

		// 事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		// 事故初查
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		// 财产损失
		$this->assign('propertyLoss', get_dict('property_loss'));
		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {

		// 实例化模型
		$Model = D('Case');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 案件编号
		$id = $data['id'];

		// 案件权限
		$case = D('CaseView')->getById($id);
		if (empty($case)) {
			$this->error('案件不存在');
		}
		if ($case['is_del'] == 1) {
			$this->error('案件已作废');
		}
		if ($case['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		// 事故发生时间
		$data['accident_time'] = strtotime($data['accident_time']);
		// 死亡人数
		$data['death_num'] = empty($data['death_num']) ? 0 : $data['death_num'];
		// 受伤人数
		$data['hurt_num'] = empty($data['hurt_num']) ? 0 : $data['hurt_num'];
		// 事故初查
		$data['first_cognizance'] = '';
		$firstCognizance = I('post.first_cognizance');
		if (is_array($firstCognizance) && count($firstCognizance) > 0) {
			$map = array();
			$map['id'] = array('in', $firstCognizance);
			$firstCognizanceIds = M('DictOption')->where($map)->getField('id', true);
			if (count($firstCognizance) !== count($firstCognizanceIds)) {
				$this->error('请正确选择事故初查项');
			}
			$data['first_cognizance'] = implode(',', $firstCognizanceIds);
		} else {
			$this->error('事故初查至少选择一项');
		}

		// 开启事务
		$Model->startTrans();

		// 保存
		$result = $Model->save($data);

		// 数据保存失败
		if (!$result) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('更新成功');
	}

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
		if ($info['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}
		$this->assign('info', $info);

		// 事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		// 事故初查
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		// 财产损失
		$this->assign('propertyLoss', get_dict('property_loss'));
		$timeRs = $this->calculateCaseTimeLimit($id);
		$this->assign('timeRs', $timeRs);
		// 渲染
		$this->display();
	}

	/**
	 * 受案登记信息
	 */
	public function getAcceptInfo() {
		// 案件编号
		$caseId = I('post.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['case_id'] = $caseId;
		$accept = M('CaseAccept')->where($map)->find();

		if (empty($accept)) {
			$this->error('', U('CaseAcceptHandle/add?case_id=' . $caseId));
		} else {
			$this->success($accept, U('CaseAcceptHandle/edit?case_id=' . $caseId . '&id=' . $accept['id']));
		}
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

	// 检查是否允许进行一般事故认定
	public function checkIsCanNormalCognizance() {
		$isSure = I('get.sure', '', 'int');
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '' || $isSure === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$map = array();
		$map['id'] = $caseId;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在');
		}
		if ($case['is_del'] == 1) {
			$this->error('案件已作废');
		}

		// 判断是否有受案登记
		$map = array();
		$map['case_id'] = $caseId;
		$caseAccept = M('CaseAccept')->where($map)->find();
		if (empty($caseAccept)) {
			$this->error('受案登记不存在');
		}
		// 判断受案登记是否已经通过审批（有效？）
		if ($caseAccept['check_status'] != 1) {
			$this->error('受案登记尚未审批通过');
		}

		// 案件当事人是否有死亡
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$map['hurt_type'] = 1;
		$map['traffic_type'] = array('neq', 8);
		$caseClientDeath = M('CaseClient')->where($map)->find();
		// 如果当事人有死亡的，案件必须是死亡事故
		if (!empty($caseClientDeath) && $case['accident_type'] != 3) {
			$this->error('当事人损害程度同案件基本信息中的案件类别不一致');
		}
		if ($case['accident_type'] == 3 && empty($caseClientDeath)) {
			$this->error('当事人损害程度同案件基本信息中的案件类别不一致');

		}

		// 案件当事人
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$map['traffic_type'] = array('neq', 8);
		$caseClient = M('CaseClient')->where($map)->select();

		// 证件类型有效值
		$idType = array();
		$certificateType = get_custom_config('certificate_type');
		foreach ($certificateType as $key => $value) {
			$idType[] = $key;
		}
		$tType = array();
		$trafficType = get_custom_config('traffic_type');
		foreach ($trafficType as $key => $value) {
			$tType[] = $key;
		}
		$bType = array();
		$blameType = get_custom_config('blame_type');
		foreach ($blameType as $key => $value) {
			$bType[] = $key;
		}
		$hType = array();
		$hurtType = get_custom_config('hurt_type');
		foreach ($hurtType as $key => $value) {
			$hType[] = $key;
		}
		$gType = array();
		$gradeType = get_custom_config('grade_type');
		foreach ($gradeType as $key => $value) {
			$gType[] = $key;
		}

		// 判断当事人字段
		foreach ($caseClient as $key => $value) {
			if (trim($value['name']) == '') {
				$this->error('存在当事人未填写姓名');
			}
			if (false === in_array($value['sex'], array('1', '0'))) {
				$this->error('存在当事人未选择性别');
			}
			if (false === in_array($value['id_type'], $idType)) {
				$this->error('存在当事人未选择证件类型');
			}
			if (trim($value['idno']) == '') {
				$this->error('存在当事人证件号码未填写');
			}
			if ($value['id_type'] == 1 && false === is_id_card($value['idno'])) {
				$this->error('存在当事人身份证号码输入不正确');
			}
			if (false === in_array($value['traffic_type'], $tType)) {
				$this->error('存在当事人未选择交通方式');
			}
			if (false === in_array($value['blame_type'], $bType)) {
				$this->error('存在当事人未选择事故责任');
			}
			if (false === in_array($value['hurt_type'], $hType)) {
				$this->error('存在当事人未选择伤害程度');
			}
			if (false === in_array($value['is_escape'], array('1', '0'))) {
				$this->error('存在当事人未选择是否逃逸');
			}
			if (in_array($value['traffic_type'], array('3', '4', '5', '6', '7'))) {
				if (false === in_array($value['grade_type'], $gType)) {
					$this->error('存在当事人未选择号牌种类');
				}
				if ($value['grade_type'] != '41' && trim($value['car_no']) == '') {
					$this->error('存在当事人未填写车辆牌号');
				}
			}

		}

		$this->success('第一步验证成功', U('checkIsCanNormalCognizance2?sure=' . $isSure . '&case_id=' . $caseId));

	}

	public function checkIsCanNormalCognizance2() {

		$isSure = I('get.sure', '', 'int');
		$caseId = I('get.case_id', '', 'int');

		if ($caseId === '' || $isSure === '') {
			$this->error('非法操作', U('Index/index'));
		}

		if ($isSure == 1) {
			$url = U('CaseCognizance/normalIndex?case_id=' . $caseId . '&action=investigation_report');
		} else {
			$url = U('CaseCognizance/unCognizanceIndex?case_id=' . $caseId . '&action=investigation_report');
		}

		// 案件信息
		$map = array();
		$map['id'] = $caseId;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在', U('Index/index'));
		}
		if ($case['is_del'] == 1) {
			$this->error('案件已作废', U('Index/index'));
		}

		// 判断案件是否有没有完成的检验鉴定记录
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$map['is_over'] = 0;
		$caseCheckup = M('CaseCheckup')->where($map)->find();
		if (!empty($caseCheckup)) {
			$map = array();
			$map['id'] = $caseCheckup['checkup_org_id'];
			$checkupOrg = M('CheckupOrg')->where($map)->find();
			$map = array();
			$map['id'] = $caseCheckup['checkup_org_item_id'];
			$checkOrgItem = M('CheckupOrgItem')->where($map)->find();
			$this->error('当前有委托 “' . $checkupOrg['name'] . '”的 “' . $checkOrgItem['name'] . '”没完成<br>继续请点击“确定”，否则点击“取消”', $url);
		}
		$this->success('第二步验证成功', $url);
	}

	/**
	 * 必须有有效的事故认定
	 */
	public function checkCognizance() {
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作', U('Index/index'));
		}
		//逃逸
		$map = array();
		$map['case_id'] = $caseId;
		$esconginzanceInfo = M('CaseCoescape')->where($map)->find();
		//简易
		$map = array();
		$map['case_id'] = $caseId;
		$map['cognizance_type'] = 0;
		$map['is_back'] = 0;
		$cognizanceInfoSimple = M('CaseCognizance')->where($map)->find();
		//一般
		$map = array();
		$map['case_id'] = $caseId;
		$map['cognizance_type'] = 1;
		$map['is_back'] = 0;
		$map['is_make'] = 1;
		$cognizanceInfo = M('CaseCognizance')->where($map)->find();
		if (empty($esconginzanceInfo) && empty($cognizanceInfo) && empty($cognizanceInfoSimple)) {
			$this->error('未进行事故认定，不能进行调解，请检查！');
		}
		$this->success('', U("CaseMediateHandle/index?case_id=" . $caseId));
	}
}