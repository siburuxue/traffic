<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 询问笔录
 */
class CaseStandingBookController extends CommonController {
	/**
	 * 列表页
	 */
	public function index() {
		//所属大队
		$map = array();
		$map['cate'] = 2;
		$map['is_del'] = 0;
		$brigadeList = M('Department')->field('id,name')->where($map)->select();
		//获得当前大队的办案人
		$myBrigade = $this->getMyBrigade();
		$map['department_id'] = $myBrigade['id'];
		$map['is_del'] = 0;
		$map['is_locked'] = 0;
		$userList = M('User')->where($map)->select();
		//事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		//财产损失
		$this->assign('property_loss', get_dict('property_loss'));
		//初查状况
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		//交通方式
		$this->assign('trafficType', get_custom_config('traffic_type'));
		//车牌号
		$this->assign('carNo', get_custom_config('car_no'));
		//伤害程度
		$this->assign('hurtType', get_custom_config('hurt_type'));
		//事故责任
		$this->assign('blameType', get_custom_config('blame_type'));
		//当前状态
		$this->assign('statusType', get_custom_config('status_type'));
		//违法行为
		$map = array();
		$map['pid'] = 0;
		$map['is_del'] = 0;
		$lawList = M('Law')->where($map)->select();
		//当前办案人所属大队
		$this->assign('brigade', $myBrigade);
		$this->assign('lawList', $lawList);
		$this->assign('brigadeList', $brigadeList);
		$this->assign('userList', $userList);
		$this->assign('type', I('get.type'));
		$this->display();
	}

