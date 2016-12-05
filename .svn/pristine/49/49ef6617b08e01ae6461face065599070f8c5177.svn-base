<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 检验鉴定
 */
class ReCaseCheckupController extends CommonController {

	/**
	 * 首页界面
	 */
	public function index() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$this->assign('case_id', $case_id);
		// 渲染
		$this->display();
	}

	/**
	 * 主页表格界面
	 */
	public function indexTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$is_cancel = I('post.is_cancel', '', 'strip_tags');
		$map['is_cancel'] = $is_cancel;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');

		// 列表信息
		$Model = D('CaseCheckupClientView');
		$count = $Model->where($map)->count('distinct CaseCheckup.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseCheckup.id')->select();
		//查询检验鉴定委托信息
		$map = array();
		$map['is_del'] = 0;
		$map['is_first'] = 1;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$entrust_list = M('CaseCheckupEntrust')->where($map)->select();

		//查询检验鉴定报告信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$report_list = M('CaseCheckupReport')->where($map)->select();

		//查询检验鉴定相关图片资料
		$map = array();
		$map['case_id'] = I('post.case_id', '', 'strip_tags');
		$map['cate'] = array('in', '39,40,41,42,43,44');
		$allPic = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => $val) {
			$case_checkup_id = $list[$key]['id'];
			//添加委托信息
			foreach ($entrust_list as $key1 => $val1) {
				if ($entrust_list[$key1]['case_checkup_id'] == $case_checkup_id) {
					$list[$key]['entrust'] = $entrust_list[$key1];
				}
			}
			//添加报告信息
			foreach ($report_list as $key2 => $val2) {
				if ($report_list[$key2]['case_checkup_id'] == $case_checkup_id) {
					$list[$key]['report'] = $report_list[$key2];
					if ($report_list[$key2]['is_back'] == 1) {
						$list[$key]['report_back'] = $report_list[$key2];
					}
				}

			}

			//添加 检验鉴定委托书39 检验鉴定结果报告44 照片张数 （ 照片类型 cate 参照custom.php 中photo_type 读取）
			//检验鉴定委托书初始化
			$list[$key]['pic_count1'] = 0;
			//检验鉴定结果报告初始化
			$list[$key]['pic_count2'] = 0;
			foreach ($allPic as $key2 => $val2) {
				if ($allPic[$key2]['cate'] == 39 && $allPic[$key2]['ext_ida'] == $case_checkup_id) {
					$list[$key]['pic_count1']++;
				}
				if ($allPic[$key2]['cate'] == 44 && $allPic[$key2]['ext_ida'] == $case_checkup_id) {
					$list[$key]['pic_count2']++;
				}

			}

		}
		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		//  渲染
		if ($is_cancel == 1) {
			$this->display('CaseCheckup/index/tableCancel');
		} else {
			$this->display('CaseCheckup/index/table');
		}
	}

	/**
	 *重新检验鉴定 申请界面
	 */
	public function applyStepTop() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id_pre = I('get.case_checkup_id_pre', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		$url_step = 0;
		//查询是否已经提请重新检验鉴定
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['pid'] = $case_checkup_id_pre;
		$map['is_cancel'] = 0;
		$caseCheckupDataRe = D('CaseCheckup')->where($map)->find();
		//查询重新检验鉴定提请记录
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $caseCheckupDataRe['id'];
		$caseCheckupAgainData = D('CaseCheckupAgain')->where($map)->find();
		//查询重新检验鉴定提请审批记录
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $caseCheckupDataRe['id'];
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->find();
		//查询重新检验鉴定提请审批领导审核记录
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['item_id'] = $caseCheckupDataRe['id'];
		$caseCheckDataRe = D('CaseCheck')->where($map)->find();

		if ($caseCheckupDataRe && $caseCheckupDataRe['pid'] = $case_checkup_id_pre) {
			$url_step = 1;
		}
		if ($caseCheckupDataRe['finish_time'] != '0' && !empty($caseCheckupDataRe) && !empty($caseCheckupDataRe['finish_time'])) {
			$url_step = 2;
		}

		//查询是否已经填写委托信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $caseCheckupDataRe['id'];
		$caseCheckupEntrustDataRe = D('CaseCheckupEntrust')->where($map)->find();
		if ($caseCheckupEntrustDataRe && $caseCheckupEntrustDataRe['case_checkup_id'] = $caseCheckupDataRe['id']) {
			$url_step = 3;
		}

		if ($url_step == 1) {
			$url = U('ReCaseCheckup/applyStepOne', array('case_id' => $case_id, 'case_checkup_id' => $caseCheckupDataRe['id'], 'again_id' => $caseCheckupAgainData['id'], 'review_id' => $caseCheckupReviewData['id'], 'recheck_id' => $caseCheckDataRe['id']));
			header("Location:$url");
		}
		if ($url_step == 2) {
			$url = U('ReCaseCheckup/applyStepTwo', array('case_id' => $case_id, 'case_checkup_id' => $caseCheckupDataRe['id'], 'again_id' => $caseCheckupAgainData['id'], 'review_id' => $caseCheckupReviewData['id'], 'recheck_id' => $caseCheckDataRe['id']));
			header("Location:$url");
		}
		if ($url_step == 3) {
			$url = U('ReCaseCheckup/applyStepThree', array('case_id' => $case_id, 'case_checkup_id' => $caseCheckupDataRe['id'], 'again_id' => $caseCheckupAgainData['id'], 'review_id' => $caseCheckupReviewData['id'], 'recheck_id' => $caseCheckDataRe['id']));
			header("Location:$url");
		}

		$this->assign('case_id', $case_id);
		$this->assign('caseData', $caseData);
		$this->assign('case_checkup_id_pre', $case_checkup_id_pre);

		//读取所有当事人及相关人员
		$allClientRelaterData = $this->getAllClientRelater($case_id);
		$this->assign('allClientRelaterData', $allClientRelaterData);
		//读取有效审核人
		$list = $this->getCheckUserList("case_checkup_check_again_0");
		$this->assign('list', $list);

		// 渲染
		$this->display();
	}
	/**
	 *重新检验鉴定 执行
	 */
	public function againInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();

		//查询上一次检验鉴定信息
		$case_checkup_id = I('post.case_checkup_id', '', 'strip_tags');
		$caseCheckupData = D('CaseCheckup')->getById($case_checkup_id);
		if ($caseCheckupData['pid'] != 0 || $caseCheckupData['id'] != $case_checkup_id) {
			$Model->rollback();
			$this->error('上一次检验鉴定信息获取失败');
		}
		$newCheckupData = $caseCheckupData;
		unset($newCheckupData['id']);
		$newCheckupData['pid'] = $caseCheckupData['id'];
		$newCheckupData['update_time'] = time();
		$newCheckupData['create_time'] = time();
		$newCheckupData['create_user_id'] = $this->my['id'];
		$newCheckupData['update_user_id'] = $this->my['id'];
		$newCheckupData['finish_time'] = 0;
		$newCheckupData['is_delay'] = 0;
		$newCheckupData['delay_checked'] = 0;
		$newCheckupData['is_out'] = 0;
		$newCheckupData['out_checked'] = 0;
		$newCheckupData['is_cancel'] = 0;
		$newCheckupData['is_over'] = 0;
		$newCheckupData['cancel_reason'] = '';
		//插入新的检验鉴定
		$addNewCheckup = D('CaseCheckup')->add($newCheckupData);
		if (!$addNewCheckup) {
			$Model->rollback();
			$this->error('重新检验鉴定信息生成失败');
		}
		//更新图片检索id  ext_idb
		$map = array();
		$map['ext_ida'] = $caseCheckupData['id'];
		$map['ext_idb'] = 0;
		$map['cate'] = 38; //cate值参照custom.php 中的 photo_cate
		$map['case_id'] = $caseCheckupData['case_id'];
		$picData = D('CasePhotoView')->where($map)->select();
		if ($picData) {
			$para = array();
			$para['ext_idb'] = $addNewCheckup;
			$result = D('CasePhotoView')->where($map)->save($para);
			if (!$result) {
				$Model->rollback();
				$this->error('书面材料更新失败');
			}
		}

		//添加重新检验鉴定申请信息
		$Model2 = D('CaseCheckupAgain');
		$caseCheckupAgainData = $Model2->create();
		if ($caseCheckupAgainData === false) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		$caseCheckupAgainData['content'] = I('post.again_content', '', 'strip_tags');
		if (!$caseCheckupAgainData['content']) {
			$this->error('申请理由必须填写');
		}
		$caseCheckupAgainData['case_checkup_id'] = $addNewCheckup;
		//插入重新检验鉴定申请记录
		$againAdd = $Model->table(C('DB_PREFIX') . 'case_checkup_again')->add($caseCheckupAgainData);
		if (!$againAdd) {
			$Model->rollback();
			$this->error('申请失败');
		}

		//插入重新检验鉴定提请
		$Model3 = D('CaseCheckupReview');
		$caseCheckupReviewAgain = $Model3->create();
		if (false === $caseCheckupReviewAgain) {
			$Model->rollback();
			$this->error($Model3->getError());
		}
		$case_checkup_review_title_type = C('custom.case_checkup_review_title_type');
		$caseCheckupReviewAgain['title'] = $case_checkup_review_title_type[3];
		$caseCheckupReviewAgain['case_checkup_id'] = $addNewCheckup;
		$caseCheckupReviewAgain['to_userid'] = I('post.check_user_id', '', 'strip_tags');
		$caseCheckupReviewAgain['content'] = I('post.content', '', 'strip_tags');
		$caseCheckupReviewAgain['case_id'] = I('post.case_id', '', 'strip_tags');
		$caseCheckupReviewAgain['cate'] = 3; //custom.php case_checkup_review_title_type 类型3
		$addReviewAgain = $Model->table(C('DB_PREFIX') . 'case_checkup_review')->add($caseCheckupReviewAgain);
		if (false === $addReviewAgain) {
			$Model->rollback();
			$this->error('提请审批信息添加失败');
		}

		//插入重新检验鉴定提请  领导审核
		$Model4 = D('CaseCheck');
		$caseCheckAgain = $Model4->create();
		if (false === $caseCheckAgain) {
			$Model->rollback();
			$this->error($Model4->getError());
		}
		$caseCheckAgain['item_id'] = $addNewCheckup;
		$caseCheckAgain['check_user_id'] = I('post.check_user_id', '', 'strip_tags');
		$caseCheckAgain['case_id'] = I('post.case_id', '', 'strip_tags');
		$caseCheckAgain['cate'] = 13; //custom.php checkup_type 类型3
		$caseCheckAgain['origin_id'] = $addReviewAgain;
		$addCheckAgain = $Model->table(C('DB_PREFIX') . 'case_check')->add($caseCheckAgain);
		if (false === $addCheckAgain) {
			$Model->rollback();
			$this->error('领导审核提请审批信息添加失败');
		}

		$Model->commit();
		//设置工作日志
		//【提请重新申请检验鉴定时间】【申请人】申请重新检验鉴定
		$L = '';
		$R = '';
		$content = $L . date('Y-m-d H:i', $newCheckupData['create_time']) . $R . $L . $caseCheckupAgainData['from_user_name'] . $R . '申请重新检验鉴定';
		$case_id = $backData['case_id'];
		$this->saveCaseLog($caseCheckupData['case_id'], $content);

		$backData = $caseCheckupAgainData;
		$url = U('applyStepOne', array('case_id' => $backData['case_id'], 'case_checkup_id' => $addNewCheckup, 'again_id' => $againAdd, 'review_id' => $addReviewAgain, 'recheck_id' => $addCheckAgain));
		$this->success('操作成功', $url);
	}

	//获取照片用于更新照片检索ID
	public function getPicForUpdate($caseId = '', $cate = '', $ext_ida = '', $ext_idb = '', $ext_idc = '', $ext_idd = '', $ext_ide = '') {
		$map = array();
		$map['ext_ida'] = $ext_ida;
		$map['cate'] = $cate; //cate值参照custom.php 中的 photo_cate
		$map['case_id'] = $caseId;
		$result = D('CasePhotoView')->where($map)->select();
		return $result;
	}

