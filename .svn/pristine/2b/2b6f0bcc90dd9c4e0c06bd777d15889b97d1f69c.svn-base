<?php
namespace Admin\Controller;

/**
 * 检验鉴定-告知
 */
class CaseCheckupNoticeController extends CommonController {

	/**
	 * 新增界面
	 */
	public function add() {

		$nowtime = time() + 300;
		//获取交通方式 伤害程度
		$traffic_type = get_custom_config('traffic_type');
		$hurt_type = get_custom_config('hurt_type');

		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		$case_checkup_report_id = I('get.case_checkup_report_id', '', 'strip_tags');
		//获取检验鉴定信息
		$map = array();
		$map['id'] = $case_checkup_id;
		$caseCheckupData = D('CaseCheckup')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('检验鉴定信息获取失败');
		}
		//获取当事人列表
		$map2 = array();
		$map2['case_id'] = $case_id;
		$map['is_del'] = 0;
		$traffic_type = C('custom.traffic_type');
		//剔除 当事人的交通方式为步行 非道路交通事故当事人
		//unset($traffic_type[1]);
		unset($traffic_type[8]);
		$traffic_type_array = array();
		foreach ($traffic_type as $key => $val) {
			$traffic_type_array[] = $key;
		}
		$map2['traffic_type'] = array('in', $traffic_type_array);
		$clientList = M('CaseClient')->where($map2)->order('id desc')->select();
		$traffic_type = C('custom.traffic_type');
		foreach ($clientList as $key => $value) {
			$clientList[$key]['traffic_type'] = $traffic_type[(int) $clientList[$key]['traffic_type']];
			$clientList[$key]['hurt_type'] = $hurt_type[(int) $clientList[$key]['hurt_type']];
		}

		//获取大队ID
		$myBrigade = $this->getMyBrigade();
		//查阅是否已经生成告知
		$map = array();
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $case_checkup_id;
		$map['case_checkup_report_id'] = $case_checkup_report_id;
		$map['is_del'] = 0;
		//获取告知信息
		$noticeDataAll = M('CaseCheckupNotice')->where($map)->order('case_client_id desc')->select();
		$noticeData = $noticeDataAll[0];
		if ($noticeData) {
			$this->redirect(U('edit', array('case_id' => $case_id, 'case_checkup_id' => $case_checkup_id, 'case_checkup_report_id' => $case_checkup_report_id, 'case_checkup_notice_id' => $noticeData['id'])));
			exit;
		}

		$this->assign('clientList', $clientList);
		$this->assign('nowtime', $nowtime);
		$this->assign('myBrigade', $myBrigade);
		$this->assign('caseCheckupData', $caseCheckupData);
		$this->assign('case_checkup_report_id', $case_checkup_report_id);