	/**
	 * @return 案件列表ID所组成的数组
	 */
	private function getResultIds($condition) {
		//所有案件ID
		$map = array();
		$map['is_del'] = 0;
		$map['cate'] = 0;
		$caseList = M('Case')->field('id as case_id')->where($map)->order('id')->select();
		$caseList = self::getCaseIdsArray($caseList);
		//大队-案件列表
		if ($condition['brigade'] != '') {
			$map = array();
			$map['ts_user.department_id'] = $condition['brigade'];
			$brigadeList = M('User')
				->field('ts_case_handle.case_id')
				->join('ts_case_handle on ts_case_handle.user_id = ts_user.id')
				->where($map)
				->select();
			$brigadeList = self::getCaseIdsArray($brigadeList);
		} else {
			$brigadeList = null;
		}
		//用户-案件列表
		if ($condition['user'] != '') {
			$map = array();
			$map['user_id'] = $condition['user'];
			$map['is_now'] = 1;
			$userHandleCaseList = M('CaseHandle')->field('distinct case_id')->where($map)->order('case_id')->select();
			$userHandleCaseList = self::getCaseIdsArray($userHandleCaseList);
		} else {
			$userHandleCaseList = null;
		}
		//案件主表
		$map = array();
        //事故时间
        if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
            $map['accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
        } else if (is_time($condition['end_time'])) {
            $map['accident_time'] = array('elt', strtotime($condition['end_time']));
        } else if (is_time($condition['start_time'])) {
            $map['accident_time'] = array('egt', strtotime($condition['start_time']));
        }
		//事故发生地点
		if ($condition['accident_place'] != '') {
			$map['accident_place'] = array('LIKE', '%' . $condition['accident_place'] . '%');
		}
		//事故类型
		if ($condition['accident_type'] != '') {
			$map['accident_type'] = $condition['accident_type'];
		}
		//事故后果-死亡人数
        if ($condition['death_num_min'] != '' && $condition['death_num_max'] != '') {
            $map['death_num'] = array(array('egt', $condition['death_num_min']), array('elt', $condition['death_num_max']));
        } else if (is_time($condition['death_num_max'])) {
            $map['death_num'] = array('elt', $condition['death_num_max']);
        } else if (is_time($condition['death_num_min'])) {
            $map['death_num'] = array('egt', $condition['death_num_min']);
        }
		//事故后果-受伤人数
        if ($condition['hurt_num_min'] != '' && $condition['hurt_num_max'] != '') {
            $map['hurt_num'] = array(array('egt', $condition['hurt_num_min']), array('elt', $condition['hurt_num_max']));
        } else if (is_time($condition['hurt_num_max'])) {
            $map['hurt_num'] = array('elt', $condition['hurt_num_max']);
        } else if (is_time($condition['hurt_num_min'])) {
            $map['hurt_num'] = array('egt', $condition['hurt_num_min']);
        }
		//财产损失
		if ($condition['property_loss'] != '') {
			$map['property_loss'] = $condition['property_loss'];
		}
		//事故初查
		if ($condition['first_result'] != '') {
			$map['first_cognizance'] = $condition['first_result'];
		}
		if ($condition['start_time'] != '' || $condition['end_time'] != '' || $condition['accident_place'] != '' || $condition['accident_type'] != '' || $condition['first_result'] != '' || $condition['death_num_min'] != '' || $condition['death_num_max'] != '' || $condition['hurt_num_min'] != '' || $condition['hurt_num_max'] != '' || $condition['property_loss'] != '') {
			$map['is_del'] = 0;
			$caseHandleCaseList = M('Case')->field('distinct id as case_id')->where($map)->order('id')->select();
			$caseHandleCaseList = self::getCaseIdsArray($caseHandleCaseList);
		} else {
			$caseHandleCaseList = null;
		}
		//当事人-案件列表
		$map = array();
		//当事人-姓名
		if ($condition['name'] != '') {
			$map['name'] = array('LIKE', '%' . $condition['name'] . '%');
		}
		//当事人-交通方式
		if ($condition['traffic_type'] != '') {
			$map['traffic_type'] = $condition['traffic_type'];
		}
		//当事人-车牌号
		if ($condition['car_no'] != '') {
			$map['car_no'] = array('LIKE', '%' . $condition['car_no'] . '%');
		}
		//当事人-受伤程度
		if ($condition['hurt_type'] != '') {
			$map['hurt_type'] = $condition['hurt_type'];
		}
		//当事人-责任
		if ($condition['blame_type'] != '') {
			$map['blame_type'] = $condition['blame_type'];
		}
		//当事人处罚
		if ($condition['coercive_measure'] != '') {
			if ($condition['coercive_measure'] == '1') {
				$map['punish_is_warning'] = 1;
			} else if ($condition['coercive_measure'] == '2') {
				$map['punish_is_fine'] = 1;
			} else if ($condition['coercive_measure'] == '3') {
				$map['punish_is_seize'] = 1;
			} else if ($condition['coercive_measure'] == '4') {
				$map['punish_is_revoke'] = 1;
			} else if ($condition['coercive_measure'] == '5') {
				$map['punish_is_detain'] = 1;
			}
		}
		if ($condition['name'] != '' || $condition['traffic_type'] != '' || $condition['car_no'] != '' || $condition['hurt_type'] != '' || $condition['blame_type'] != '' || $condition['coercive_measure'] != '') {
			$clientHandleCaseList = M('CaseClient')->field('distinct case_id')->where($map)->order('case_id')->select();
			$clientHandleCaseList = self::getCaseIdsArray($clientHandleCaseList);
		} else {
			$clientHandleCaseList = null;
		}
		//当事人-法律条款-案件列表
		$map = array();
		if ($condition['law'] != '') {
			$map['law_pid'] = $condition['law'];
			$clientIds = M('CaseClientLaw')->field('group_concat(distinct case_client_id) as ids')->where($map)->select();
			$map = array();
			$map['id'] = array('IN', $clientIds[0]['ids']);
			$lawHandleCaseList = M('CaseClient')->field('distinct case_id')->where($map)->order('case_id')->select();
			$lawHandleCaseList = self::getCaseIdsArray($lawHandleCaseList);
		} else {
			$lawHandleCaseList = null;
		}
		//认定时间
		$map = array();
		$map['is_back'] = 0;
        if (is_time($condition['cognizance_start_time']) && is_time($condition['cognizance_end_time'])) {
            $map['create_time'] = array(array('egt', strtotime($condition['cognizance_start_time'])), array('elt', strtotime($condition['cognizance_end_time'])));
        } else if (is_time($condition['cognizance_end_time'])) {
            $map['create_time'] = array('elt', strtotime($condition['cognizance_end_time']));
        } else if (is_time($condition['cognizance_start_time'])) {
            $map['create_time'] = array('egt', strtotime($condition['cognizance_start_time']));
        }
		if ($condition['cognizance_start_time'] != '' || $condition['cognizance_end_time'] != '') {
			$cognizanceHandleCaseList = M('CaseCognizance')->where($map)->select();
			$cognizanceHandleCaseList = self::getCaseIdsArray($cognizanceHandleCaseList);
		} else {
			$cognizanceHandleCaseList = null;
		}
		//处罚-案件列表
		if ($condition['punish_type'] != '') {
			if ($condition['punish_type'] == '1') {
				$punishHandleCaseList = M('Case')->field('ts_case.id as case_id')
					->join(' LEFT JOIN ts_case_punish on ts_case_punish.case_id = ts_case.id')
					->group('ts_case_punish.case_id')
					->having('count(ts_case_punish.create_user_id) > 0')->order('ts_case.id');
				$punishHandleCaseList = self::getCaseIdsArray($punishHandleCaseList);
			} else {
				$punishHandleCaseList = M('Case')->field('ts_case.id as case_id')
					->join(' LEFT JOIN ts_case_punish on ts_case_punish.case_id = ts_case.id')
					->group('ts_case_punish.case_id')
					->having('count(ts_case_punish.create_user_id) = 0')->order('ts_case.id');
				$punishHandleCaseList = self::getCaseIdsArray($punishHandleCaseList);
			}
		} else {
			$punishHandleCaseList = null;
		}
		//调解-案件列表
		$map = array();
		if ($condition['mediate_type'] != '') {
			$map['apply_status'] = $condition['mediate_type'];
			$mediateHandleCaseList = M('CaseMediateApply')->where($map)->order('case_id')->select();
			$mediateHandleCaseList = self::getCaseIdsArray($mediateHandleCaseList);
		} else {
			$mediateHandleCaseList = null;
		}
		//获取所有数组的交集
		$caseIdsArray = array_intersect($caseList,
			($brigadeList === null ? $caseList : $brigadeList),
			($userHandleCaseList === null ? $caseList : $userHandleCaseList),
			($caseHandleCaseList === null ? $caseList : $caseHandleCaseList),
			($clientHandleCaseList === null ? $caseList : $clientHandleCaseList),
			($lawHandleCaseList === null ? $caseList : $lawHandleCaseList),
			($cognizanceHandleCaseList === null ? $caseList : $cognizanceHandleCaseList),
			($punishHandleCaseList === null ? $caseList : $punishHandleCaseList),
			($mediateHandleCaseList === null ? $caseList : $mediateHandleCaseList));
		//当前状态筛选条件
		if ($condition['status_type'] != '') {
			$statusType = get_custom_config('status_type');
			$status = $statusType[$condition['status_type']];
			$idsArray = array();
			foreach ($caseIdsArray as $key => $val) {
				$caseStatus = new \Lib\CaseStatus($val);
				if ($status == $caseStatus->getStatus()) {
					array_push($idsArray, $val);
				}
			}
			$caseIdsArray = $idsArray;
		}

		return array_values($caseIdsArray);
	}

