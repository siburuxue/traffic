<?php
namespace Admin\Controller;

/**
 * 调节
 */
class CaseMediateHandleController extends CaseCommonController {

	/**
	 * 调解申请画面加载
	 */
	public function index() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['traffic_type'] = array('neq', 8);
		$clientList = M('CaseClient')->field('id,name,idno')->where($map)->select();
		$this->assign('clientList', $clientList);
		$this->assign('now', date('Y-m-d H:i'));
		$this->display();
	}

	/**
	 * 调解申请列表
	 */
	public function applyTable() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$model = D('CaseMediateApplyView');
//        $count = $model->count('CaseMediateApply.id');
		//        $page = new Page($count, 15);
		//        $list = $model->order('CaseMediateApply.case_mediate_accept_id,CaseMediateApply.id desc')->limit($page->firstrow . ',' . $page->rows)->select();
		$list = $model->where($map)->order('CaseMediateApply.case_mediate_accept_id,CaseMediateApply.id desc')->select();
		foreach ($list as $key => $val) {
			$photoList = parent::getPhotoList(53, $val['id']);
			$list[$key]['num'] = count($photoList);
			if ($val['user_type'] == '1') {
				$relaterInfo = M('CaseClientRelater')->getById($val['case_client_id']);
				$list[$key]['name'] = $relaterInfo['name'];
			}
		}
		$this->assign('list', $list);
		// 分页信息
		//        $pageInfo = array(
		//            'totalrows' => $count,
		//            'totalpage' => $page->totalpages,
		//            'nowpage' => $page->nowpage,
		//        );
		//        $this->assign('page', $pageInfo);
		$this->display('CaseMediateHandle/index/applyTable');
	}

	/**
	 * 调解列表
	 */
	public function mediateTable() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$status = array('未调解', '已调解', '已终结');
		$model = D('CaseMediateAcceptView');