/**
 *重新检验鉴定 更新
 */
	public function againUpdate() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckupAgain');

		$caseCheckupAgainData['id'] = I('post.again_id', '', 'strip_tags');
		$caseCheckupAgainData['from_user_id'] = I('post.from_user_id', '', 'strip_tags');
		$caseCheckupAgainData['to_user_id'] = I('post.to_user_id', '', 'strip_tags');
		$caseCheckupAgainData['update_time'] = time();
		$caseCheckupAgainData['content'] = I('post.again_content', '', 'strip_tags');
		//插入重新检验鉴定申请记录
		$againSave = $Model->table(C('DB_PREFIX') . 'case_checkup_again')->save($caseCheckupAgainData);
		if (!$againSave) {
			$Model->rollback();
			$this->error('申请失败');
		}
		//检验鉴定信息ID
		$case_id = I('post.case_id', '', 'strip_tags');
		//检验鉴定信息ID
		$case_checkup_id = I('post.case_checkup_id', '', 'strip_tags');
		//插入重新检验鉴定提请
		$Model3 = D('CaseCheckupReview');
		$caseCheckupReviewAgain = $Model3->create();
		if (false === $caseCheckupReviewAgain) {
			$Model->rollback();
			$this->error($Model3->getError());
		}
		$case_checkup_review_title_type = C('custom.case_checkup_review_title_type');
		$caseCheckupReviewAgain['title'] = $case_checkup_review_title_type[3];
		$caseCheckupReviewAgain['case_checkup_id'] = $case_checkup_id;
		$caseCheckupReviewAgain['to_userid'] = I('post.check_user_id', '', 'strip_tags');
		$caseCheckupReviewAgain['content'] = I('post.content', '', 'strip_tags');
		$caseCheckupReviewAgain['case_id'] = $case_id;
		$caseCheckupReviewAgain['cate'] = 3; //custom.php case_checkup_review_title_type 类型3
		$addReviewAgain = $Model->table(C('DB_PREFIX') . 'case_checkup_review')->add($caseCheckupReviewAgain);
		if (false === $addReviewAgain) {
			$Model->rollback();
			$this->error('提请审批信息添加失败');
		}

		//插入重新检验鉴定提请  领导审核
		$Model4 = D('CaseCheck');
		$caseCheckAgain = $Model4->create();
		if (false === $caseCheckAgain) {
			$Model->rollback();
			$this->error($Model4->getError());
		}
		$caseCheckAgain['item_id'] = $case_checkup_id;
		$caseCheckAgain['check_user_id'] = I('post.check_user_id', '', 'strip_tags');
		$caseCheckAgain['case_id'] = $case_id;
		$caseCheckAgain['cate'] = 13; //custom.php case_checkup_review_title_type 类型3
		$caseCheckAgain['origin_id'] = $addReviewAgain;
		$addCheckAgain = $Model->table(C('DB_PREFIX') . 'case_check')->add($caseCheckAgain);
		if (false === $addCheckAgain) {
			$Model->rollback();
			$this->error('领导审核提请审批信息添加失败');
		}

		$Model->commit();
		$backData = $caseCheckupAgainData;
		$url = U('applyStepOne', array('case_id' => $case_id, 'case_checkup_id' => $case_checkup_id, 'again_id' => $caseCheckupAgainData['id'], 'review_id' => $addReviewAgain, 'recheck_id' => $addCheckAgain));
		$this->success('操作成功', $url);
	}

	/**
	 * 获取审批人员列表
	 * $powerName string 权限名称
	 */
	public function getCheckUserList($powerName = '') {
		$list = array();
		$myBrigade = $this->getMyBrigade();
		// 当前用户所在大队下所有子部门
		$departmentIds = get_all_child($this->allDepartment, $myBrigade['id']);

		// 查询有当前权限的角色
		$map = array();
		$map['name'] = $powerName;
		$map['is_del'] = 0;
		$roleIds = D('PowerView')->where($map)->group('RolePower.role_id')->getField('role_id', true);

		// 没有角色，人员必然是空
		if (empty($roleIds)) {
			return $list;
		}

		// 通过角色和所在部门查询用户
		$map = array();
		$map['role_id'] = array('in', $roleIds);
		$map['department_id'] = array('in', $departmentIds);
		$map['is_del'] = 0;
		$map['is_locked'] = 0;
		$list = D('UserView')->where($map)->group('User.id')->select();
		return $list;
	}