	/**
	 * table加载
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'ts_case.accident_time';
			break;
		default:
			$orderby = 'ts_case.create_time';
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
		//最后查询合并的结果集
		$allArray = array();
		//事故类型
		$accidentType = get_custom_config('accident_type');
		//财产损失
		$propertyLoss = get_dict('property_loss');
		//交通方式
		$trafficType = get_custom_config('traffic_type');
		//伤害程度
		$hurtType = get_custom_config('hurt_type');
		//事故责任
		$blameType = get_custom_config('blame_type');
		$condition = get_condition();
		//获取案件列表ID
		$caseIdsArray = self::getResultIds($condition);
		$count = count($caseIdsArray);
		// 分页信息
		$page = new Page($count, 15);
		$map = array();
		if ($count > 0) {
			$map['ts_case.id'] = array('IN', $caseIdsArray);
		} else {
			$map['ts_case.id'] = -1;
		}
		$map['ts_case_handle.is_now'] = 1;
		$caseResult = M('Case')->field('
                            ts_case.id,
                            ts_case.accident_time,
                            ts_case.death_num,
                            ts_case.hurt_num,
                            ts_case.property_loss,
                            ts_case.accident_place,
                            ts_case.accident_time,
                            ts_case.create_time,
                            ts_case.accident_type,
                            ts_user.true_name,
                            ts_department.name as department_name,
                            group_concat(ts_dict_option.name) as first_cognizance_name
                        ')
			->join(" LEFT JOIN ts_case_handle on ts_case_handle.case_id = ts_case.id")
			->join(' LEFT JOIN ts_user on ts_case_handle.user_id = ts_user.id')
			->join(' LEFT JOIN ts_department on ts_department.id = ts_user.department_id')
			->join(' LEFT JOIN ts_dict_option on ts_dict_option.id in (SELECT ts_case.first_cognizance FROM DUAL) ')
			->where($map)
			->group('ts_case.id')
			->order($orderby)
			->limit($page->firstrow, $page->rows)
			->select();
		foreach ($caseResult as $key => $val) {
			$case_id = $val['id'];
			//事故类型名字
			$accidentTypeName = $accidentType[$val['accident_type']];
			//事故后果
			foreach ($propertyLoss as $i => $item) {
				if ($item['id'] == $val['property_loss']) {
					$result = $item['name'];
					break;
				}
			}
			$val['result'] = "死亡人数：" . $val['death_num'] . "人，受伤人数：" . $val['hurt_num'] . "人，财产损失：" . $result;
			//是否调解
			$map = array();
			$map['case_id'] = $case_id;
			$mediateCount = M('CaseMediateApply')->where($map)->count();
			$mediateStatus = ($mediateCount == 0 ? '未调解' : '已调解');
			//当前状态
			$caseStatus = new \Lib\CaseStatus($case_id);
			$status = $caseStatus->getStatus();
			//事故认定时间
			$map = array();
			$map['case_id'] = $case_id;
			$map['is_bak'] = 0;
			$cognizanceInfo = M('CaseCognizance')->where($map)->order('id desc')->find();
			//当事人信息
			$map = array();
			$map['ts_case_client.case_id'] = $case_id;
			$clientRs = M('CaseClient')->field('
                                ts_case_client.id,
                                ts_case_client.name,
                                ts_case_client.traffic_type,
                                ts_case_client.car_no,
                                ts_case_client.hurt_type,
                                ts_case_client.blame_type,
                                ts_case_client.punish_is_warning,
                                ts_case_client.punish_is_fine,
                                ts_case_client.punish_is_seize,
                                ts_case_client.punish_is_revoke,
                                ts_case_client.punish_is_detain,
                                group_concat(ts_law.title) as law_name
                            ')
				->join(' LEFT JOIN ts_case_client_law on ts_case_client_law.case_client_id = ts_case_client.id')
				->join(' LEFT JOIN ts_law on ts_case_client_law.law_pid = ts_law.id')
				->where($map)
				->group('ts_case_client.id')
				->select();
			if (count($clientRs) > 0) {
				foreach ($clientRs as $k => $v) {
					$map = array();
					$map['case_id'] = $case_id;
					$map['case_client_id'] = $v['id'];
					$punishCount = M('CasePunish')->where($map)->count();
					$punishStatus = ($punishCount == 0 ? '未处罚' : '已处罚');
					$str = '';
					if ($v['punish_is_warning'] == '1') {
						$str .= '警告 ';
					}
					if ($v['punish_is_fine'] == '1') {
						$str .= '罚款 ';
					}
					if ($v['punish_is_seize'] == '1') {
						$str .= '暂扣 ';
					}
					if ($v['punish_is_revoke'] == '1') {
						$str .= '吊销 ';
					}
					if ($v['punish_is_detain'] == '1') {
						$str .= '拘留 ';
					}
					array_push($allArray, array(
						'id' => $val['id'], //主键
						'brigadeName' => $val['department_name'], //所属大队
						'accidentTime' => $val['accident_time'], //事故时间
						'accidentPlace' => $val['accident_place'], //事故地点
						'accidentType' => $accidentTypeName, //事故类型
						'result' => $val['result'], //事故后果
						'firstCognizance' => $val['first_cognizance_name'], //初查情况
						'name' => $v['name'], //姓名
						'trafficType' => $trafficType[$v['traffic_type']], //交通方式
						'carNo' => $v['car_no'], //车牌号
						'law' => $v['law_name'], //违法行为
						'hurtType' => $hurtType[$v['hurt_type']], //伤害程度
						'coerciveMeasure' => $str, //行政强制措施
						'blameType' => $blameType[$v['blame_type']], //事故责任
						'punishStatus' => $punishStatus, //处罚
						'cognizanceTime' => $cognizanceInfo['create_time'], //责任认定时间
						'mediateStatus' => $mediateStatus, //调解
						'status' => $status, //当前状态
						'handleName' => $val['true_name'], //办案人
						'count' => count($clientRs),
					));
				}
			} else {
				array_push($allArray, array(
					'id' => $val['id'], //主键
					'brigadeName' => $val['department_name'], //所属大队
					'accidentTime' => $val['accident_time'], //事故时间
					'accidentPlace' => $val['accident_place'], //事故地点
					'accidentType' => $accidentTypeName, //事故类型
					'result' => $val['result'], //事故后果
					'firstCognizance' => $val['first_cognizance_name'], //初查情况
					'name' => $v['name'], //姓名
					'trafficType' => $trafficType[$v['traffic_type']], //交通方式
					'carNo' => $v['car_no'], //车牌号
					'law' => $v['law_name'], //违法行为
					'hurtType' => $hurtType[$v['hurt_type']], //伤害程度
					'coerciveMeasure' => $str, //行政强制措施
					'blameType' => $blameType[$v['blame_type']], //事故责任
					'punishStatus' => $punishStatus, //处罚
					'cognizanceTime' => $cognizanceInfo['create_time'], //责任认定时间
					'mediateStatus' => $mediateStatus, //调解
					'status' => $status, //当前状态
					'handleName' => $val['true_name'], //办案人
					'count' => 1,
				));
			}

		}
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->assign('allArray', $allArray);
		$this->display('CaseStandingBook/index/table');
	}

	/**
	 * 根据大队选取办案人
	 */
	public function getUserList() {
		$map = array();
		$map['department_id'] = I('post.brigadeId', '', 'int');
		$map['is_del'] = 0;
		$map['is_locked'] = 0;
		$list = M('User')->where($map)->select();
		$this->success($list);
	}

