<?php
namespace Admin\Controller;

/**
 * 案件08系统--当事人信息
 */
class CaseExtClientReadController extends CommonController {

	/**
	 * 新增界面 call_user_func call_user_func_array call_user_func_array(array('ClassA','bc'), array("111", "222"));
	 */
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

		$case_ext_client = C('custom_case_ext.case_ext_client');
		$this->assign('case_ext_client', $case_ext_client);

		$law = D('Law')->where('pid=0 and is_del=0')->select();
		$this->assign('law', $law);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('CaseExtClient');
		// 开启事务
		$Model->startTrans();
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$die_time = I('post.die_time', '', 'strip_tags');
		$data['die_time'] = strtotime($die_time);
		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$data['id'] = $id;
		// 成功
		$Model->commit();
		$this->success($data);
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
		$data = D('CaseExtClient')->where($map)->find();
		$this->assign('data', $data);
		$case_ext_client = C('custom_case_ext.case_ext_client');
		$this->assign('case_ext_client', $case_ext_client);

		// 渲染
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function update() {

		// 实例化模型
		$Model = D('CaseExtClient');
		// 开启事务
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$die_time = I('post.die_time', '', 'strip_tags');
		$data['die_time'] = strtotime($die_time);
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
		$map['case_client_name'] = array('neq', '');
		$allChildren = D('CaseCaseClientView')->where($map)->order('train2 asc,create_time desc')->select();
		return $allChildren;
	}
	//AJAX--获取case_ext_client 信息
	public function getCaseExtClientData() {
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$map['client_id'] = I('post.client_id', '', 'strip_tags');
		if (!$map['case_id'] || !$map['client_id']) {
			$this->error('信息读取失败');
		}
		$CaseExtClientInfo = D('CaseExtClient')->where($map)->find();
		if (!$CaseExtClientInfo) {
			$this->error('信息读取失败');

		}
		// if ($CaseExtClientInfo['die_time']) {
		// 	$CaseExtClientInfo['die_time'] = date('Y-m-d H:i', $CaseExtClientInfo['die_time']);
		// } else {
		// 	$CaseExtClientInfo['die_time'] = "";
		// }
		$this->success(array(0 => $CaseExtClientInfo));
	}

	//AJAX--获取法律条款 信息
	public function getLawChildren() {
		$map = array();
		$map['is_del'] = 0;
		$map['pid'] = I('post.pid', '', 'strip_tags');
		if (!$map['pid']) {
			$this->error('信息读取失败');
		}
		$law = D('Law')->where($map)->select();
		if (!$law) {
			$this->error('信息读取失败');

		}
		$this->success(array(0 => $law));
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
			$map['train2'] = $value[0];
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
