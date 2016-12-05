<?php
namespace Admin\Controller;

/**
 * 案件08系统--综合分析接处警信息
 */
class CaseExtReasonReadController extends CommonController {

	public function add() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		$this->assign('caseData', $caseData);
		$this->assign('caseDataJson', json_encode($caseData));
		//读取当前案件所有有效当事人---车辆信息
		$allClient = $this->getValidClient($case_id);
		$this->assign('allClient', $allClient);

		$case_ext_reason = C('custom_case_ext.case_ext_reason');
		$this->assign('case_ext_reason', $case_ext_reason);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = new \Think\Model();
		$Model1 = D('CaseExtReason');
		$Model2 = D('CaseExtReasonCaseinfo');

		// 开启事务
		$Model->startTrans();
		// 创建数据
		$dataCaseExtReason = $Model1->create();
		$dataCaseExtReasonCaseinfo = $Model2->create();
		if (false === $dataCaseExtReason) {
			$this->error($Model1->getError());
		}
		if (false === $dataCaseExtReasonCaseinfo) {
			$this->error($Model2->getError());
		}
		$dataCaseExtReason['reason_man'] = serialize(I('post.reason_man', '', 'strip_tags'));
		$dataCaseExtReason['reason_car'] = serialize(I('post.reason_car', '', 'strip_tags'));
		$dataCaseExtReason['reason_road'] = serialize(I('post.reason_road', '', 'strip_tags'));
		$dataCaseExtReasonCaseinfo['accept_time'] = strtotime(I('post.accept_time', '', 'strip_tags'));
		$dataCaseExtReasonCaseinfo['out_time'] = strtotime(I('post.out_time', '', 'strip_tags'));
		$id = I('post.id', '', 'strip_tags');
		if (!$id) {
			unset($dataCaseExtReason['id']);
		}
		$map = array();
		$map['client_id'] = I('post.client_id', '', 'strip_tags');
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$reasonInfo = M('CaseExtReason')->where($map)->find();
		if ($reasonInfo) {
			$dataCaseExtReason['id'] = $reasonInfo['id'];
			$result_1 = $Model1->save($dataCaseExtReason);

		} else {
			$result_1 = $Model1->add($dataCaseExtReason);
		}
		$map = array();
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$caseInfo = M('CaseExtReasonCaseinfo')->where($map)->find();
		if ($caseInfo) {
			$dataCaseExtReasonCaseinfo['id'] = $caseInfo['id'];
			$result_2 = M('CaseExtReasonCaseinfo')->save($dataCaseExtReasonCaseinfo);
		} else {
			$result_2 = M('CaseExtReasonCaseinfo')->add($dataCaseExtReasonCaseinfo);
		}
		// 数据保存失败
		if (!$result_1 && !$result_2) {
			$Model->rollback();
			$this->error('操作失败');
		}
		// 成功
		$Model->commit();
		$this->success();
	}

	/**
	 * 新增界面
	 */
	public function edit() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		$this->assign('caseData', $caseData);
		$map = array();
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$data = D('CaseExtReason')->where($map)->find();
		$this->assign('data', $data);
		$case_ext_reason = C('custom_case_ext.case_ext_reason');
		$this->assign('case_ext_reason', $case_ext_reason);

		// 渲染
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function update() {

		// 实例化模型
		$Model = D('CaseExtReason');
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		// 开启事务
		//$Model->startTrans();
		$id = $Model->save($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('操作失败');
		}

		// 成功
		$Model->commit();
		$this->success($data);
	}
	//获取所有有效当事人--车辆信息
	public function getValidClient($case_id) {
		//案件及当事人（当事人车辆）信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$map['case_client_is_del'] = 0;
		$traffic_type = C('custom.traffic_type');
		//剔除 当事人的交通方式为步行 非道路交通事故当事人
		//unset($traffic_type[1]);
		unset($traffic_type[8]);
		$traffic_type_array = array();
		foreach ($traffic_type as $key => $val) {
			$traffic_type_array[] = $key;
		}
		$map['case_client_traffic_type'] = array('in', $traffic_type_array);
		$map['case_client_car_no'] = array('neq', '');
		$allChildren = D('CaseCaseClientView')->where($map)->order('train3 asc,create_time desc')->select();
		return $allChildren;
	}
	//AJAX--获取case_ext_reason  case_ext_reason_caseinfo 信息
	public function getCaseExtReasonData() {
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$map['client_id'] = I('post.client_id', '', 'strip_tags');
		if (!$map['case_id'] || !$map['client_id']) {
			$this->error('信息读取失败');
		}
		$CaseExtReasonInfo = D('CaseExtReason')->where($map)->find();
		$CaseExtReasonInfo['reason_man'] = unserialize($CaseExtReasonInfo['reason_man']);
		$CaseExtReasonInfo['reason_car'] = unserialize($CaseExtReasonInfo['reason_car']);
		$CaseExtReasonInfo['reason_road'] = unserialize($CaseExtReasonInfo['reason_road']);
		unset($map['client_id']);
		$CaseExtReasonCaseInfo = D('CaseExtReasonCaseinfo')->where($map)->find();
		if (!$CaseExtReasonCaseInfo['accept_time']) {
			$CaseExtReasonCaseInfo['accept_time'] = time();
		}
		if (!$CaseExtReasonCaseInfo['out_time']) {
			$CaseExtReasonCaseInfo['out_time'] = time();
		}
		// if (!$CaseExtReasonCaseInfo['police_arrive_time']) {
		// 	$CaseExtReasonCaseInfo['police_arrive_time'] = '';
		// } else {
		// 	$CaseExtReasonCaseInfo['out_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['police_arrive_time']);
		// }
		// if (!$CaseExtReasonCaseInfo['doctor_arrive_time']) {
		// 	$CaseExtReasonCaseInfo['doctor_arrive_time'] = '';
		// } else {
		// 	$CaseExtReasonCaseInfo['doctor_arrive_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['doctor_arrive_time']);
		// }
		// if (!$CaseExtReasonCaseInfo['fireman_arrive_time']) {
		// 	$CaseExtReasonCaseInfo['fireman_arrive_time'] = '';
		// } else {
		// 	$CaseExtReasonCaseInfo['fireman_arrive_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['fireman_arrive_time']);
		// }
		// if (!$CaseExtReasonCaseInfo['handle_time']) {
		// 	$CaseExtReasonCaseInfo['handle_time'] = '';
		// } else {
		// 	$CaseExtReasonCaseInfo['handle_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['handle_time']);
		// }
		$CaseExtReasonCaseInfo['accept_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['accept_time']);
		$CaseExtReasonCaseInfo['out_time'] = date('Y-m-d H:i', $CaseExtReasonCaseInfo['out_time']);
		unset($CaseExtReasonCaseInfo['id']);
		if (!$CaseExtReasonInfo || !$CaseExtReasonCaseInfo) {
			$this->error('信息读取失败');

		}
		$data = array_merge($CaseExtReasonInfo, $CaseExtReasonCaseInfo);
		$this->success(array(0 => $data));
	}

	//ajax获取原因类型二级子选项
	public function getReasonTypeChildren() {
		$pid = I('post.pid', '', 'strip_tags');
		$code = I('post.code', '', 'strip_tags');
		if (!$pid) {
			$this->error('无相关数据');
		}
		$caseExtReason = C('custom_case_ext.case_ext_reason');
		$reason_type_cids = $caseExtReason['reason_type'][$code]['value'][$pid]['value'];
		$this->success(array($reason_type_cids));
	}

	//ajax获取原因类型三级子选项
	public function getReasonTypeGrandChildren() {
		$pid = I('post.pid', '', 'strip_tags');
		$code = I('post.code', '', 'strip_tags');
		$cid = I('post.cid', '', 'strip_tags');
		if (!$pid) {
			$this->error('无相关数据');
		}
		$caseExtReason = C('custom_case_ext.case_ext_reason');
		$reason_type_cids = $caseExtReason['reason_type'][$code]['value'][$pid]['value'][$cid]['value'];
		$this->success(array($reason_type_cids));
	}
	//AJAX--改变排序值
	public function setTrain() {
		$map['is_del'] = 0;
		$case_id = I('post.case_id', '', 'strip_tags');
		$train = I('post.train', '', 'strip_tags');

		//echo json_encode($train);exit;
		if (!$case_id || !$train) {
			$this->error('设置失败');
		}
		$Model = D('CaseClient');
		// 开启事务
		$Model->startTrans();

		foreach ($train as $value) {
			$map['train3'] = $value[0];
			$map['id'] = $value[1];
			$map['update_time'] = time();
			$map['case_id'] = $case_id;
			$saveTrain = $Model->save($map);
			if (!$saveTrain) {
				$Model->rollback();
				$this->error('设置失败');

			}

		}
		$Model->commit();
		$this->success('设置成功');
	}

}
