<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 检验鉴定
 */
class CaseCheckupController extends CommonController {

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
	 * '不可操作'首页界面
	 */
	public function indexRead() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$this->assign('case_id', $case_id);
		// 渲染
		$this->display();
	}
	/**
	 * 详情页 页面分流
	 */
	public function edit() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$case_checkup_id = I('get.case_checkup_id', '', 'strip_tags');

		$caseCheckupData = D('CaseCheckup')->getById($case_checkup_id);
		if (!$caseCheckupData) {
			$this->error('未能获取有效检验鉴定信息');
		}

		$map = array();
		$map['case_checkup_id'] = $caseCheckupData['id'];
		$map['is_del'] = 0;
		$caseCheckuEntrustData = D('CaseCheckupEntrust')->where($map)->find();
		if (!$caseCheckuEntrustData || $caseCheckuEntrustData['case_checkup_id'] != $case_checkup_id) {
			$url = U('CaseCheckup/applyStepTwo', array('case_id' => $caseCheckupData['case_id'], 'case_checkup_id' => $caseCheckupData['id']));
		} else {
			$url = U('CaseCheckup/applyStepThree', array('case_id' => $caseCheckupData['case_id'], 'case_checkup_id' => $caseCheckupData['id']));

		}
		$this->redirect($url);

	}

	/**
	 * 主页表格界面
	 */
	public function indexTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['pid'] = 0;
		$is_cancel = I('post.is_cancel', '', 'strip_tags');
		//$map['is_cancel'] = 0;
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

			$list[$key]['re_list'] = $this->getReCaseCheckupInfo($list[$key]['id'], $is_cancel);

		}

		//  渲染
		if ($is_cancel == 1) {
			//重新计算有效数据数量
			$count = 0;
			foreach ($list as $key3 => $value3) {
				//读取作废检验鉴定 如果第一次检验鉴定未作废
				if ($list[$key3]['is_cancel'] == '0') {
					$new_re_list = $list[$key3]['re_list'];
					//清除检验鉴定未作废项
					$list[$key3]['id'] = '';
					//保留重新检验鉴定作废项
					$list[$key3]['re_list'] = $new_re_list;

					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}
				} else {
					$count++;
					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}

				}
			}
			$template = "CaseCheckup/index/tableCancel";
		} else {
			//重新计算有效数据数量
			$count = 0;
			foreach ($list as $key3 => $value3) {
				//读取作废检验鉴定 如果第一次检验鉴定未作废
				if ($list[$key3]['is_cancel'] == 1) {
					//清除检验鉴定作废项
					$list[$key3]['id'] = '';
				} else {
					$count++;

					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}
				}
			}
			$template = "CaseCheckup/index/table";

		}

		$page = new Page($count, 15);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);

		$this->assign('page', $pageInfo);
		$this->assign('list', $list);
		$this->display($template);

	}