	/**
	 * 获取案件ID数组
	 */
	private function getCaseIdsArray($array) {
		$ids = array();
		foreach ($array as $key => $val) {
			array_push($ids, $val['case_id']);
		}
		return $ids;
	}

	/**
	 * 导出Excel
	 */
	public function exportExcel() {
		//最后查询合并的结果集
		$allArray = array();
		//事故类型
		$accidentType = get_custom_config('accident_type');
		//交通方式
		$trafficType = get_custom_config('traffic_type');
		//伤害程度
		$hurtType = get_custom_config('hurt_type');
		//事故责任
		$blameType = get_custom_config('blame_type');
		$condition = json_decode(urldecode(I('get.paras')), true);
		//获取案件列表ID
		$caseIdsArray = self::getResultIds($condition);
		$map = array();
		$map['ts_case.id'] = array('IN', $caseIdsArray);
		$map['ts_case_handle.is_now'] = 1;
		$caseResult = M('Case')->field('
                            ts_case.id,
                            ts_case.accident_time,
                            ts_case.accident_place,
                            ts_case.accident_type,
                            ts_user.true_name,
                            ts_department.name as department_name,
                            group_concat(ts_dict_option.name) as first_cognizance_name
                        ')
			->join(" LEFT JOIN ts_case_handle on ts_case_handle.case_id = ts_case.id")
			->join(' LEFT JOIN ts_user on ts_case_handle.user_id = ts_user.id')
			->join(' LEFT JOIN ts_department on ts_department.id = ts_user.department_id')
			->join(' LEFT JOIN ts_dict_option on ts_dict_option.id in (SELECT ts_case.first_cognizance FROM DUAL) ')
			->where($map)
			->group('ts_case.id')
			->order('ts_case.id desc')
			->select();
		foreach ($caseResult as $key => $val) {
			$case_id = $val['id'];
			$accidentTypeName = $accidentType[$val['accident_type']];
			//是否调解
			$map = array();
			$map['case_id'] = $case_id;
			$mediateCount = M('CaseMediateApply')->where($map)->count();
			$mediateStatus = ($mediateCount == 0 ? '未调解' : '已调解');
			//当前状态
			$caseStatus = new \Lib\CaseStatus($case_id);
			$status = $caseStatus->getStatus();
			//事故认定时间
			$map = array();
			$map['case_id'] = $case_id;
			$map['is_bak'] = 0;
			$cognizanceInfo = M('CaseCognizance')->where($map)->order('id desc')->find();
			//当事人信息
			$map = array();
			$map['ts_case_client.case_id'] = $case_id;
			$clientRs = M('CaseClient')->field('
                                ts_case_client.id,
                                ts_case_client.name,
                                ts_case_client.traffic_type,
                                ts_case_client.car_no,
                                ts_case_client.hurt_type,
                                ts_case_client.blame_type,
                                ts_case_client.punish_is_warning,
                                ts_case_client.punish_is_fine,
                                ts_case_client.punish_is_seize,
                                ts_case_client.punish_is_revoke,
                                ts_case_client.punish_is_detain,
                                group_concat(ts_law.title) as law_name
                            ')
				->join(' LEFT JOIN ts_case_client_law on ts_case_client_law.case_client_id = ts_case_client.id')
				->join(' LEFT JOIN ts_law on ts_case_client_law.law_pid = ts_law.id')
				->where($map)
				->group('ts_case_client.id')
				->select();
			if (count($clientRs) > 0) {
				foreach ($clientRs as $k => $v) {
					$map = array();
					$map['case_id'] = $case_id;
					$map['case_client_id'] = $v['id'];
					$punishCount = M('CasePunish')->where($map)->count();
					$punishStatus = ($punishCount == 0 ? '未处罚' : '已处罚');
					$str = '';
					if ($v['punish_is_warning'] == '1') {
						$str .= '警告 ';
					}
					if ($v['punish_is_fine'] == '1') {
						$str .= '罚款 ';
					}
					if ($v['punish_is_seize'] == '1') {
						$str .= '暂扣 ';
					}
					if ($v['punish_is_revoke'] == '1') {
						$str .= '吊销 ';
					}
					if ($v['punish_is_detain'] == '1') {
						$str .= '拘留 ';
					}
					array_push($allArray, array(
						'brigadeName' => $val['department_name'], //所属大队
						'accidentTime' => $val['accident_time'], //事故时间
						'accidentPlace' => $val['accident_place'], //事故地点
						'accidentType' => $accidentTypeName, //事故类型
						'accidentResult' => '',
						'firstCognizance' => $val['$first_cognizance_name'], //初查情况
						'name' => $v['name'], //姓名
						'trafficType' => $trafficType[$v['traffic_type']], //交通方式
						'carNo' => $v['car_no'], //车牌号
						'law' => $v['law_name'], //违法行为
						'hurtType' => $hurtType[$v['hurt_type']], //伤害程度
						'coerciveMeasure' => $str, //行政强制措施
						'blameType' => $blameType[$v['blame_type']], //事故责任
						'punishStatus' => $punishStatus, //处罚
						'cognizanceTime' => $cognizanceInfo['create_time'], //责任认定时间
						'mediateStatus' => $mediateStatus, //调解
						'status' => $status, //当前状态
						'handleName' => $val['true_name'], //办案人
						'count' => count($clientRs),
						'id' => $val['id'], //主键
					));
				}
			} else {
				array_push($allArray, array(
					'brigadeName' => $val['department_name'], //所属大队
					'accidentTime' => $val['accident_time'], //事故时间
					'accidentPlace' => $val['accident_place'], //事故地点
					'accidentType' => $accidentTypeName, //事故类型
					'accidentResult' => '',
					'firstCognizance' => $val['first_cognizance_name'], //初查情况
					'name' => $v['name'], //姓名
					'trafficType' => $trafficType[$v['traffic_type']], //交通方式
					'carNo' => $v['car_no'], //车牌号
					'law' => $v['law_name'], //违法行为
					'hurtType' => $hurtType[$v['hurt_type']], //伤害程度
					'coerciveMeasure' => $str, //行政强制措施
					'blameType' => $blameType[$v['blame_type']], //事故责任
					'punishStatus' => $punishStatus, //处罚
					'cognizanceTime' => $cognizanceInfo['create_time'], //责任认定时间
					'mediateStatus' => $mediateStatus, //调解
					'status' => $status, //当前状态
					'handleName' => $val['true_name'], //办案人
					'count' => 1,
					'id' => $val['id'], //主键
				));
			}

		}
		$titleArray = array("所属大队", "事故时间", "事故地点", "事故类型", "事故后果", "初查情况", "姓名", "交通方式", "车牌号", "违法行为", "伤害程度", "行政强制措施", "事故责任", "处罚", "责任认定时间", "调解", "当前状态", "办案人");
		//行位置
		$xAxisArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		//一共有多少行数据
		$rows = count($allArray);
		//一共有多少列数据
		$cells = 18;
		$xlsTitle = iconv('UTF-8','gb2312','交通事故台账');
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:R1");
		//设置表格样式
		$objPHPExcel->getActiveSheet()->setCellValue("A1", $xlsTitle);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//写title
		for ($i = 0; $i < $cells; $i++) {
			//写入title
			$objPHPExcel->getActiveSheet()->setCellValue("{$xAxisArray[$i]}2", $titleArray[$i]);
			//设置表格样式
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//设置表格宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(20);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle('A2:R2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:R2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($allArray[$j] as $key => $val) {
				if ($k < 18) {
					//写入title
					$objPHPExcel->getActiveSheet()->setCellValue($xAxisArray[$k] . ($j + 3), $val);
					//设置表格样式
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
					//加边框
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$k++;
				} else {
					continue;
				}
			}
		}
		$mergeNum = 0;
		//合并单元格
		for ($z = 0; $z < $rows; $z++) {
			$count = (int) $allArray[$z]['count'];
			if ($z < $mergeNum) {
				continue;
			} else {
				$mergeNum += $count;
				if ($count > 1) {
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[0] . ($z + 3) . ':' . $xAxisArray[0] . ($z + 3 + ($count - 1))); //所属大队合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[1] . ($z + 3) . ':' . $xAxisArray[1] . ($z + 3 + ($count - 1))); //事故时间合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[2] . ($z + 3) . ':' . $xAxisArray[2] . ($z + 3 + ($count - 1))); //事故地点合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[3] . ($z + 3) . ':' . $xAxisArray[3] . ($z + 3 + ($count - 1))); //事故类型合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[4] . ($z + 3) . ':' . $xAxisArray[4] . ($z + 3 + ($count - 1))); //事故后果合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[5] . ($z + 3) . ':' . $xAxisArray[5] . ($z + 3 + ($count - 1))); //初查情况合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[14] . ($z + 3) . ':' . $xAxisArray[14] . ($z + 3 + ($count - 1))); //责任认定时间合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[15] . ($z + 3) . ':' . $xAxisArray[15] . ($z + 3 + ($count - 1))); //调解合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[16] . ($z + 3) . ':' . $xAxisArray[16] . ($z + 3 + ($count - 1))); //当前状态合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[17] . ($z + 3) . ':' . $xAxisArray[17] . ($z + 3 + ($count - 1))); //办案人合并单元格
				}
			}
		}
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
		header("Content-Disposition:attachment;filename=$xlsTitle.xls"); //attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
}