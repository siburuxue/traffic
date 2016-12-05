<?php
namespace Admin\Controller;

/**
 * 案件08系统--车辆信息
 */
class CaseExtCarController extends CommonController {

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

		$case_ext_car = C('custom_case_ext.case_ext_car');
		$this->assign('case_ext_car', $case_ext_car);

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('CaseExtCar');
		// 开启事务
		$Model->startTrans();
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

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
		$data = D('CaseExtCar')->where($map)->find();
		$this->assign('data', $data);
		$case_ext_car = C('custom_case_ext.case_ext_car');
		$this->assign('case_ext_car', $case_ext_car);

		// 渲染
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function update() {

		// 实例化模型
		$Model = D('CaseExtCar');
		// 开启事务
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

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
		$allChildren = D('CaseCaseClientView')->where($map)->order('train1 asc,case_client_create_time desc')->select();
		return $allChildren;
	}

	//AJAX--获取case_ext_car 信息
	public function getCaseExtCarData() {
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$map['car_no'] = I('post.car_no', '', 'strip_tags');
		if (!$map['case_id'] || !$map['car_no']) {
			$this->error('信息读取失败');
		}
		$caseExtCarInfo = D('CaseExtCar')->where($map)->find();
		if (!$caseExtCarInfo) {
			$this->error('信息读取失败');

		}
		$this->success(array(0 => $caseExtCarInfo));
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
			$map['train1'] = $value[0];
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