/**
 * '不可操作'主页表格界面
 */
	public function indexReadTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['pid'] = 0;
		$is_cancel = I('post.is_cancel', '', 'strip_tags');
		//$map['is_cancel'] = 0;
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

			$list[$key]['re_list'] = $this->getReCaseCheckupInfo($list[$key]['id'], $is_cancel);

		}

		//  渲染
		if ($is_cancel == 1) {
			//重新计算有效数据数量
			$count = 0;
			foreach ($list as $key3 => $value3) {
				//读取作废检验鉴定 如果第一次检验鉴定未作废
				if ($list[$key3]['is_cancel'] == '0') {
					$new_re_list = $list[$key3]['re_list'];
					//清除检验鉴定未作废项
					$list[$key3]['id'] = '';
					//保留重新检验鉴定作废项
					$list[$key3]['re_list'] = $new_re_list;

					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}
				} else {
					$count++;
					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}

				}
			}
			$template = "CaseCheckup/indexRead/tableCancel";
		} else {
			//重新计算有效数据数量
			$count = 0;
			foreach ($list as $key3 => $value3) {
				//读取作废检验鉴定 如果第一次检验鉴定未作废
				if ($list[$key3]['is_cancel'] == 1) {
					//清除检验鉴定作废项
					$list[$key3]['id'] = '';
				} else {
					$count++;

					if ($list[$key3]['re_list']) {

						foreach ($list[$key3]['re_list'] as $key5 => $value5) {
							$count++;
						}

					}
				}
			}
			$template = "CaseCheckup/indexRead/table";

		}

		$page = new Page($count, 15);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);

		$this->assign('page', $pageInfo);
		$this->assign('list', $list);
		$this->display($template);

	}

	public function getReCaseCheckupInfo($pid, $is_cancel) {
		// 列表信息
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['pid'] = $pid;
		$map['is_cancel'] = $is_cancel;
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
		return $list;
	}

	/**
	 *新增检验鉴定  步骤一
	 */
	public function applyStepOne() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}

		//鉴定对象一级选项
		$case_checkup_obj_type = C('custom.case_checkup_obj_type');
		$this->assign('case_checkup_obj_type', $case_checkup_obj_type);
		// 渲染
		$this->display();
	}
	/**
	 *新增检验鉴定执行操作
	 */
	public function applyStepOneInsert() {

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
		if ($clientData['id'] != $map['id']) {
			$this->error('案件信息读取错误');
		}

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
		//1.超期：事故发生三天以后才进行委托
		if ($clientData['accident_time'] < $validTime) {
			$caseCheckupData['is_out'] = 1;

		}
		//2.死亡委托：鉴定对象为人员，选择当事人，/todo委托类型为尸检todo/，且当事人死亡三天以上的 则超期
		if ($caseCheckupData['checkup_org_item_pid'] == 1 && $clientData['case_client_hurt_type'] == 1 && $clientData['case_client_death_time'] < $validTime) {
			$caseCheckupData['is_out'] = 1;
		}
		//3.逃逸委托：鉴定对象为车辆，选择车牌号，车牌号对应当事人逃逸，且当事人驾车（交通方式）逃逸的，且当事人逃逸抓获三天以上的 则超期
		if ($caseCheckupData['checkup_org_item_pid'] == 2 && $clientData['case_client_is_escape'] == 1 && $clientData['case_client_traffic_type'] != 1 && $clientData['case_client_traffic_type'] != 8 && $clientData['case_client_escape_catch_man_time'] < $validTime) {
			//超期
			$caseCheckupData['is_out'] = 1;
		}

		$time = time();
		//获取当前时间的年月日 三个字段
		$get_date_info = $this->getYearMonthDay($time);
		//判断约定时间是否在60个自然日以内
		if ($finish_time >= $time + 60 * 24 * 3600) {
			$this->error('约定完成时间不可超过当前时间60个自然日');

		}
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
		$addCaseCheckupData = $Model->table(C('DB_PREFIX') . 'case_checkup')->add($caseCheckupData);
		if (!$addCaseCheckupData) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$caseCheckupData['id'] = $addCaseCheckupData;
		//
		//
		//设置工作记录日志
		//【委托时间】【大队】委托【鉴定机构名称】对【鉴定对象】进行【委托类型】，约定完成时间为【约定完成时间】
		$L = '';
		$R = '';
		//委托时间 date('Y-m-d', $time)
		//大队
		$myBrigade = $this->getMyBrigade();
		//鉴定机构名称
		$checkup_org_id = I('post.checkup_org_id', '', 'strip_tags');
		$checkupOrgData = D('CheckupOrg')->getById($checkup_org_id);
		//鉴定对象
		$checkup_org_item_pid = I('post.checkup_org_item_pid', '', 'strip_tags');
		$target_case_client_id = I('post.target_case_client_id', '', 'strip_tags');
		$target_other = I('post.target_other', '', 'strip_tags');
		if ($target_case_client_id) {
			$objChild = D('CaseClient')->getById($target_case_client_id);
		}
		if ($checkup_org_item_pid == 1) {
			$checkObj = '人员：' . $objChild['name'];
		}
		if ($checkup_org_item_pid == 2) {
			$checkObj = '车辆：' . $objChild['car_no'];
		}
		if ($checkup_org_item_pid == 3) {
			$checkObj = '其他：' . $target_other;
		}
		//鉴定类型
		$checkup_org_item_id = I('post.checkup_org_item_id', '', 'strip_tags');
		$checkTypeData = D('CheckupOrgItem')->getById($checkup_org_item_id);
		//约定完成时间
		$finish_time = I('post.finish_time', '', 'strip_tags');
		$content = $L . date('Y-m-d H:i', $time) . $R . $L . $myBrigade['name'] . $R . '委托' . $L . $checkupOrgData['name'] . $R . '对' . $L . $checkObj . $R . '进行' . $L . $checkTypeData['name'] . $R . ',约定完成时间为' . $L . $finish_time . $R;
		$case_id = I('post.case_id', '', "strip_tags");
		$this->saveCaseLog($case_id, $content);
		//
		//
		//
		$url = U('applyStepTwo', array('case_id' => $caseCheckupData['case_id'], 'case_checkup_id' => $caseCheckupData['id']));
		$this->success("操作成功", $url);
	}

	/**
	 *新增检验鉴定  步骤二
	 */
	public function applyStepTwo() {
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
		//仅延期
		if ($caseCheckupData['is_delay'] == '1' && $caseCheckupData['is_out'] == '0') {
			$newCate = '0';
		}
		//仅超期
		if ($caseCheckupData['is_delay'] == '0' && $caseCheckupData['is_out'] == '1') {
			$newCate = '1';
		}
		//延期并超期
		if ($caseCheckupData['is_delay'] == '1' && $caseCheckupData['is_out'] == '1') {
			$newCate = '2';
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
		$this->assign('newCate', $newCate);
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

		//获取该案件下所有有效当事人的信息
		$allValidClient = $this->getValidClient($caseData['id']);
		$this->assign('allValidClient', $allValidClient);
		//读取委托检验鉴定未保存的值
		if ($_GET['update_type'] == 1) {
			$entrust_cookie_data = session("entrust_cookie_data");
			$entrust_cookie_data = json_encode(array($entrust_cookie_data));
			$this->assign('entrust_cookie_data', $entrust_cookie_data);
		}
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
		//1.超期：事故发生三天以后才进行委托
		if ($clientData['accident_time'] < $validTime) {
			$caseCheckupData['is_out'] = 1;
		}
		//2.死亡委托：鉴定对象为人员，选择当事人，/todo委托类型为尸检todo/，且当事人死亡三天以上的 则超期
		if ($caseCheckupData['checkup_org_item_pid'] == 1 && $clientData['case_client_hurt_type'] == 1 && $clientData['case_client_death_time'] < $validTime) {
			$caseCheckupData['is_out'] = 1;
		}
		//3.逃逸委托：鉴定对象为车辆，选择车牌号，车牌号对应当事人逃逸，且当事人驾车（交通方式）逃逸的，且当事人逃逸抓获三天以上的 则超期
		if ($caseCheckupData['checkup_org_item_pid'] == 2 && $clientData['case_client_is_escape'] == 1 && $clientData['case_client_traffic_type'] != 1 && $clientData['case_client_traffic_type'] != 8 && $clientData['case_client_escape_catch_man_time'] < $validTime) {
			//超期
			$caseCheckupData['is_out'] = 1;
		}
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
		$url = U('applyStepTwo', array('case_id' => $caseCheckupData['case_id'], 'case_checkup_id' => $caseCheckupData['id'], 'update_type' => '1'));
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

		$addCaseCheckupReportData = D('CaseCheckupReport')->add($caseCheckupReportData);
		if (!$addCaseCheckupReportData) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//更新主表 is_over 是否完成的状态
		$caseCheckupData['id'] = $caseCheckupReportData['case_checkup_id'];
		$caseCheckupData['is_over'] = 1;
		$caseCheckupData['update_time'] = time();
		$result = D('CaseCheckup')->save($caseCheckupData);
		if (!$result) {
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
		$Model = new \Think\Model();
		$Model->startTrans();
		$id = I('post.id', '', 'int');
		$data['id'] = $id;
		$caseCheckupData = D('CaseCheckup')->getById($id);
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
			$Model->rollback();
			$this->error('操作失败!');
		}

		//图片查询条件
		if ($caseCheckupData['pid'] != 0) {
			//如果 pid不等于零  则为重新检验鉴定
			//重新检验鉴定 书面材料photo_cate 38 索引键 ext_ida存储的为该案件第一次检验鉴定的主键ID 即pid
			$map = array();
			$map['cate'] = 38;
			$map['case_id'] = $caseCheckupData['case_id'];
			$map['ext_ida'] = $caseCheckupData['pid'];
			$para = array();
			$para['is_document'] = 0;
			$para['update_user_id'] = $this->my['id'];
			$para['update_time'] = time();
			$model = M('CasePhoto');
			$rs = $model->where($map)->save($para);
			if ($rs === false) {
				$Model->rollback();
				$this->error('操作失败!!');
			}
		}
		$map = array();
		$map['cate'] = array('in', '39,40,41,42,43,44');
		$map['case_id'] = $caseCheckupData['case_id'];
		$map['ext_ida'] = $id;
		$para = array();
		$para['is_document'] = 0;
		$para['update_user_id'] = $this->my['id'];
		$para['update_time'] = time();
		$model = M('CasePhoto');
		$rs = $model->where($map)->save($para);
		//echo $model->getLastSql();exit;
		if ($rs === false) {
			$Model->rollback();
			$this->error('操作失败!!');
		}
		$Model->commit();
		$this->success('操作成功');
	}
	/**
	 *检验鉴定作废状态查询
	 */
	public function caseCheckupCancelStatus() {
		$id = I('post.id', '', 'int');
		$checkupData = D('CaseCheckup')->getById($id);
		if (!$checkupData) {
			$this->error('检验鉴定信息无效');
		}
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

		// if ($checkupData['pid'] != 0) {

		// 	$pid = $checkupData['pid'];
		// 	$checkupDataPre = D('CaseCheckup')->getById($pid);
		// 	if (!$checkupDataPre) {
		// 		$this->error('上一次检验鉴定信息无效');
		// 	}
		// 	//查询是否处在提审状态
		// 	$map = array();
		// 	$map['case_checkup_id'] = $pid;
		// 	$map['status'] = 0;
		// 	$reviewDataPre = D('CaseCheckupReview')->where($map)->find();
		// 	if ($reviewDataPre) {
		// 		$this->error('上一次检验鉴定已提审,不能作废');
		// 	}
		// 	//查询是否存在有效鉴定报告
		// 	$map = array();
		// 	$map['case_checkup_id'] = $pid;
		// 	$map['is_back'] = 0;
		// 	$reportDataPre = D('CaseCheckupReport')->where($map)->find();
		// 	if ($reportDataPre) {
		// 		$this->error('上一次检验鉴定已出具鉴定结果,不能作废');
		// 	}

		// }
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
		     *Ajax 查询是否已经生成重新检验鉴定
	*/
	public function findReCheckupData() {
		$id = I('id', '', 'strip_tags');
		if (!$id) {
			$this->error('查询失败');
		}
		$map = array();
		$map['pid'] = $id;
		$map['is_cancel'] = 0;
		$caseCheckupData = D('CaseCheckup')->where($map)->find();
		if ($caseCheckupData && $caseCheckupData['pid'] == $id) {
			$this->success($caseCheckupData);
		} else {
			$this->error('查询失败');
		}

	}

}
