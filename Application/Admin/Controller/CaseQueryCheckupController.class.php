<?php
namespace Admin\Controller;

/**
 * 案件查询---检验鉴定
 */
class CaseQueryCheckupController extends CommonController {

	/**
	 *新增检验鉴定  步骤三
	 */
	public function applyStepThree() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		//判断案件信息是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		$this->assign('caseData', $caseData);
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		//判断检验鉴定信息是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_checkup_id;
		$caseCheckupData = D('CaseCheckupClientView')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('未能读取有效检验鉴定信息');
		}
		//判断是否可以进行提请审核 并判断审核类型

		//查询该检验鉴定最新审批审批数据
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['cate'] = $newCate;
		$map['case_checkup_id'] = $caseCheckupData['id']; //custom.php check_type 检验鉴定
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->order('create_time desc')->find();
		//审批（审核）数据赋值
		$this->assign('caseCheckupReviewData', $caseCheckupReviewData);
		//审核类型 延期=0 超期=1 延期并超期=2
		$this->assign('newCate', '0');

		//检验鉴定数据赋值
		$this->assign('caseCheckupData', $caseCheckupData);
		//鉴定对象一级选项
		$case_checkup_obj_type = C('custom.case_checkup_obj_type');
		$this->assign('case_checkup_obj_type', $case_checkup_obj_type);

		//获取该案件办案人所在大队信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $caseData['create_user_id'];
		$caseDealUserData = D('UserView')->where($map)->find();
		if (!$caseDealUserData) {
			$this->error('办案人信息获取失败');
		}
		$this->assign('caseDealUserData', $caseDealUserData);

		//获取该案件下所有有效当事人的信息（交通方式不为‘不行、非道路交通事故当事人’）
		$allValidClient = $this->getValidClient($caseData['id']);
		$this->assign('allValidClient', $allValidClient);

		//读取检验鉴定委托信息
		$map = array();
		$map['is_del'] = 0;
		$map['is_first'] = 1;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$caseCheckupEntrustData = D('CaseCheckupEntrustOrgView')->where($map)->order('create_time desc')->find();
		$this->assign('caseCheckupEntrustData', $caseCheckupEntrustData);
		//读取检验鉴定报告信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['is_back'] = 0;
		$caseCheckupReportData = D('CaseCheckupReport')->where($map)->order('create_time desc')->select();
		//读取检验鉴定结果照片数量
		foreach ($caseCheckupReportData as $key => $value) {
			$caseCheckupReportData[$key]['pic_count'] = 0;
			$map = array();
			$map['ext_ida'] = $caseCheckupReportData[$key]['id'];
			$map['cate'] = 44; //cate值参照custom.php 中的 photo_cate44
			$map['case_id'] = $caseCheckupData['case_id'];
			$caseCheckupReportData[$key]['pic_count'] = D('CasePhotoView')->where($map)->count();
		}
		//已经退回的报告
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['is_back'] = 1;
		$caseCheckupReportBackData = D('CaseCheckupReport')->where($map)->order('create_time desc')->select();
		//读取检验鉴定结果照片数量
		foreach ($caseCheckupReportBackData as $key1 => $value1) {
			$caseCheckupReportBackData[$key1]['pic_count'] = 0;
			$map = array();
			$map['ext_ida'] = $caseCheckupReportBackData[$key1]['id'];
			$map['cate'] = 44; //cate值参照custom.php 中的 photo_cate44
			$map['case_id'] = $caseCheckupData['case_id'];
			$caseCheckupReportBackData[$key1]['pic_count'] = D('CasePhotoView')->where($map)->count();
		}
		//读取检验鉴告知信息 读取申请重新检验鉴定的部分
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['is_again'] = 1;
		$map['report_is_back'] = 0;
		$caseCheckupNoticeAgainData = D('CaseCheckupNoticeReportView')->where($map)->order('create_time desc')->select();

		$checkupAgain = 0;
		if ($caseCheckupNoticeAgainData) {
			$checkupAgain = 1;
		}
		$this->assign('caseCheckupReportData', $caseCheckupReportData);
		$this->assign('caseCheckupReportBackData', $caseCheckupReportBackData);
		$this->assign('caseCheckupNoticeAgainData', $caseCheckupNoticeAgainData);
		$this->assign('checkupAgain', $checkupAgain);

		//读取所有当事人及相关人员
		$allClientName = array();
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$allClientId = D('CaseClient')->where($map)->select();
		$allId = array();
		$allName = array();
		foreach ($allClientId as $key => $value) {
			$allId[$key][] = $allClientId[$key]['id'];
			$allName[$key][] = $allClientId[$key]['name'];

			//读取相关人 勿删
			$map = array();
			//$map['case_client_id'] = array('in', $allId);
			$map['case_client_id'] = $allClientId[$key]['id'];
			$allClientRelater = D('CaseClientRelater')->where($map)->select();
			foreach ($allClientRelater as $key1 => $value1) {
				$allId[$key][] = $allClientRelater[$key1]['id'];
				$allName[$key][] = $allClientRelater[$key1]['name'];
			}

		}
		$this->assign('allId', $allId);
		$this->assign('allName', $allName);
		// 渲染
		$this->display();
	}

	/**
	 *新增检验鉴定 查看详情
	 */
	public function detail() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		//判断案件信息是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		$this->assign('caseData', $caseData);
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		//判断检验鉴定信息是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_checkup_id;
		$caseCheckupData = D('CaseCheckupClientView')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('未能读取有效检验鉴定信息');
		}
		//判断是否可以进行提请审核 并判断审核类型

		//查询该检验鉴定最新审批审批数据
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['cate'] = $newCate;
		$map['case_checkup_id'] = $caseCheckupData['id']; //custom.php check_type 检验鉴定
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->order('create_time desc')->find();
		//审批（审核）数据赋值
		$this->assign('caseCheckupReviewData', $caseCheckupReviewData);
		//审核类型 延期=0 超期=1 延期并超期=2
		$this->assign('newCate', '0');

		//检验鉴定数据赋值
		$this->assign('caseCheckupData', $caseCheckupData);
		//鉴定对象一级选项
		$case_checkup_obj_type = C('custom.case_checkup_obj_type');
		$this->assign('case_checkup_obj_type', $case_checkup_obj_type);

		//获取该案件办案人所在大队信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $caseData['create_user_id'];
		$caseDealUserData = D('UserView')->where($map)->find();
		if (!$caseDealUserData) {
			$this->error('办案人信息获取失败');
		}
		$this->assign('caseDealUserData', $caseDealUserData);

		//获取该案件下所有有效当事人的信息（交通方式不为‘不行、非道路交通事故当事人’）
		$allValidClient = $this->getValidClient($caseData['id']);
		$this->assign('allValidClient', $allValidClient);

		//读取检验鉴定委托信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$caseCheckupEntrustData = D('CaseCheckupEntrustOrgView')->where($map)->order('create_time desc')->find();
		$this->assign('caseCheckupEntrustData', $caseCheckupEntrustData);

		//读取检验鉴定报告信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['is_back'] = 0;
		$caseCheckupReportData = D('CaseCheckupReport')->where($map)->order('create_time desc')->select();
		//读取检验鉴定结果照片数量
		foreach ($caseCheckupReportData as $key => $value) {
			$caseCheckupReportData[$key]['pic_count'] = 0;
			$map = array();
			$map['ext_ida'] = $caseCheckupReportData[$key]['id'];
			$map['cate'] = 44; //cate值参照custom.php 中的 photo_cate44
			$map['case_id'] = $caseCheckupData['case_id'];
			$caseCheckupReportData[$key]['pic_count'] = D('CasePhotoView')->where($map)->count();
		}
		//已经退回的报告
		$map = array();
		$map['is_del'] = 0;
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['is_back'] = 1;
		$caseCheckupReportBackData = D('CaseCheckupReport')->where($map)->order('create_time desc')->select();
		//读取检验鉴定结果照片数量
		foreach ($caseCheckupReportBackData as $key1 => $value1) {
			$caseCheckupReportBackData[$key1]['pic_count'] = 0;
			$map = array();
			$map['ext_ida'] = $caseCheckupReportBackData[$key1]['id'];
			$map['cate'] = 44; //cate值参照custom.php 中的 photo_cate44
			$map['case_id'] = $caseCheckupData['case_id'];
			$caseCheckupReportBackData[$key1]['pic_count'] = D('CasePhotoView')->where($map)->count();
		}
		$this->assign('caseCheckupReportData', $caseCheckupReportData);
		$this->assign('caseCheckupReportBackData', $caseCheckupReportBackData);

		//读取所有当事人及相关人员
		$allClientName = array();
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$allClientId = D('CaseClient')->where($map)->select();
		$allId = array();
		$allName = array();
		foreach ($allClientId as $key => $value) {
			$allId[] = $allClientId[$key]['id'];
			$allName[] = $allClientId[$key]['name'];
		}
		$map = array();
		$map['case_client_id'] = array('in', $allId);
		$allClientRelater = D('CaseClientRelater')->where($map)->select();
		foreach ($allClientRelater as $key1 => $value1) {
			$allName[] = $allClientRelater[$key1]['name'];
		}
		$this->assign('allId', $allId);
		$this->assign('allName', $allName);
		// 渲染
		$this->display();
	}

	//步骤二 三 当鉴定对象是车辆·人员时自动加载 子对象
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
		$allChildren = D('CaseCaseClientView')->where($map)->order('create_time desc')->select();
		return $allChildren;
	}

	public function getClientInfo() {
		//案件及当事人（当事人车辆）信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('post.id', '', "strip_tags");
		$client = D('CaseClient')->where($map)->find();
		$this->success($client);
	}
	//步骤二 存储委托信息cookie
	public function setCookie() {
		$entrustCookieData = $_POST;
		//setcookie("entrust_cookie_data", $entrustCookieData, time() + 36000);
		session("entrust_cookie_data", $entrustCookieData);
		$data = session("entrust_cookie_data");
		if (!$data) {
			$this->error('cookie存储失败');
		}
		$this->success($data);
	}

	//AJAX更改检验鉴定委托内容case_checkup_entrust表 提交
	public function entrustSetSubmitFinish() {
		$data['id'] = I('post.id', '', 'strip_tags');
		$data['update_time'] = time();
		if ($_POST['is_submit']) {
			$data['is_submit'] = I('post.is_submit', '', 'strip_tags');
		}
		if ($_POST['is_finish']) {
			$data['is_finish'] = I('post.is_finish', '', 'strip_tags');
		}
		$save = D('CaseCheckupEntrust')->save($data);
		if (!$save) {
			$this->error('设置失败');
		}
		$this->success('设置成功');
	}

	//获取 日期的 年 月 日 三个字段值
	public function getYearMonthDay($time) {

		$date = date('Ymd', $time);
		$date_year = substr($date, 0, 4);
		$date_month = substr($date, 4, 2) * 1;
		$date_day = substr($date, 6, 2) * 1;
		$date_info = array();
		$date_info['year'] = $date_year;
		$date_info['month'] = $date_month;
		$date_info['day'] = $date_day;
		return $date_info;
	}
	//查询并获取当前时间之后第N个工作日的数据
	public function getValidWorkday($date_info = '0', $duration = '0') {
		$map = array();
		$map['year'] = $date_info['year'];
		$map['month'] = $date_info['month'];
		$map['day'] = $date_info['day'];

		$todayData = D('Calendar')->where($map)->find();
		$map = array();
		$map['id'] = array('gt', $todayData['id']);
		$map['is_holidays'] = 0;
		$targetDays = D('Calendar')->where($map)->order('id asc')->limit($duration)->select();
		$targetKey = $duration - 1;
		if ($targetKey < 0) {
			$targetKey = 0;
		}
		return $targetDays[$targetKey];
	}

	/**
	 *新增检验鉴定 AJAX读取子对象类型字选项
	 */
	public function getTypeChild() {
		$type_id = I('post.type_id', '', 'strip_tags');
		$case_id = I('post.case_id', '', 'strip_tags');
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
		$allChildren['objc'] = D('CaseCaseClientView')->where($map)->order('create_time desc')->select();
		//鉴定类型
		$map = array();
		$map['is_del'] = 0;
		$map['checkup_org_obj'] = $type_id;
		$allChildren['checktype'] = D('CheckupOrgItem')->where($map)->order('create_time desc')->select();
		$this->success($allChildren);
	}
	/**
	 *新增检验鉴定 AJAX读取鉴定机构选项
	 */
	public function getOrgChild() {
		$org_type = I('post.org_type', '', 'strip_tags');
		$department = $this->getMyBrigade();
		$department_id = $department['id'];

		//鉴定类型
		$map = array();
		$map['is_del'] = 0;
		$map['checkup_org_item_id'] = $org_type;
		$allOrg = D('CheckupOrgItemAccessView')->where($map)->order('checkup_org_item_access_id desc')->select();
		$targetOrgId = array();
		foreach ($allOrg as $key => $val) {
			$targetOrgId[] = $allOrg[$key]['checkup_org_id'];
		}

		//
		$map = array();
		$map['is_del'] = 0;
		$map['department_id'] = $department_id;
		$map['checkuporg_id'] = array('in', $targetOrgId);
		$map['checkuporg_is_del'] = 0;
		$allOrgChildren = D('CheckupOrgDepartmentView')->where($map)->order('id desc')->group('checkup_org_id')->select();
		//echo D('CheckupOrgDepartmentView')->getLastSql();exit;
		$this->success($allOrgChildren);
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
		$this->display('CaseCheckup/applyStepThree/photoTable');
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

	/**
	 *短鉴定结果张数
	 */
	public function checkupResultPicNumber() {
		$cate = I('get.cate', '', 'int');
		$case_checkup_id = I('get.case_checkup_id', '', 'int');
		$case_checkup_report_id = I('get.case_checkup_report_id', '', 'int');
		$case_id = I('get.case_id', '', 'int');
		$this->assign('cate', $cate);
		$this->assign('case_checkup_id', $case_checkup_id);
		$this->assign('case_checkup_report_id', $case_checkup_report_id);
		$this->assign('case_id', $case_id);

		$lists = $this->getPhotoList($cate, $case_checkup_report_id, $case_id);
		$this->assign('lists', $lists);
		$this->display();

	}

	/**
	 *检验鉴定作废
	 */
	public function caseCheckupCancel() {
		$id = I('post.id', '', 'int');
		$data['id'] = $id;
		//查询是否处在提审状态
		$map = array();
		$map['case_checkup_id'] = $id;
		$map['status'] = 0;
		$reviewData = D('CaseCheckupReview')->where($map)->find();
		if ($reviewData) {
			$this->error('检验鉴定已提审,不能作废');
		}
		//查询是否存在有效鉴定报告
		$map = array();
		$map['case_checkup_id'] = $id;
		$map['is_back'] = 0;
		$reportData = D('CaseCheckupReport')->where($map)->find();
		if ($reportData) {
			$this->error('检验鉴定已出具鉴定结果,不能作废');
		}

		$data['is_cancel'] = 1;
		$data['cancel_reason'] = I('post.cancel_reason', '', 'strip_tags');
		$data['update_time'] = time();
		$save = D('CaseCheckup')->save($data);
		if (!$save) {
			$this->error('操作失败');
		}
		$this->success('操作成功');
	}
	/**
	 *检验鉴定作废状态查询
	 */
	public function caseCheckupCancelStatus() {
		$id = I('post.id', '', 'int');
		$data['id'] = $id;
		//查询是否处在提审状态
		$map = array();
		$map['case_checkup_id'] = $id;
		$map['status'] = 0;
		$reviewData = D('CaseCheckupReview')->where($map)->find();
		if ($reviewData) {
			$this->error('检验鉴定已提审,不能作废');
		}
		//查询是否存在有效鉴定报告
		$map = array();
		$map['case_checkup_id'] = $id;
		$map['is_back'] = 0;
		$reportData = D('CaseCheckupReport')->where($map)->find();
		if ($reportData) {
			$this->error('检验鉴定已出具鉴定结果,不能作废');
		}
		$this->success('操作成功');
	}

	/*
		 *Ajax 读取检验鉴定结果信息
	*/
	public function getReportDetail() {
		$id = I('post.id', '', 'strip_tags');
		$reportData = D('CaseCheckupReport')->getById($id);
		$reportData['finish_time_date'] = date('Y-m-d H:i', $reportData['finish_time']);
		$this->success($reportData);
	}

	/*
		 *Ajax 检验鉴定结果退回操作
	*/
	public function reportBack() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$data['id'] = I('post.id', '', 'strip_tags');
		$data['back_reason'] = I('post.back_reason', '', 'strip_tags');
		$data['update_time'] = time();
		$data['is_back'] = 1;
		$result = D('CaseCheckupReport')->save($data);
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$reportData = D('CaseCheckupReport')->getById($data['id']);
		if (!$reportData) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$caseCheckupData['id'] = $reportData['case_checkup_id'];
		$caseCheckupData['is_over'] = 0;
		$caseCheckupData['update_time'] = time();
		$result = D('CaseCheckup')->save($caseCheckupData);
		//判断是否生成重新检验鉴定数据
		$map = array();
		$map['pid'] = $reportData['case_checkup_id'];
		$map['is_cancel'] = 0;
		$reCheckupData = D('CaseCheckup')->where($map)->find();
		if ($reCheckupData) {
			$Model->rollback();
			$this->error('已经申请有效重新检验鉴定,操作失败');
		}

		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$this->success('退回成功');
	}

/*
 *Ajax 检验鉴定结果退回操作--状态查询
 */
	public function reportBackStatus() {
		$data['id'] = I('post.id', '', 'strip_tags');
		$reportData = D('CaseCheckupReport')->getById($data['id']);
		if (!$reportData) {
			$this->error('鉴定报告读取失败');
		}

		//判断是否生成重新检验鉴定数据
		$map = array();
		$map['pid'] = $reportData['case_checkup_id'];
		$map['is_cancel'] = 0;
		$reCheckupData = D('CaseCheckup')->where($map)->find();
		if ($reCheckupData) {
			$this->error('已经申请有效重新检验鉴定,操作失败');
		}
		$this->success('可执行退回');

	}

}