//        $count = $model->count('CaseMediateAccept.id');
		//        $page = new Page($count, 15);
		//        $list = $model->order('CaseMediateAccept.id desc')->limit($page->firstrow . ',' . $page->rows)->select();
		$list = $model->where($map)->order('CaseMediateAccept.id desc')->select();
		foreach ($list as $key => $val) {
			$map = array();
			$map['case_mediate_accept_id'] = $val['id'];
			$CaseMediateAcceptRs = M('CaseMediateApply')->where($map)->select();
			$array = array();
			foreach ($CaseMediateAcceptRs as $k => $v) {
				if ($v['user_type'] == '0') {
					$clientRs = M('CaseClient')->getById($v['case_client_id']);
				} else {
					$clientRs = M('CaseClientRelater')->getById($v['case_client_id']);
				}
				array_push($array, $clientRs['name']);
			}
			$list[$key]['names'] = implode(',', $array);
			$list[$key]['statusName'] = $status[(int) $val['status']];
			$map = array();
			$map['case_id'] = $this->case['id'];
			$map['case_mediate_accept_id'] = $val['id'];
			$noticeList = M('CaseMediateNotice')->where($map)->select();
			$list[$key]['notice'] = count($noticeList);
		}
		$this->assign('list', $list);
		// 分页信息
		//        $pageInfo = array(
		//            'totalrows' => $count,
		//            'totalpage' => $page->totalpages,
		//            'nowpage' => $page->nowpage,
		//        );
		//        $this->assign('page1', $pageInfo);
		$this->display('CaseMediateHandle/index/mediateTable');
	}

	/**
	 * 新增调解申请
	 */
	public function addMediateApply() {
		$model = D('CaseMediateApply');
		$data = $model->create($_POST);
		$model->startTrans();
		if ($data === false) {
			$model->rollback();
			$this->error($model->getError());
		}
//        if(strpos($data['case_client_id'],"client") !== false){
		//            $client = M('CaseClient')->getById(str_replace('client','',$data['case_client_id']));
		//            $data['case_client_id'] = str_replace('client','',$data['case_client_id']);
		//            $data['user_type'] = 0;
		//        }else{
		//            $client= M('CaseClientRelater')->getById(str_replace('relater','',$data['case_client_id']));
		//            $data['case_client_id'] = str_replace('relater','',$data['case_client_id']);
		//            $data['user_type'] = 1;
		//        }
		$client = M('CaseClient')->getById($data['case_client_id']);
		$data['user_type'] = 0;
		$id = $model->add($data);
		if (!$id) {
			$model->rollback();
			$this->error('保存失败');
		}
		$map = array();
		$map['id'] = $this->case['id'];
		$map['mediate_status'] = 1;
		$rs = D('Case')->save($map);
		if ($rs === false) {
			$model->rollback();
			$this->error('保存失败');
		}
		$map = array();
		$map['id'] = $this->case['id'];
		$map['mediate_complete_status'] = 0;
		$map['update_time'] = time();
		$map['update_user_id'] = $this->my['id'];
		$rs = M('Case')->save($map);
		if ($rs === false) {
			$model->rollback();
			$this->error('数据保存失败');
		}
		//操作日志
		$content = $client['name'] . "在" . date('Y-m-d H:i') . "申请调解";
		$this->saveCaseLog($this->case['id'], $content);
		$model->commit();
		$this->success('保存成功');
	}

	/**
	 * 调解申请书加载
	 */
	public function photoTable() {
		$this->assign('id', I('get.id'));
		$this->display();
	}

	/**
	 * 获取图片列表
	 */
	public function photoList() {
		$cate = I('post.cate', '', 'int');
		$id = I('post.id', '', 'int');
		$list = parent::getPhotoList($cate, $id);
		$this->assign('list', $list);
		$this->display('CaseMediateHandle/index/photoTable');
	}

	/**
	 * 调解
	 */
	public function mediate() {
		$map = array();
		$map['case_id'] = I('post.case_id');
		$model = D('CaseMediateAccept');
		$model->startTrans();
		$data = $model->create($map);
		$id = $model->add($data);
		if (!$id) {
			$model->rollback();
			$this->error('保存失败');
		}
		$list = I('post.ids');
		foreach ($list as $key => $val) {
			$val['case_mediate_accept_id'] = $id;
			$val['apply_status'] = 2;
			$rs = D('CaseMediateApply')->save($val);
			if ($rs === false) {
				$model->rollback();
				$this->error('保存失败');
			}
		}
		$model->commit();
		$this->success('保存成功');
	}

	/**
	 * 删除调解
	 */
	public function delete() {
		$id = I('post.id');
		$map = array();
		$map['id'] = $id;
		$model = M('CaseMediateAccept');
		$result = $model->where($map)->delete();
		if ($result === false) {
			$model->rollback();
			$this->error('操作失败');
		}
		$map = array();
		$map['case_mediate_accept_id'] = $id;
		$rs = D('CaseMediateApply')->where($map)->save(array('case_mediate_accept_id' => 0, 'apply_status' => 0));
		if ($rs === false) {
			$model->rollback();
			$this->error('操作失败');
		}
		$model->commit();
		$this->success('操作成功');
	}

	/**
	 * 不调节画面加载
	 */
	public function unMediateIndex() {
		//调解申请主键
		$caseMediateApplyId = I('get.id', '', 'int');
		$caseId = $this->case['id'];
		//交通方式
		$traffic_type = get_custom_config('traffic_type');
		//伤害程度
		$hurt_type = get_custom_config('hurt_type');
		//调解申请信息
		$applyInfo = M('CaseMediateApply')->getById($caseMediateApplyId);
		//获取当事人信息
		$clientInfo = array();
		$client = M('CaseClient')->getbyId($applyInfo['case_client_id']);
		$clientInfo['name'] = $client['name'];
		$clientInfo['traffic_type'] = $traffic_type[$client['traffic_type']];
		$clientInfo['hurt_type'] = $hurt_type[$client['hurt_type']];
		//获取当事人列表
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['traffic_type'] = array('neq', 8);
		$clientList = M('CaseClient')->where($map)->select();
		//获取当事人相关人信息
		$map = array();
		$map['case_client_id'] = $applyInfo['case_client_id'];
		$relaterList = M('CaseClientRelater')->where($map)->select();
		//获取不调解信息
		$map = array();
		$map['case_mediate_apply_id'] = $caseMediateApplyId;
		$map['case_id'] = $caseId;
		$info = M('CaseMediateRefuse')->where($map)->find();
		//获取当前大队办案人信息
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();

		$this->assign('info', $info);
		$this->assign('userList', $userList);
		$this->assign('userId', $this->my['id']);
		$this->assign('clientInfo', $clientInfo);
		$this->assign('clientList', $clientList);
		$this->assign('relaterList', $relaterList);
		$this->assign('caseMediateApplyId', $caseMediateApplyId);
		$this->assign('clientId', $applyInfo['case_client_id']);
		$this->display();
	}

	/**
	 * 保存不调解通知
	 */
	public function saveRefuse() {
		$model = D('CaseMediateRefuse');
		$model->startTrans();
		$data = $model->create();
		if ($data == false) {
			$model->rollback();
			$this->error($model->getError());
		}
		if (I('post.id', '', 'int') === '') {
			$result = $model->add($data);
		} else {
			$result = $model->save($data);
		}
		if ($result === false) {
			$model->rollback();
			$this->error('保存失败');
		}
		$map = array();
		$map['id'] = I('post.case_mediate_apply_id');
		$map['apply_status'] = 1;
		$rs = D('CaseMediateApply')->save($map);
		if ($rs === false) {
			$model->rollback();
			$this->error('保存失败');
		}
		//调解记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['status'] = array("neq", 2);
		$acceptList = M('CaseMediateAccept')->where($map)->select();
		//不调节记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['apply_status'] = 0;
		$applyList = M('CaseMediateApply')->where($map)->select();
		if (count($acceptList) == 0 && count($applyList) === 0) {
			$map = array();
			$map['id'] = $this->case['id'];
			$map['mediate_complete_status'] = 1;
			$map['update_time'] = time();
			$map['update_user_id'] = $this->my['id'];
			$rs = M('Case')->save($map);
			if ($rs === false) {
				$model->rollback();
				$this->error('数据保存失败');
			}
		}
		//操作日志
		$client = M('CaseClient')->getById(I('post.case_client_id'));
		$content = date('Y-m-d H:i') . "不予调解通知书送达" . $client['name'];
		$this->saveCaseLog($this->case['id'], $content);
		$model->commit();
		$this->success('保存成功');
	}

	/**
	 * 调解通知画面加载
	 */
	public function mediateIndex() {
		//调解表主键
		$caseMediateAcceptId = I('get.id', '', 'int');
		//交通方式
		$traffic_type = get_custom_config('traffic_type');
		//伤害程度
		$hurt_type = get_custom_config('hurt_type');
		//当事人列表
		$map = array();
		$map['case_mediate_accept_id'] = $caseMediateAcceptId;
		$applyList = M('CaseMediateApply')->where($map)->select();
		$mediateClients = array();
		foreach ($applyList as $key => $val) {
			$clientInfo = M('CaseClient')->field('id,name,traffic_type,hurt_type,car_no')->getById($val['case_client_id']);
			$clientInfo['traffic_type'] = $traffic_type[$clientInfo['traffic_type']];
			$clientInfo['hurt_type'] = $hurt_type[$clientInfo['hurt_type']];
			array_push($mediateClients, $clientInfo);
		}
		//获取当前大队办案人信息
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();
		$this->assign('userList', $userList);
		$this->assign('userId', $this->my['id']);
		$this->assign('mediateClients', $mediateClients);
		$this->assign('caseMediateAcceptId', $caseMediateAcceptId);
		$this->display();
	}

	/**
	 * 获取当事人调解通知信息
	 */
	public function getClientInfo() {
		$clientId = I('post.client_id');
		$caseId = $this->case['id'];
		$CaseMediateAcceptId = I('post.case_mediate_accept_id');
		$map = array();
		$map['case_client_id'] = $clientId;
		$map['case_mediate_accept_id'] = $CaseMediateAcceptId;
		$map['case_id'] = $caseId;
		$caseMediateNoticeInfo = M('CaseMediateNotice')->where($map)->find();
		$map = array();
		$map['case_client_id'] = $clientId;
		$relaterList = M('CaseClientRelater')->where($map)->select();
		$clientInfo = M('CaseClient')->getById($clientId);
		$this->success(array('info' => $caseMediateNoticeInfo, 'relaterList' => $relaterList));
	}

	/**
	 * 保存调解通知
	 */
	public function saveNotice() {
		$model = D('CaseMediateNotice');
		$model->startTrans();
		$data = $model->create();
		if ($data == false) {
			$model->rollback();
			$this->error($model->getError());
		}
		if (I('post.id', '', 'int') === '') {
			$result = $model->add($data);
		} else {
			$result = $model->save($data);
		}
		if ($result === false) {
			$model->rollback();
			$this->error('保存失败');
		}
		//操作日志
		$client = M('CaseClient')->getById(I('post.case_client_id'));
		$content = date('Y-m-d H:i') . "调解通知书送达" . $client['name'];
		$this->saveCaseLog($this->case['id'], $content);
		$model->commit();
		$this->success('保存成功');
	}

	/**
	 * 调解记录列表
	 */
	public function mediateRecord() {
		//调解表主键
		$caseMediateAcceptId = I('get.id', '', 'int');
		//当事人列表
		$map = array();
		$map['case_mediate_accept_id'] = $caseMediateAcceptId;
		$applyList = M('CaseMediateApply')->where($map)->select();
		$clientList = array();

		foreach ($applyList as $key => $val) {
			$clientInfo = M('CaseClient')->getById($val['case_client_id']);
			array_push($clientList, $clientInfo);
		}
		//获取当前大队办案人信息
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();
		//画面详细信息
		$info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
		$this->assign('info', $info);
		$this->assign('userList', $userList);
		$this->assign('userId', $this->my['id']);
		$this->assign('clientList', $clientList);
		$this->display();
	}

	/**
	 * 保存调解记录
	 */
	public function saveAcceptInfo() {
		if (I('post.start_time') == '') {
			$this->error('请选择开始时间');
		}
		if (I('post.end_time') == '') {
			$this->error('请选择结束时间');
		}
		if (I('post.place') == '') {
			$this->error('请输入调解地点');
		}
		if (I('post.police') == '') {
			$this->error('请选择交通警察');
		}
		if (I('post.content') == '') {
			$this->error('请选择调解记录编辑内容');
		}
		$model = D('CaseMediateAccept');
		$data = $model->create();
		$rs = $model->save($data);
		if ($rs === false) {
			$this->error('保存失败');
		}
		$this->success('保存成功');
	}

	/**
	 * 道路交通事故损害赔偿调解书加载
	 */
	public function mediateBook() {
		//调解表主键
		$caseMediateAcceptId = I('get.id', '', 'int');
		//案件信息
		$caseInfo = M('Case')->getById($this->case['id']);
		//当事人列表
		$map = array();
		$map['case_mediate_accept_id'] = $caseMediateAcceptId;
		$applyList = M('CaseMediateApply')->where($map)->select();
		$clientList = array();

		foreach ($applyList as $key => $val) {
			$clientInfo = M('CaseClient')->getById($val['case_client_id']);
			array_push($clientList, $clientInfo);
		}
		//画面详细信息
		$info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
		$this->assign('info', $info);
		$this->assign('caseInfo', $caseInfo);
		$this->assign('clientList', $clientList);
		$this->display();
	}

	/**
	 * 保存道路交通事故损害赔偿调解书
	 */
	public function saveMediateBook() {
	    if(I('post.result') == ''){
            $this->error('请输入道路交通事故损害赔偿调解书内容');
        }
		$model = D('CaseMediateAccept');
		$data = $model->create();
		$rs = $model->save($data);
		if ($rs === false) {
			$this->error('保存失败');
		}
		//调解记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['status'] = array("neq", 2);
		$acceptList = M('CaseMediateAccept')->where($map)->select();
		//不调节记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['apply_status'] = 0;
		$applyList = M('CaseMediateApply')->where($map)->select();
		if (count($acceptList) == 0 && count($applyList) === 0) {
			$map = array();
			$map['id'] = $this->case['id'];
			$map['mediate_complete_status'] = 1;
			$map['update_time'] = time();
			$map['update_user_id'] = $this->my['id'];
			$rs = M('Case')->save($map);
			if ($rs === false) {
				$model->rollback();
				$this->error('数据保存失败');
			}
		}
		//操作日志
		$content = date('Y-m-d H:i') . "进行道路交通事故损害赔偿调解，制作道路交通事故损害赔偿调解书，送达调解申请人";
		$this->saveCaseLog($this->case['id'], $content);
		$this->success('保存成功');
	}

	/**
	 * 道路交通事故损害赔偿调解终结书加载
	 */
	public function mediateFinishBook() {
		//调解表主键
		$caseMediateAcceptId = I('get.id', '', 'int');
		//案件信息
		$caseInfo = M('Case')->getById($this->case['id']);
		//当事人列表
		$map = array();
		$map['case_mediate_accept_id'] = $caseMediateAcceptId;
		$applyList = M('CaseMediateApply')->where($map)->select();
		$clientList = array();
		foreach ($applyList as $key => $val) {
			$clientInfo = M('CaseClient')->getById($val['case_client_id']);
			array_push($clientList, $clientInfo['name']);
		}
		//画面详细信息
		$info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
		$this->assign('info', $info);
		$this->assign('caseInfo', $caseInfo);
		$this->assign('clientNames', implode(',', $clientList));
		$this->display();
	}

	/**
	 * 保存道路交通事故损害赔偿调解终结书
	 */
	public function saveMediateFinishBook() {
        if(I('post.final') == ''){
            $this->error('请输入道路交通事故损害赔偿终结书内容');
        }
        if(I('post.final_time') == ''){
            $this->error('请选择终结时间');
        }
		$model = D('CaseMediateAccept');
		$data = $model->create();
		$rs = $model->save($data);
		if ($rs === false) {
			$this->error('保存失败');
		}
		//调解记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['status'] = array("neq", 2);
		$acceptList = M('CaseMediateAccept')->where($map)->select();
		//不调节记录
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['apply_status'] = 0;
		$applyList = M('CaseMediateApply')->where($map)->select();
		if (count($acceptList) == 0 && count($applyList) === 0) {
			$map = array();
			$map['id'] = $this->case['id'];
			$map['mediate_complete_status'] = 1;
			$map['update_time'] = time();
			$map['update_user_id'] = $this->my['id'];
			$rs = M('Case')->save($map);
			if ($rs === false) {
				$model->rollback();
				$this->error('数据保存失败');
			}
		}
		//操作日志
		$content = "在" . date('Y-m-d H:i') . "进行道路交通事故损害赔偿调解，制作道路交通事故损害赔偿调解终结书，送达调解申请人";
		$this->saveCaseLog($this->case['id'], $content);
		$this->success('保存成功');
	}
}