		// 渲染
		$this->display();
	}
	//ajax 读取告知当事人及相关人员
	public function getClientRelater() {

		$case_client_id = I('id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['case_client_id'] = $case_client_id;
		$list = M('CaseClientRelater')->where($map)->select();
		// if ($list) {
		// 	$names = "、";
		// 	foreach ($list as $key => $value) {
		// 		$names .= $list[$key]['name'] . "、";
		// 	}
		// }
		//$names = mb_substr($names, 0, -1, 'utf8');
		if ($list) {
			$names = array();
			foreach ($list as $key => $value) {
				$names[] = $list[$key]['name'];
			}
		}
		$this->success($names);
	}
	//ajax读取告知信息
	public function getNoticeInfo() {

		$case_id = I('post.case_id', '', 'strip_tags');
		$case_checkup_id = I('post.case_checkup_id', '', 'strip_tags');
		$case_client_id = I('post.case_client_id', '', 'strip_tags');
		$case_checkup_report_id = I('post.case_checkup_report_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['case_client_id'] = $case_client_id;
		$map['case_checkup_id'] = $case_checkup_id;
		$map['case_client_id'] = $case_client_id;
		$map['case_checkup_report_id'] = $case_checkup_report_id;
		$data = M('CaseCheckupNotice')->where($map)->find();
		$data['notice_time_date'] = date('Y-m-d H:i', $data['notice_time']);
		if ($data['case_client_id'] == $case_client_id && $data['case_checkup_report_id'] == $case_checkup_report_id && $data['case_checkup_id'] == $case_checkup_id) {
			$this->success($data);
		} else {
			$this->error('无有效告知信息');
		}

	}

	/**
	 * 新增
	 */
	public function insert() {
		// 实例化模型
		$Model = D('CaseCheckupNotice');
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['notice_time'] = strtotime($data['notice_time']);
		$Model->startTrans();
		$id = $Model->add($data);
		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据添加失败');
		}
		// 成功
		$Model->commit();
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		$case_checkup_report_id = I('get.case_checkup_report_id', '', 'strip_tags');
		$url = U('edit', array('case_id' => $data['case_id'], 'case_checkup_id' => $data['case_checkup_id'], 'case_checkup_report_id' => $data['case_checkup_report_id'], 'case_checkup_notice_id' => $id));
		$this->success('新增成功', $url);
	}

	/**
	 * 编辑界面
	 */
	public function edit() {

		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		$case_checkup_report_id = I('get.case_checkup_report_id', '', 'strip_tags');
		$case_checkup_notice_id = I('get.case_checkup_notice_id', '', 'strip_tags');

		$nowtime = time() + 300;
		//获取交通方式 伤害程度
		$traffic_type = get_custom_config('traffic_type');
		$hurt_type = get_custom_config('hurt_type');

		//获取检验鉴定信息
		$map = array();
		$map['id'] = $case_checkup_id;
		$caseCheckupData = D('CaseCheckup')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('检验鉴定信息获取失败');
		}

		//获取当事人列表
		$map2 = array();
		$map2['case_id'] = $case_id;
		$map['is_del'] = 0;
		$traffic_type = C('custom.traffic_type');
		//剔除 当事人的交通方式为步行 非道路交通事故当事人
		//unset($traffic_type[1]);
		unset($traffic_type[8]);
		$traffic_type_array = array();
		foreach ($traffic_type as $key => $val) {
			$traffic_type_array[] = $key;
		}
		$map2['traffic_type'] = array('in', $traffic_type_array);
		$clientList = M('CaseClient')->where($map2)->order('id desc')->select();
		$traffic_type = C('custom.traffic_type');
		foreach ($clientList as $key => $value) {
			$clientList[$key]['traffic_type'] = $traffic_type[(int) $clientList[$key]['traffic_type']];
			$clientList[$key]['hurt_type'] = $hurt_type[(int) $clientList[$key]['hurt_type']];
		}
		//获取大队ID
		$myBrigade = $this->getMyBrigade();
		//验证告知信息
		$map = array();
		$map['id'] = $case_checkup_notice_id;
		$map['is_del'] = 0;
		//获取告知信息
		$noticeData = M('CaseCheckupNotice')->where($map)->find();
		if (!$noticeData) {
			$this->error('告知信息获取失败');
		}

		$this->assign('clientList', $clientList);
		$this->assign('nowtime', $nowtime);
		$this->assign('myBrigade', $myBrigade);
		$this->assign('noticeData', $noticeData);
		$this->assign('caseCheckupData', $caseCheckupData);
		$this->assign('case_checkup_report_id', $case_checkup_report_id);

		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
		// 实例化模型
		$Model = D('CaseCheckupNotice');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['notice_time'] = strtotime($data['notice_time']);
		$Model->startTrans();

		$id = $Model->save($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 成功
		$Model->commit();
		$url = U('edit', array('case_id' => $data['case_id'], 'case_checkup_id' => $data['case_checkup_id'], 'case_checkup_report_id' => $data['case_checkup_report_id'], 'case_checkup_notice_id' => $data['id']));
		$this->success('更新成功', $url);
	}

/**
 * 加载图片
 */
	public function photoList() {
		$cate = I('post.cate', '', 'int');
		$id = I('post.id', '', 'int');
		$case_id = I('post.case_id', '', 'int');
		$lists = $this->getPhotoList($cate, $id, $case_id);
		$this->assign('lists', $lists);
		$this->display('CaseCheckupNotice/add/photoTable');
	}

	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	protected function getPhotoList($cate = 0, $itemId = 0, $case_id = 0) {

		$map = array();
		$map['cate'] = $cate;
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$map['ext_ida'] = $itemId;
		$list = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);

		return $list;
	}

}
