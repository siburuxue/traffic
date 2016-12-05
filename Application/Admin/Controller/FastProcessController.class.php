<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 用户
 */
class FastProcessController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		$this->display();
	}

	/**
	 * 首页Table加载
	 */
	public function indexTable() {
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

		$trafficType = get_custom_config('traffic_type');
		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		$condition = get_condition();
		//当事人姓名
		$clientName = $condition['case_client_true_name'];
		//车牌号
		$carNo = $condition['case_client_car_no'];
		//事故发生时间
		$startTime = strtotime($condition['start_time']);
		$endTime = strtotime($condition['end_time']);
		//事故类型
		$accidentType = $condition['accident_type'];

		// 搜索条件
		$map = array();
		// 不显示作废案件
		$map['CaseInfo.is_del'] = 0;
		// 只能查看当前办案人的案件
		$map['CaseHandle.user_id'] = $this->my['id'];
		// 只能查看当前办案人所在大队的案件
		$map['CaseInfo.department_id'] = $myBrigade['id'];
		//事故发生时间
		if (is_time($startTime) && is_time($endTime)) {
			$map['CaseInfo.accident_time'] = array(array('egt', $startTime), array('elt', $endTime));
		} else if (is_time($condition['end_time'])) {
			$map['CaseInfo.accident_time'] = array(array('elt', $endTime));
		} else if (is_time($condition['start_time'])) {
			$map['CaseInfo.accident_time'] = array(array('egt', $startTime));
		}
		isset($accidentType) && $map['CaseInfo.accident_type'] = $accidentType;
		if ($clientName != '' || $carNo != '') {
			$data = array();
			isset($clientName) && $data['name'] = array('LIKE', '%' . $clientName . "%");
			isset($carNo) && $data['car_no'] = array('LIKE', '%' . $carNo . "%");
			$caseList = M('CaseClient')->field('GROUP_CONCAT(case_id) as case_id')->where($data)->select();
			$map['id'] = array("IN", '-1,' . $caseList[0]['case_id']);
		}
		//只显示快赔的案件
		$map['cate'] = 1;
		$model = D('FastProcessCaseView');
		$count = $model->where($map)->count('distinct CaseInfo.id');
		$page = new Page($count, 15);
		$list = $model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->select();
		$array = array();
		foreach ($list as $key => $val) {
			$map = array();
			$map['case_id'] = $val['id'];
			$cognizanceCount = M('CaseCognizanceSimple')->where($map)->count();
			$map = array();
			$map['case_id'] = $val['id'];
			$clientList = M('CaseClient')->where($map)->order('id desc')->select();
			if (count($clientList) > 0) {
				foreach ($clientList as $k => $v) {
					$data = array();
					$data['count'] = count($clientList) == 0 ? 1 : count($clientList);
					$data['true_name'] = $list[$key]['true_name'];
					$data['accident_place'] = $list[$key]['accident_place'];
					$data['accident_time'] = $list[$key]['accident_time'];
					$data['code'] = $list[$key]['code'];
					$data['id'] = $list[$key]['id'];
					$data['cognizance_status'] = ($cognizanceCount == '0' ? '未认定' : '已认定');
					$data['name'] = $v['name'];
					$data['traffic_type'] = $trafficType[(int) $v['traffic_type']];
					$data['car_no'] = $v['car_no'];
					array_push($array, $data);
				}
			} else {
				$data = array();
				$data['count'] = count($clientList) == 0 ? 1 : count($clientList);
				$data['true_name'] = $list[$key]['true_name'];
				$data['accident_place'] = $list[$key]['accident_place'];
				$data['accident_time'] = $list[$key]['accident_time'];
				$data['code'] = $list[$key]['code'];
				$data['id'] = $list[$key]['id'];
				$data['cognizance_status'] = ($cognizanceCount == '0' ? '未认定' : '已认定');
				$data['name'] = '';
				$data['traffic_type'] = '';
				$data['car_no'] = '';
				array_push($array, $data);
			}
		}
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('list', $array);
		$this->assign('page', $pageInfo);
		$this->display('FastProcess/index/table');
	}

	/**
	 * 新增加载
	 */
	public function add() {
		// 当前用户所在大队
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		$this->display();
	}

	/**
	 * 执行新增
	 */
	public function insert() {
		$time = time();
		$date = date('Ym');
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		if (empty($myBrigade['area_code'])) {
			$this->error('当前用户所在大队缺失行政区划代码');
		}
		$accidentTime = strtotime(I('post.accident_time'));
		$accidentPlace = I('post.accident_place');
		$accidentName = I('post.accident_name');
		if ($accidentTime == '') {
			$this->error('请选择事故发生时间');
		}
		if ($accidentPlace == '') {
			$this->error('请选择事故发生地点');
		}
		if ($accidentName == '') {
			$this->error('请选择事故名称');
		}
		$cate = 1;
		$departmentId = $myBrigade['id'];
		$map = array();
		$map['cate'] = $cate;
		$map['accident_name'] = $accidentName;
		$map['accident_place'] = $accidentPlace;
		$map['accident_time'] = $accidentTime;
		$map['department_id'] = $departmentId;
		// 案件序号
		$map['num'] = create_fast_case_num($date);
		// 案件编号
		$map['code'] = create_fast_case_code($date, $map['num']);
		$map['create_time'] = $time;
		$map['create_user_id'] = $this->my['id'];
		$map['update_time'] = $time;
		$map['update_user_id'] = $this->my['id'];
		$model = M('Case');
		$model->startTrans();
		$id = $model->add($map);
		if (!$id) {
			$model->rollback();
			$this->error('数据保存失败');
		}
		// 创建办案人
		$data = array();
		$data['case_id'] = $id;
		$data['user_id'] = $this->my['id'];

		$caseHandleModel = D('CaseHandle');
		$data = $caseHandleModel->create($data);
		if (false === $data) {
			$model->rollback();
			$this->error($caseHandleModel->getError());
		}

		$res = $caseHandleModel->add($data);
		if (!$res) {
			$model->rollback();
			$this->error('数据保存失败');
		}

		$model->commit();
		$this->success('新增成功', U('detail?case_id=' . $id));
	}

	/**
	 * 详细画面
	 */
	public function detail() {
		// 案件编号
		$id = I('get.case_id', '', 'int');
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
		//受案登记信息
		$map = array();
		$map['CaseAlarm.case_id'] = $info['id'];
		$alarmList = D('CaseAlarmView')->where($map)->limit(0, 5)->select();

		$map = array();
		$map['case_id'] = $id;
		$map['traffic_type'] = array('neq', 8);
		$caseClient = M('CaseClient')->where($map)->field('id,name,detain_time,is_escape')->select();
		//当事人信息
		foreach ($caseClient as $key => &$value) {
			$value['detain_car_status'] = $value['detain_time'] == 0 ? '未扣车' : '已扣车';

			$map = array();
			$map['case_id'] = $id;
			$map['case_client_id'] = $value['id'];
			$map['name_id'] = 1;
			$map['status'] = 0;
			$unique = M('CaseClientDetain')->where($map)->find();
			$value['detain_licence_status'] = empty($unique) ? '未扣留驾驶证' : '已扣留驾驶证';
			$value['escape_status'] = $value['is_escape'] == 1 ? '逃逸' : '未逃逸';
		}
		//简易事故认定
		$map = array();
		$map['case_id'] = $id;
		$cognizanceInfo = M('CaseCognizanceSimple')->where($map)->find();
		$this->assign('cognizanceId', $cognizanceInfo['case_cognizance_id']);
		$this->assign('clientList', $caseClient);
		$this->assign('alarmList', $alarmList);
		$this->assign('info', $info);
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function edit() {
		// 案件编号
		$id = I('get.case_id', '', 'int');
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
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		$time = time();
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		if (empty($myBrigade['area_code'])) {
			$this->error('当前用户所在大队缺失行政区划代码');
		}
		$id = I('post.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		$accidentTime = strtotime(I('post.accident_time'));
		$accidentPlace = I('post.accident_place');
		$accidentName = I('post.accident_name');
		if ($accidentTime == '') {
			$this->error('请选择事故发生时间');
		}
		if ($accidentPlace == '') {
			$this->error('请选择事故发生地点');
		}
		if ($accidentName == '') {
			$this->error('请选择事故名称');
		}
		$map = array();
		$map['id'] = I('post.id', '', 'int');
		$map['accident_name'] = $accidentName;
		$map['accident_place'] = $accidentPlace;
		$map['accident_time'] = $accidentTime;
		$map['update_time'] = $time;
		$map['update_user_id'] = $this->my['id'];
		$model = M('Case');
		$model->startTrans();
		$id = $model->save($map);
		if (!$id) {
			$model->rollback();
			$this->error('数据保存失败');
		}
		$model->commit();
		$this->success('编辑成功');
	}

	/**
	 * 获取案件信息
	 */
	public function getInfo() {
		$myBrigade = $this->getMyBrigade();
		if (empty($myBrigade)) {
			$this->error('当前用户没有大队信息');
		}
		if (empty($myBrigade['area_code'])) {
			$this->error('当前用户所在大队缺失行政区划代码');
		}
		$id = I('post.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		$info['accident_time'] = date('Y-m-d H:i', $info['accident_time']);
		$this->success($info);
	}
}