/**
 *重新检验鉴定  步骤二
 */
	public function applyStepOne() {

		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		$again_id = I('get.again_id', '', 'strip_tags');
		$recheck_id = I('get.recheck_id', '', 'strip_tags');
		$review_id = I('get.review_id', '', 'strip_tags');
		//判断案件信息是否有效
		$caseData = D('Case')->getById($case_id);
		$this->assign('caseData', $caseData);
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		//判断检验鉴定信息是否有效
		$caseCheckupData = D('CaseCheckupClientView')->getById($case_checkup_id);
		if (!$caseCheckupData) {
			$this->error('未能读取有效检验鉴定信息');
		}
		$this->assign('caseCheckupData', $caseCheckupData);
		//读取重新检验鉴定申请信息
		$againData = D('CaseCheckupAgain')->getById($again_id);
		if (!$againData) {
			$this->error('未能读取有效重新检验鉴定申请信息');
		}
		$this->assign('againData', $againData);

		//读取重新检验鉴定审批信息
		$againReviewData = D('CaseCheckupReview')->getById($review_id);
		$againCheckData = D('CaseCheck')->getById($recheck_id);
		if (!$againReviewData || !$againCheckData) {
			$this->error('未能读取有效重新检验鉴定审批信息');
		}
		$this->assign('againReviewData', $againReviewData);
		$this->assign('againCheckData', $againCheckData);

		//读取所有有效当事人及相关人员
		$allClientRelaterData = $this->getAllClientRelater($case_id);
		$this->assign('allClientRelaterData', $allClientRelaterData);

		//读取有效审核人
		$list = $this->getCheckUserList("case_checkup_check_again_0");
		$this->assign('list', $list);
		$nowtime = time() - 300;
		$this->assign('nowtime', $nowtime);

		//判断是否可以进行提请审核 并判断审核类型
		$newCate = '0';
		if ($caseCheckupData['is_delay'] == '1') {
			$newCate = '0';
		}
		//审核类型 延期=0 超期=1 延期并超期=2
		$this->assign('newCate', $newCate);

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

		//获取该案件下所有有效当事人的信息
		$allValidClient = $this->getValidClient($caseData['id']);
		$this->assign('allValidClient', $allValidClient);
		//读取上一次委托检验鉴定未保存的值
		$map = array();
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['case_checkup_id'] = $caseCheckupData['pid'];
		$entrust_cookie_data = D('CaseCheckupEntrust')->where($map)->find();
		unset($entrust_cookie_data['id']);
		unset($entrust_cookie_data['train']);
		unset($entrust_cookie_data['code']);
		unset($entrust_cookie_data['is_submit']);
		unset($entrust_cookie_data['is_finish']);
		unset($entrust_cookie_data['is_first']);
		unset($entrust_cookie_data['target_client_id']);
		unset($entrust_cookie_data['target_name']);
		unset($entrust_cookie_data['target_sex']);
		unset($entrust_cookie_data['target_age']);
		unset($entrust_cookie_data['target_tel']);
		unset($entrust_cookie_data['target_company']);
		unset($entrust_cookie_data['target_address']);
		unset($entrust_cookie_data['status']);
		if ($entrust_cookie_data['case_checkup_id'] != "" && $entrust_cookie_data['case_checkup_id'] == $caseCheckupData['pid']) {
			$entrust_cookie_data = json_encode(array($entrust_cookie_data));

		}

		$this->assign('entrust_cookie_data', $entrust_cookie_data);

		$this->assign('case_id', $case_id);
		$this->assign('case_checkup_id_pre', $caseCheckupData['pid']);

		// 渲染
		$this->display();
	}

	/**
	 *重新检验鉴定  步骤二
	 */
	public function applyStepTwo() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');
		$again_id = I('get.again_id', '', 'strip_tags');
		$recheck_id = I('get.recheck_id', '', 'strip_tags');
		$review_id = I('get.review_id', '', 'strip_tags');
		//判断案件信息是否有效
		$caseData = D('Case')->getById($case_id);
		$this->assign('caseData', $caseData);
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		//判断检验鉴定信息是否有效
		$caseCheckupData = D('CaseCheckupClientView')->getById($case_checkup_id);
		if (!$caseCheckupData) {
			$this->error('未能读取有效检验鉴定信息');
		}
		$this->assign('caseCheckupData', $caseCheckupData);
		//读取重新检验鉴定申请信息
		$againData = D('CaseCheckupAgain')->getById($again_id);
		if (!$againData) {
			$this->error('未能读取有效重新检验鉴定申请信息');
		}
		$this->assign('againData', $againData);

		//读取重新检验鉴定审批信息
		$againReviewData = D('CaseCheckupReview')->getById($review_id);
		$againCheckData = D('CaseCheck')->getById($recheck_id);
		if (!$againReviewData || !$againCheckData) {
			$this->error('未能读取有效重新检验鉴定审批信息');
		}
		$this->assign('againReviewData', $againReviewData);
		$this->assign('againCheckData', $againCheckData);

		//读取所有有效当事人及相关人员
		$allClientRelaterData = $this->getAllClientRelater($case_id);
		$this->assign('allClientRelaterData', $allClientRelaterData);

		//读取有效审核人
		$list = $this->getCheckUserList("case_checkup_check_again_0");
		$this->assign('list', $list);

		$this->assign('nowtime', time() - 300);

		//判断是否可以进行提请审核 并判断审核类型
		$newCate = '0';
		if ($caseCheckupData['is_delay'] == '1') {
			$newCate = '0';
		}
		//审核类型 延期=0 超期=1 延期并超期=2
		$this->assign('newCate', $newCate);
		//查询该检验鉴定最新审批审批数据
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['cate'] = $newCate;
		$map['case_checkup_id'] = $caseCheckupData['id']; //custom.php check_type 检验鉴定
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->order('create_time desc')->find();
		//审批（审核）数据赋值
		$this->assign('caseCheckupReviewData', $caseCheckupReviewData);
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

		//获取该案件下所有有效当事人的信息
		$allValidClient = $this->getValidClient($caseData['id']);
		$this->assign('allValidClient', $allValidClient);

		//读取委托检验鉴定未保存的值
		if ($_GET['update_type'] == 1) {
			$entrust_cookie_data = session("entrust_cookie_data");
			$entrust_cookie_data = json_encode(array($entrust_cookie_data));
			$this->assign('entrust_cookie_data', $entrust_cookie_data);
		}
		$this->assign('case_id', $case_id);
		$this->assign('case_checkup_id_pre', $caseCheckupData['pid']);
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
	/**
	 *更新检验鉴定执行操作
	 */
	public function applyStepTwoUpdate() {
		$again_id = I('post.again_id', '', 'strip_tags');
		$review_id = I('post.review_id', '', 'strip_tags');
		$recheck_id = I('post.recheck_id', '', 'strip_tags');
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckup');
		$caseCheckupData = $Model2->create();
		if ($caseCheckupData['checkup_org_item_pid'] == 1) {
			if (empty($caseCheckupData['target_case_client_id'])) {
				$Model->rollback();
				$this->error('请选择鉴定对象相关人员');
			}
		}
		if ($caseCheckupData['checkup_org_item_pid'] == 2) {
			if (empty($caseCheckupData['target_case_client_id'])) {
				$Model->rollback();
				$this->error('请选择鉴定对象相关车辆');
			}
		}
		if ($caseCheckupData['checkup_org_item_pid'] == 3) {

			if (empty($caseCheckupData['target_other']) || $caseCheckupData['target_other'] == '0') {
				$Model->rollback();
				$this->error('请填写鉴定对象其他项');
			}
			if (empty($caseCheckupData['target_case_client_id'])) {
				$caseCheckupData['target_case_client_id'] = 0;
			}
		}

		if (false === $caseCheckupData) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		//读取案件和当事人车牌号
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('post.case_id', '', "strip_tags");
		$target_case_client_id = I('post.target_case_client_id', '', "strip_tags");
		if ($target_case_client_id) {
			$map['case_client_is_del'] = 0;
			$map['case_client_id'] = $target_case_client_id;

		}
		$clientData = D('CaseCaseClientView')->where($map)->find();

		$caseCheckupData['target_car_no'] = $clientData['case_client_car_no'];
		if ($caseCheckupData['target_car_no'] == "") {
			$caseCheckupData['target_car_no'] = "0";
		}
		//检验对象为其他时数据处理
		$target_other = I('post.target_other', '', "strip_tags");
		if (!empty($target_other)) {
			$caseCheckupData['target_other'] = $target_other;
		}

		$finish_time = I('post.finish_time', '', "strip_tags");
		$finish_time = strtotime($finish_time);
		if ($finish_time <= time()) {
			$Model->rollback();
			$this->error('约定完成时间不能早于当前时间');
		}
		$caseCheckupData['finish_time'] = $finish_time;
		//委派时间前三天
		$validTime = time() - 3 * 24 * 3600;

		//
		//超期判断
		//初始化超期值
		$caseCheckupData['is_out'] = 0;
		//获取当前时间的年月日 三个字段
		$time = time();
		//判断约定时间是否在60个自然日以内
		if ($finish_time >= $time + 60 * 24 * 3600) {
			$this->error('约定完成时间不可超过当前时间60个自然日');

		}
		$get_date_info = $this->getYearMonthDay($time);
		//查询并获取当前时间之后第N个工作日的数据
		$get_valid_date = $this->getValidWorkday($get_date_info, '20');
		//$get_valid_date 格式为当天凌晨 00:00:00 所以应该在转化为时间戳之后自加上24*3600-1秒  讲有效时间计算到当天午夜11:59:59
		//第20个工作日之后的日期转化为时间戳 并加上24*3600
		$target_valid_date = strtotime($get_valid_date['year'] . '-' . $get_valid_date['month'] . '-' . $get_valid_date['day']) + 24 * 3600 - 1;
		//
		//
		//延期判断
		//判断约定完成时间是否在委派时间之后的20个工作日之内或之外
		//初始化延期值
		$caseCheckupData['is_delay'] = 0;
		if ($finish_time > $target_valid_date) {
			//之外则延期
			$caseCheckupData['is_delay'] = 1;
		}
		//更新超期 延期审核状态
		$caseCheckupData['out_checked'] = 0;
		$caseCheckupData['delay_checked'] = 0;

		$saveCaseCheckupData = $Model->table(C('DB_PREFIX') . 'case_checkup')->save($caseCheckupData);
		if (!$saveCaseCheckupData) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();

		$url = U('applyStepTwo', array('case_id' => $caseCheckupData['case_id'], 'case_checkup_id' => $caseCheckupData['id'], 'again_id' => $again_id, 'review_id' => $review_id, 'recheck_id' => $recheck_id, 'update_type' => '1'));
		$this->success("操作成功", $url);
	}

	public function entrustInsert() {

		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckupEntrust');
		$caseCheckupEntrustData = $Model2->create();
		$entrust_time = I('post.entrust_time', '', 'strip_tags');

		if ($caseCheckupEntrustData === false) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		$caseCheckupEntrustData['entrust_time'] = strtotime($entrust_time);

		$id = I('post.id', '', 'strip_tags');
		if ($id) {
			$result = M('CaseCheckupEntrust')->save($caseCheckupEntrustData);

		} else {
			$result = M('CaseCheckupEntrust')->add($caseCheckupEntrustData);
			$caseCheckupEntrustData['id'] = $result;

		}
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$case_id = I('post.case_id', '', 'strip_tags');
		$case_checkup_id = I('post.case_checkup_id', '', 'strip_tags');
		$url = U('applyStepThree', array('case_id' => $case_id, 'case_checkup_id' => $case_checkup_id));
		$this->success('操作成功', $url);
	}

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

		//读取所有有效当事人及相关人员
		$allClientRelaterData = $this->getAllClientRelater($case_id);
		$this->assign('allClientRelaterData', $allClientRelaterData);

		//查询重新检验鉴定申请记录
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $case_checkup_id;
		$caseCheckupAgainData = D('CaseCheckupAgain')->where($map)->find();

		if (!$caseCheckupAgainData) {
			$this->error('未能读取有效重新检验鉴定申请信息');
		}
		$this->assign('caseCheckupAgainData', $caseCheckupAgainData);

		//判断是否可以进行提请审核 并判断审核类型
		if ($caseCheckupData['is_delay'] == '1') {
			$newCate = '0';
		}

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
			//$map['ext_ida'] = $caseCheckupReportData[$key]['id'];
			$map['ext_idd'] = $caseCheckupReportData[$key]['id'];
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
		//读取退回检验鉴定结果照片数量
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

		$this->assign('case_id', $case_id);
		$this->assign('case_checkup_id_pre', $caseCheckupData['pid']);

		// 渲染
		$this->display();
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
		$lists = $this->getPhotoList($cate, $id, 0, $case_id);
		$this->assign('lists', $lists);
		$this->display('CaseCheckup/applyStepThree/photoTable');
	}

	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	protected function getPhotoList($cate = 0, $case_checkup_id = 0, $itemId = 0, $case_id = 0) {

		$map = array();
		$map['cate'] = $cate;
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		if ($case_checkup_id != 0) {
			$map['ext_ida'] = $case_checkup_id;
		}

		if ($itemId != 0) {
			$map['ext_idd'] = $itemId;
		}

		$list = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);

		return $list;
	}
	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	protected function getPhotoListAgain($cate = 0, $case_checkup_id = 0, $itemId = 0, $case_id = 0) {

		$map = array();
		$map['cate'] = $cate;
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		if ($case_checkup_id != 0) {
			$map['ext_ida'] = $case_checkup_id;
		}

		$map['ext_idb'] = $itemId;

		$list = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);
		return $list;
	}

