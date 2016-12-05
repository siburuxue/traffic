<?php
namespace Admin\Controller;

use Lib\Page;

/**
 * 统计
 */
class StatisticsController extends CommonController {

	/**
	 * 构造方法
	 */
	public function __construct() {
		parent::__construct();
		$this->myBrigade = $this->getMyBrigade();
	}

	/**
	 * 获取办案人
	 */
	public function getHandle() {
		$did = I('request.did');
		$list = array();

		if (is_array($did) && !empty($did)) {
			$allDepartment = $this->allDepartment;
			$departmentIds = array();
			foreach ($did as $value) {
				$departmentIds = array_merge($departmentIds, get_all_child($allDepartment, $value));
			}

			$map = array();
			$map['department_id'] = array('in', $departmentIds);
			$list = M('User')->where($map)->select();
		}

		$this->assign('list', empty($list) ? array() : $list);
		$this->display();
	}

	/**
	 * 血样统计
	 */
	public function bloodtube() {
		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);
		// 渲染
		$this->display();
	}

	/**
	 * 血样统计打印
	 */
	public function bloodtubePrint() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['is_used'] = 1;
		$condition = get_condition();

		isset($condition['department']) && $map['target_department_id'] = array('in', $condition['department']);
		isset($condition['handle']) && $map['used_user_id'] = array('in', $condition['handle']);
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		isset($condition['accident_place']) && $map['case_accident_place'] = array('like', '%' . $condition['accident_place'] . '%');
		isset($condition['client_name']) && $map['case_client_name'] = $condition['client_name'];

		// 列表信息
		$Model = D('CaseBloodtubeCateView');

		$list1 = $Model->where($map)->order('create_time desc')->group('BloodtubeCate.id')->select();

		$list = array();
		// 查询扩展信息
		foreach ($list1 as $key => &$value) {
			$map = array();
			$map['bloodtube_cate_id'] = $value['id'];
			$map['is_del'] = 0;
			$value['bloodtube'] = M('Bloodtube')->where($map)->select();

			$item = array();
			$item['department_name'] = $value['department_name'];
			$item['handle_true_name'] = $value['handle_true_name'];
			$item['case_accident_time'] = date('Y-m-d H:i', $value['case_accident_time']);
			$item['case_accident_place'] = $value['case_accident_place'];
			$item['case_client_name'] = $value['case_client_name'];
			$item['bloodtube'] = $value['bloodtube'][0]['code'];
			$item['count'] = 2;
			$list[] = $item;
			$item = array();
			$item['department_name'] = $value['department_name'];
			$item['handle_true_name'] = $value['handle_true_name'];
			$item['case_accident_time'] = date('Y-m-d H:i', $value['case_accident_time']);
			$item['case_accident_place'] = $value['case_accident_place'];
			$item['case_client_name'] = $value['case_client_name'];
			$item['bloodtube'] = $value['bloodtube'][1]['code'];
			$item['count'] = 2;
			$list[] = $item;

		}
		unset($value);
		$titleArray = array("所属大队", "办案人", "事故时间", "事故地点", "被提取人姓名", "抗凝瓶唯一编码");
		//行位置
		$xAxisArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		//一共有多少行数据
		$rows = count($list);
		//一共有多少列数据
		$cells = 6;
		$xlsTitle = '血样提取统计';
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:F1");
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
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:F2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($list[$j] as $key => $val) {
				if ($k < 6) {
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
			$count = (int) $list[$z]['count'];
			if ($z < $mergeNum) {
				continue;
			} else {
				$mergeNum += $count;
				if ($count > 1) {
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[0] . ($z + 3) . ':' . $xAxisArray[0] . ($z + 3 + ($count - 1))); //所属大队合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[1] . ($z + 3) . ':' . $xAxisArray[1] . ($z + 3 + ($count - 1))); //办案人合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[2] . ($z + 3) . ':' . $xAxisArray[2] . ($z + 3 + ($count - 1))); //事故时间合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[3] . ($z + 3) . ':' . $xAxisArray[3] . ($z + 3 + ($count - 1))); //事故地点合并单元格
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[4] . ($z + 3) . ':' . $xAxisArray[4] . ($z + 3 + ($count - 1))); //被提取人姓名合并单元格
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

	/**
	 * 血样提取统计表格
	 */
	public function bloodtubeTable() {

		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'CaseInfo.accident_time';
			break;
		case 'id':
			$orderby = 'BloodtubeCate.id';
			break;
		default:
			$orderby = 'BloodtubeCate.create_time';
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

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['is_used'] = 1;
		$condition = get_condition();

		isset($condition['department']) && $map['target_department_id'] = array('in', $condition['department']);
		isset($condition['handle']) && $map['used_user_id'] = array('in', $condition['handle']);
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		isset($condition['accident_place']) && $map['case_accident_place'] = array('like', '%' . $condition['accident_place'] . '%');
		isset($condition['client_name']) && $map['case_client_name'] = $condition['client_name'];

		// 列表信息
		$Model = D('CaseBloodtubeCateView');
		$count = $Model->where($map)->count('distinct BloodtubeCate.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('BloodtubeCate.id')->select();

		// 查询扩展信息
		foreach ($list as $key => &$value) {
			$map = array();
			$map['bloodtube_cate_id'] = $value['id'];
			$map['is_del'] = 0;
			$value['bloodtube'] = M('Bloodtube')->where($map)->select();

		}
		unset($value);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Statistics/bloodtube/table');
	}

	/**
	 * 检验鉴定
	 */
	public function checkup() {
		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);
		// 检验鉴定项目
		$map = array();
		$map['is_del'] = 0;
		$checkupOrgItem = M('CheckupOrgItem')->where($map)->order('train asc')->select();
		// 检验鉴定子项目
		$checkupOrgObj = get_custom_config('checkup_org_obj');
		// 检验鉴定项目选项
		$checkupOrgItemOption = array();

		foreach ($checkupOrgObj as $key => $value) {
			$item = array();
			$item['id'] = '';
			$item['name'] = $value;
			$item['disabled'] = 1;
			$item['prefix'] = '';
			$checkupOrgItemOption[] = $item;

			$child = list_search($checkupOrgItem, 'checkup_org_obj=' . $key);
			foreach ($child as $k => $v) {
				$item = array();
				$item['id'] = $v['id'];
				$item['name'] = $v['name'];
				$item['disabled'] = 0;
				$item['prefix'] = '|-&nbsp;';
				$checkupOrgItemOption[] = $item;
			}
		}
		$this->assign('checkupOrgItemOption', $checkupOrgItemOption);
		// 检验鉴定机构
		$map = array();
		$map['is_del'] = 0;
		$map['status'] = 1;
		$checkupOrg = M('CheckupOrg')->where($map)->select();

		foreach ($checkupOrg as $key => &$value) {
			$map = array();
			$map['checkup_org_id'] = $value['id'];
			$child = D('CheckupOrgItemAccessView')->where($map)->group('CheckupOrgItem.id')->select();

			foreach ($child as $k => &$v) {
				$map = array();
				$map['checkup_org_id'] = $value['id'];
				$map['checkup_org_item_id'] = $v['id'];
				$map['is_del'] = 0;
				$map['is_cancel'] = 0;
				$v['_count'] = M('CaseCheckup')->where($map)->count();
			}
			unset($v);

			$value['_child'] = $child;

		}
		unset($value);
		$this->assign('checkupOrg', $checkupOrg);

		//dump($checkupOrg);

		// 渲染
		$this->display();
	}

	/**
	 * 获取检验鉴定机构
	 */
	public function getCheckupOrgDepartment() {

	}

	/**
	 * 检验鉴定表格
	 */
	public function checkupTable() {

		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'CaseInfo.accident_time';
			break;
		case 'id':
			$orderby = 'CaseCheckup.id';
			break;
		case 'finish_time':
			$orderby = 'CaseCheckupReport.finish_time';
			break;
		default:
			$orderby = 'CaseCheckup.create_time';
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

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['is_cancel'] = 0;
		// 前台查询条件
		$condition = get_condition();
		// 所属大队
		isset($condition['department']) && $map['case_department_id'] = array('in', $condition['department']);
		// 办案人
		isset($condition['handle']) && $map['create_user_id'] = array('in', $condition['handle']);
		// 鉴定对象
		if (isset($condition['obj'])) {
			$map['_string'] = '
				(TargetCaseClient.name like "%' . $condition['obj'] . '%" and checkup_org_item_pid=1)
				or (TargetCaseClient.car_no like "%' . $condition['obj'] . '%" and checkup_org_item_pid=2)
				or (target_other like "%' . $condition['obj'] . '%" and checkup_org_item_pid=3)
			';
		}
		// 事故时间
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		// 检验鉴定时间
		if (is_time($condition['checkup_start_time']) && is_time($condition['checkup_end_time'])) {
			$map['create_time'] = array(array('egt', strtotime($condition['checkup_start_time'])), array('elt', strtotime($condition['checkup_end_time'])));
		} else if (is_time($condition['checkup_end_time'])) {
			$map['create_time'] = array(array('elt', strtotime($condition['checkup_end_time'])));
		} else if (is_time($condition['checkup_start_time'])) {
			$map['create_time'] = array(array('egt', strtotime($condition['checkup_start_time'])));
		}
		// 鉴定项目
		isset($condition['project']) && $map['checkup_org_item_id'] = $condition['project'];
		// 受委托单位
		isset($condition['checkup_org_id']) && $map['checkup_org_id'] = $condition['checkup_org_id'];
		// 结论做出时间
		if (is_time($condition['result_start_time']) && is_time($condition['result_end_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('egt', strtotime($condition['result_start_time'])), array('elt', strtotime($condition['result_end_time'])));
		} else if (is_time($condition['result_end_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('elt', strtotime($condition['result_end_time'])));
		} else if (is_time($condition['result_start_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('egt', strtotime($condition['result_start_time'])));
		}

//		dump($map);

		// 列表信息
		$Model = D('CaseCheckupStatistics');
		$count = $Model->where($map)->count('distinct CaseCheckup.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('CaseCheckup.id')->select();
//		exit($Model->_sql());
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Statistics/checkup/table');

	}

	/**
	 * 打印检验鉴定统计
	 */
	public function printCheckUp() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['is_cancel'] = 0;
		// 前台查询条件
		$condition = get_condition();
		// 所属大队
		isset($condition['department']) && $map['case_department_id'] = array('in', $condition['department']);
		// 办案人
		isset($condition['handle']) && $map['create_user_id'] = array('in', $condition['handle']);
		// 鉴定对象
		if (isset($condition['obj'])) {
			$map['_string'] = '
				(TargetCaseClient.name like "%' . $condition['obj'] . '%" and checkup_org_item_pid=1)
				or (TargetCaseClient.car_no like "%' . $condition['obj'] . '%" and checkup_org_item_pid=2)
				or (target_other like "%' . $condition['obj'] . '%" and checkup_org_item_pid=3)
			';
		}
		// 事故时间
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['case_accident_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['case_accident_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		// 检验鉴定时间
		if (is_time($condition['checkup_start_time']) && is_time($condition['checkup_end_time'])) {
			$map['create_time'] = array(array('egt', strtotime($condition['checkup_start_time'])), array('elt', strtotime($condition['checkup_end_time'])));
		} else if (is_time($condition['checkup_end_time'])) {
			$map['create_time'] = array(array('elt', strtotime($condition['checkup_end_time'])));
		} else if (is_time($condition['checkup_start_time'])) {
			$map['create_time'] = array(array('egt', strtotime($condition['checkup_start_time'])));
		}
		// 鉴定项目
		isset($condition['project']) && $map['checkup_org_item_id'] = $condition['project'];
		// 受委托单位
		isset($condition['checkup_org_id']) && $map['checkup_org_id'] = $condition['checkup_org_id'];
		// 结论做出时间
		if (is_time($condition['result_start_time']) && is_time($condition['result_end_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('egt', strtotime($condition['result_start_time'])), array('elt', strtotime($condition['result_end_time'])));
		} else if (is_time($condition['result_end_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('elt', strtotime($condition['result_end_time'])));
		} else if (is_time($condition['result_start_time'])) {
			$map['case_checkup_report_finish_time'] = array(array('egt', strtotime($condition['result_start_time'])));
		}
		// 列表信息
		$Model = D('CaseCheckupStatistics');
		$rs = $Model->where($map)->order('create_time desc')->group('CaseCheckup.id')->select();
		$list = array();
		foreach ($rs as $key => $val) {
			array_push($list, array(
				'department_name' => $val['department_name'],
				'handle_true_name' => $val['handle_true_name'],
				'target_name' => ($val['checkup_org_item_id'] == '4' ? $val['target_case_client_name'] : $val['target_case_car_no']),
				'case_accident_time' => date('Y-m-d H:i', $val['case_accident_time']),
				'create_time' => date('Y-m-d H:i', $val['create_time']),
				'checkup_org_item_name' => $val['checkup_org_item_name'],
				'checkup_org_name' => $val['checkup_org_name'],
				'finish_time' => date('Y-m-d H:i', $val['finish_time']),
			));
		}
		//////////////////////////////////////////////////统计/////////////////////////////////////////////////////////////////
		// 检验鉴定机构
		$map = array();
		$map['is_del'] = 0;
		$map['status'] = 1;
		$checkupOrg = M('CheckupOrg')->where($map)->select();
		$allArray = array();
		foreach ($checkupOrg as $key => &$value) {
			$map = array();
			$map['checkup_org_id'] = $value['id'];
			$child = D('CheckupOrgItemAccessView')->where($map)->group('CheckupOrgItem.id')->select();
			foreach ($child as $k => &$v) {
				$map = array();
				$map['checkup_org_id'] = $value['id'];
				$map['checkup_org_item_id'] = $v['id'];
				$map['is_del'] = 0;
				$map['is_cancel'] = 0;
				$v['_count'] = M('CaseCheckup')->where($map)->count();
				array_push($allArray, array(
					'name' => $value['name'],
					'check_up_name' => $v['name'],
					'num' => $v['_count'],
					'count' => count($child),
				));
			}
			unset($v);
		}
		//导出Excel
		//////////////////////////////////////////////////////////////////////检验鉴定统计Sheet//////////////////////////////////////////////////////////////////////
		$titleArray = array("所属大队", "办案人", "被鉴定人（车、物）", "事故时间", "检验鉴定时间", "鉴定项目", "受委托单位", "结论做出时间");
		//行位置
		$xAxisArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		//一共有多少行数据
		$rows = count($list);
		//一共有多少列数据
		$cells = 8;
		$xlsTitle = '检验鉴定统计';
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($xlsTitle);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:H1");
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
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(30);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:H2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($list[$j] as $key => $val) {
				if ($k < 8) {
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
		////////////////////////////////////////////////////////////////////////合计Sheet////////////////////////////////////////////////////////////////////////
		$titleArray = array("检验鉴定机构", "检验鉴定项目", "起数");
		//一共有多少行数据
		$rows = count($allArray);
		//一共有多少列数据
		$cells = 3;
		$xlsTitle = '合计';
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle($xlsTitle);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:C1");
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
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(30);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:C2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($allArray[$j] as $key => $val) {
				if ($k < 3) {
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
					$objPHPExcel->getActiveSheet()->mergeCells($xAxisArray[0] . ($z + 3) . ':' . $xAxisArray[0] . ($z + 3 + ($count - 1))); //检验鉴定机构合并单元格
				}
			}
		}
		$excelName = '检验鉴定统计';
		//导出文件
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $excelName . '.xls"');
		header("Content-Disposition:attachment;filename={$excelName}.xls"); //attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}

	public function car() {
		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);
		// 渲染
		$this->display();
	}

	/**
	 * 车辆停放费用统计合计
	 */
	public function parking() {
		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);
		// 车型
		$this->assign('grade_type', get_custom_config('grade_type'));

		// 停放地点
		$parking = get_custom_config('parking_lot');
		$this->assign('parking_lot', $parking);

		$park = array();

		foreach ($parking as $key => $value) {
			$map = array();
			$map['detain_parking'] = $key;
			$map['is_del'] = 0;
			$count = M('CaseClient')->where($map)->count();

			$item = array();
			$item['id'] = $key;
			$item['name'] = $value;
			$item['count'] = $count;

			$park[] = $item;

		}
		$this->assign('park', $park);
		// 渲染
		$this->display();
	}

	/**
	 * 车辆停放费用统计表格
	 */
	public function parkingTable() {

		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'detain_time':
			$orderby = 'CaseInfo.detain_time';
			break;
		case 'id':
			$orderby = 'CaseClient.id';
			break;
		case 'detain_return_time':
			$orderby = 'CaseCheckupReport.detain_return_time';
			break;
		default:
			$orderby = 'CaseClient.create_time';
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

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['detain_time'] = array('neq', 0);
		$condition = get_condition();

		// 大队
		isset($condition['department']) && $map['case_department_id'] = array('in', $condition['department']);
		// 办案人
		isset($condition['handle']) && $map['case_handle_user_id'] = array('in', $condition['handle']);

		// 车号
		isset($condition['car_no']) && $map['car_no'] = array('like', '%' . $condition['car_no'] . '%');

		// 车型
		isset($condition['grade_type']) && $map['grade_type'] = $condition['grade_type'];
		// 停放地点
		isset($condition['parking_lot']) && $map['detain_parking'] = $condition['parking_lot'];

		// 进场时间
		if (is_time($condition['detain_start_time']) && is_time($condition['detain_end_time'])) {
			$map['detain_time'] = array(array('egt', strtotime($condition['detain_start_time'])), array('elt', strtotime($condition['detain_end_time'])));
		} else if (is_time($condition['detain_end_time'])) {
			$map['detain_time'] = array(array('elt', strtotime($condition['detain_end_time'])));
		} else if (is_time($condition['detain_start_time'])) {
			$map['detain_time'] = array(array('egt', strtotime($condition['detain_start_time'])));
		}

		// 放行时间
		if (is_time($condition['return_start_time']) && is_time($condition['return_end_time'])) {
			$map['detain_return_time'] = array(array('egt', strtotime($condition['return_start_time'])), array('elt', strtotime($condition['return_end_time'])));
		} else if (is_time($condition['return_end_time'])) {
			$map['detain_return_time'] = array(array('elt', strtotime($condition['return_end_time'])));
		} else if (is_time($condition['return_start_time'])) {
			$map['detain_return_time'] = array(array('egt', strtotime($condition['return_start_time'])));
		}

		// 列表信息
		$Model = D('CaseParkingStatistics');
		$count = $Model->where($map)->count('distinct CaseClient.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseClient.id')->select();

		// 查询扩展信息
		foreach ($list as $key => &$value) {
			$value['grade_type_name'] = str_replace('号牌', '', get_custom_config('grade_type.' . $value['grade_type']));
			$value['detain_parking_name'] = get_custom_config('parking_lot.' . $value['detain_parking']);
		}
		unset($value);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Statistics/parking/table');
	}

	/**
	 * 打印车辆停放费用统计
	 */
	public function printPark() {
		////////////////////////////////////////////////车辆停放费用统计/////////////////////////////////////////////////////
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['detain_time'] = array('neq', 0);
		$condition = get_condition();

		// 大队
		isset($condition['department']) && $map['case_department_id'] = array('in', $condition['department']);
		// 办案人
		isset($condition['handle']) && $map['case_handle_user_id'] = array('in', $condition['handle']);

		// 车号
		isset($condition['car_no']) && $map['car_no'] = array('like', '%' . $condition['car_no'] . '%');

		// 车型
		isset($condition['grade_type']) && $map['grade_type'] = $condition['grade_type'];
		// 停放地点
		isset($condition['parking_lot']) && $map['detain_parking'] = $condition['parking_lot'];

		// 进场时间
		if (is_time($condition['detain_start_time']) && is_time($condition['detain_end_time'])) {
			$map['detain_time'] = array(array('egt', strtotime($condition['detain_start_time'])), array('elt', strtotime($condition['detain_end_time'])));
		} else if (is_time($condition['detain_end_time'])) {
			$map['detain_time'] = array(array('elt', strtotime($condition['detain_end_time'])));
		} else if (is_time($condition['detain_start_time'])) {
			$map['detain_time'] = array(array('egt', strtotime($condition['detain_start_time'])));
		}

		// 放行时间
		if (is_time($condition['return_start_time']) && is_time($condition['return_end_time'])) {
			$map['detain_return_time'] = array(array('egt', strtotime($condition['return_start_time'])), array('elt', strtotime($condition['return_end_time'])));
		} else if (is_time($condition['return_end_time'])) {
			$map['detain_return_time'] = array(array('elt', strtotime($condition['return_end_time'])));
		} else if (is_time($condition['return_start_time'])) {
			$map['detain_return_time'] = array(array('egt', strtotime($condition['return_start_time'])));
		}

		// 列表信息
		$Model = D('CaseParkingStatistics');
		$rs = $Model->where($map)->order('create_time desc')->group('CaseClient.id')->select();

		// 查询扩展信息
		foreach ($rs as $key => &$value) {
			$value['grade_type_name'] = get_custom_config('grade_type.' . $value['grade_type']);
			$value['detain_parking_name'] = get_custom_config('parking_lot.' . $value['detain_parking']);
		}
		unset($value);
		$list = array();
		foreach ($rs as $key => $val) {
			array_push($list, array(
				'department_name' => $val['department_name'],
				'case_handle_true_name' => $val['case_handle_true_name'],
				'car_no' => $val['car_no'],
				'grade_type_name' => str_replace('号牌', '', $val['grade_type_name']),
				'detain_parking_name' => $val['detain_parking_name'],
				'detain_time' => date('Y-m-d H:i', $val['detain_time']),
				'detain_return_time' => date('Y-m-d H:i', $val['detain_return_time']),
			));
		}
		////////////////////////////////////////////////合计/////////////////////////////////////////////////////
		// 停放地点
		$parking = get_custom_config('parking_lot');
		$park = array();
		foreach ($parking as $key => $value) {
			$map = array();
			$map['detain_parking'] = $key;
			$map['is_del'] = 0;
			$count = M('CaseClient')->where($map)->count();
			$item = array();
			$item['id'] = $key;
			$item['name'] = $value;
			$item['count'] = $count;
			$park[] = $item;
		}
		//导出Excel
		//////////////////////////////////////////////////////////////////////车辆停放费用统计Sheet//////////////////////////////////////////////////////////////////////
		$titleArray = array("所属大队", "办案人", "车号", "车型", "停放地点", "进场时间", "放行时间");
		//行位置
		$xAxisArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		//一共有多少行数据
		$rows = count($list);
		//一共有多少列数据
		$cells = 7;
		$xlsTitle = '车辆停放费用统计';
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($xlsTitle);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:G1");
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
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(30);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($list[$j] as $key => $val) {
				if ($k < 8) {
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
		////////////////////////////////////////////////////////////////////////合计Sheet////////////////////////////////////////////////////////////////////////
		$titleArray = array("编号", "车辆停放单位", "台次");
		//一共有多少行数据
		$rows = count($park);
		//一共有多少列数据
		$cells = 3;
		$xlsTitle = '合计';
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle($xlsTitle);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:C1");
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
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(30);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:C2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($park[$j] as $key => $val) {
				if ($k < 3) {
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
		$excelName = '车辆停放费用统计';
		//导出文件
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $excelName . '.xls"');
		header("Content-Disposition:attachment;filename={$excelName}.xls"); //attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}

	public function caseInfo() {
		$this->display();

	}

	/**
	 * 伤亡情况
	 */
	public function casualties() {
		$this->display();

	}
}

?>