//检验报告数据执行操作
	public function reportInsert() {

		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckupReport');
		$caseCheckupReportData = $Model2->create();
		if ($caseCheckupReportData === false) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		$caseCheckupReportData['finish_time'] = strtotime(I('post.finish_time', '', 'strip_tags'));
		// if ($caseCheckupReportData['finish_time'] < time()) {
		// 	$this->error('鉴定完成时间不能小于当前时间');
		// }

		$addCaseCheckupReportData = D('caseCheckupReport')->add($caseCheckupReportData);
		if (!$addCaseCheckupReportData) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$this->success("操作成功");

	}
	/**
	 *短信消息执行操作
	 */
	public function msgInsert() {

		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckupSms');
		$caseCheckupSms = $Model2->create();
		if ($caseCheckupSms === false) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		$id = $Model2->add($caseCheckupSms);

		if (!$id) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$this->success("操作成功");

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

		$lists = $this->getPhotoList($cate, $case_checkup_id, $case_checkup_report_id, $case_id);
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
		$save = D('CaseCheckup')->where($map)->save($data);
		if (!$save) {
			$this->error('操作失败');
		}
		$this->success('操作成功');
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

		//读取所有当事人及相关人员
		$allClientRelaterData = $this->getAllClientRelater($case_id);
		$this->assign('allClientRelaterData', $allClientRelaterData);

		//查询重新检验鉴定申请记录
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $case_checkup_id;
		$caseCheckupAgainData = D('CaseCheckupAgain')->where($map)->find();

		if (!$caseCheckupAgainData) {
			$this->error('未能读取有效重新检验鉴定申请信息');
		}
		$this->assign('caseCheckupAgainData', $caseCheckupAgainData);

		//判断检验鉴定信息是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_checkup_id;
		$caseCheckupData = D('CaseCheckupClientView')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('未能读取有效检验鉴定信息');
		}
		//检验鉴定数据赋值
		$this->assign('caseCheckupData', $caseCheckupData);
		$this->assign('case_id', $caseCheckupData['case_id']);
		$this->assign('case_checkup_id_pre', $caseCheckupData['pid']);

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

	/*
		      *Ajax 读取检验鉴定结果信息
	*/
	public function getReportDetail() {
		$id = I('id', '', 'strip_tags');
		$reportData = D('CaseCheckupReport')->getById($id);
		$reportData['finish_time_date'] = date('Y-m-d H:i', $reportData['finish_time']);
		$this->success($reportData);
	}

	/*读取案件有效当事人及相关人
		     *
	*/
	public function getAllClientRelater($case_id) {
		$allClientName = array();
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $case_id;
		$allClientId = D('CaseClient')->where($map)->select();
		$allClientRelaterData = array();

		foreach ($allClientId as $key => $value) {
			$allClientRelaterData[] = array('id' => $allClientId[$key]['id'], 'name' => $allClientId[$key]['name']);
			//读取相关人 勿删
			$map = array();
			//$map['case_client_id'] = array('in', $allId);
			$map['case_client_id'] = $allClientId[$key]['id'];
			$allClientRelater = D('CaseClientRelater')->where($map)->select();
			foreach ($allClientRelater as $key1 => $value1) {
				$allClientRelaterData[] = array('id' => $allClientRelater[$key1]['id'], 'name' => $allClientRelater[$key1]['name']);

			}
		}
		return $allClientRelaterData;
	}

	/**
	 *重新检验鉴定书面材料 cate=38  custom.php photo_cate[39]
	 */
	public function checkupAgainPicNumber() {
		$cate = I('get.cate', '', 'int');
		$case_checkup_id_pre = I('get.case_checkup_id_pre', '', 'int');
		$case_id = I('get.case_id', '', 'int');
		$this->assign('cate', $cate);
		$this->assign('case_checkup_id_pre', $case_checkup_id_pre);
		$this->assign('case_id', $case_id);
		$map = array();
		$map['pid'] = $case_checkup_id_pre;
		$map['is_cancel'] = 0;
		$picCaseCheckupData = D('CaseCheckup')->where($map)->find();
		if (!$picCaseCheckupData) {
			$picCaseCheckupData['id'] = 0;
		}
		$this->assign('picCaseCheckupData', $picCaseCheckupData);

		$lists = $this->getPhotoListAgain($cate, $case_checkup_id_pre, $picCaseCheckupData['id'], $case_id);
		$this->assign('lists', $lists);
		$this->display();
	}

}
