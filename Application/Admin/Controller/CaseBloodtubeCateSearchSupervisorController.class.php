<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 领导采血管(组)查询
 */
class CaseBloodtubeCateSearchSupervisorController extends CommonController {

	/**
	 * 权限
	 */
	public function __construct() {
		parent::__construct();
		if (false === is_power($this->myPower, 'case_bloodtubecate_supervisor_normal,case_bloodtubecate_supervisor_advance,case_bloodtubecate_supervisor_low', 'or')) {
			$this->error('没有权限');
		}

	}
	/**
	 * 采血管查询
	 */
	public function index() {
		if (true === is_power($this->myPower, 'case_bloodtubecate_supervisor_advance')) {
			// 部门
			$department = $this->allDepartment;
			$department = list_to_tree($department);
			$department = tree_to_array($department);
			$this->assign('department', $department);

		} else {

			//获取大队ID
			$myBrigade = $this->getMyBrigade();
			$pid = $myBrigade['id'];
			// 部门
			$department = $this->allDepartment;
			//该部门以及该部门下所有子部门的主键id $allChild
			$allChild = get_all_child($department, $pid);
			// 部门人员
			$map = array();
			$map['is_del'] = 0;
			if (true === is_power($this->myPower, 'case_bloodtubecate_supervisor_normal')) {
				//普通权限bloodcate_normal可选择被派发人员
				$map['department_id'] = array('in', $allChild);
			} else {
				//低级权限bloodcate_low只能看到自己
				$map['id'] = $this->my['id'];
			}
			$allUsers = M('User')->where($map)->select();
			$this->assign('myBrigade', $myBrigade);
			$this->assign('allUsers', $allUsers);

		}

		//回收方式
		$recover_type = C('custom.recover_type');
		$this->assign('recover_type', $recover_type);
		$this->display();
	}
	/**
	 * ajax读取该大队下所有可选值班人员
	 */
	public function ajaxAllUsers() {
		$pid = I('post.uid', '', 'int');

		// 部门
		$department = $this->allDepartment;

		//该部门以及该部门下所有子部门的主键id $allChild
		$allChild = get_all_child($department, $pid);

		// 部门（大队）下所属人员
		$map = array();
		$map['is_del'] = 0;
		$map['department_id'] = array('in', $allChild);
		$allUsers = M('User')->where($map)->select();
		$this->success($allUsers);

	}

	/**
	 * 表格
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'accident_time':
			$orderby = 'Cases.accident_time';
			break;
		default:
			$orderby = 'Cases.create_time';
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
		$map['bloodtube_is_del'] = 0;
		//$map['case_is_del'] = array('exp', ' is NULL or Cases.is_del = 0');
		$condition = get_condition();
		isset($condition['case_id']) && $map['case_id'] = $condition['case_id'];
		isset($condition['bloodtube_code']) && $map['bloodtube_code'] = $condition['bloodtube_code'];
		isset($condition['is_used']) && $map['is_used'] = $condition['is_used'];
		isset($condition['is_recover']) && $map['bloodtube_is_recover'] = $condition['is_recover'];
		isset($condition['recover_type']) && $map['bloodtube_recover_type'] = $condition['recover_type'];
		//高级权限可筛选大队
		if (true === is_power($this->myPower, 'case_bloodtubecate_supervisor_advance')) {
			isset($condition['target_department_id']) && $map['target_department_id'] = $condition['target_department_id'];
		} else {
			//普通权限不可筛选大队
			$myBrigade = $this->getMyBrigade();
			$map['target_department_id'] = $myBrigade['id'];

		}
		//低级权限只能看到派发给自己的采血管数据
		if (true === is_power($this->myPower, 'case_bloodtubecate_supervisor_low') && false === is_power($this->myPower, 'case_bloodtubecate_supervisor_advance') && false === is_power($this->myPower, 'case_bloodtubecate_supervisor_normal')) {
			$map['target_user_id'] = $this->my['id'];
		} else {
			isset($condition['target_user_id']) && $map['target_user_id'] = $condition['target_user_id'];

		}
		//采血管派发时间筛选
		$start_time = isset($condition['start_time']) ? strtotime($condition['start_time']) : '1';
		$end_time = isset($condition['end_time']) ? strtotime($condition['end_time']) : time() + time();
		if (isset($condition['start_time']) or isset($condition['end_time'])) {
			$map['to_user_time'] = array('BETWEEN', "$start_time,$end_time");
		}
		//事故时间筛选
		$case_start_time = isset($condition['case_start_time']) ? strtotime($condition['case_start_time']) : '1';
		$case_end_time = isset($condition['case_end_time']) ? strtotime($condition['case_end_time']) : time() + time();
		if (isset($condition['case_start_time']) or isset($condition['case_end_time'])) {
			$map['case_id'] = array('neq', 0);
			$map['case_accident_time'] = array('BETWEEN', "$case_start_time,$case_end_time");
		}
		// 列表信息
		$Model = D('BloodtubeCateCaseView');
		$count = $Model->where($map)->count('distinct BloodtubeCate.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('BloodtubeCate.id')->select();
		// $data = $Model->getLastSql();
		// echo $data;
		//$excelData用作excel数据导出时进行筛选
		$excelData = array();
		foreach ($list as $key => $val) {
			$bloodtube_cate_id = $list[$key]['id'];
			$list[$key]['bloodtube'] = M('Bloodtube')->where("bloodtube_cate_id='$bloodtube_cate_id' and is_del=0")->select();
			$excelData[$key] = $list[$key]['id'];
		}
		$this->assign('list', $list);
		$this->assign('excelData', implode($excelData, ","));
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		// 组别类型
		$this->assign('dutyGroupType', get_custom_config('duty_group_type'));

		// 渲染
		$this->display('CaseBloodtubeCateSearchSupervisor/index/table');
	}

	//导出Excel
	public function expExcel() {
		if (empty($_GET['id'])) {
			$this->error('无可导出数据');
		}
		$allId = $_GET['id'];
		$allId = explode(",", $allId);
		$map['is_del'] = 0;
		$map['id'] = array('in', $allId);
		$map['department_is_del'] = 0;
		//$map['user_is_del'] = 0;
		$map['bloodtube_is_del'] = 0;

		$xlsData = D('BloodtubeCateExeclView')->where($map)->select();
		foreach ($xlsData as $key => $val) {
			if ($xlsData[$key]['to_user_time'] == 0 || $xlsData[$key]['to_user_time'] == null) {
				$xlsData[$key]['to_user_time'] = '无记录';
			} else {
				$xlsData[$key]['to_user_time'] = date('Y-m-d H:i', $xlsData[$key]['to_user_time']);

			}
			if ($xlsData[$key]['bloodtube_recover_time'] == 0 || $xlsData[$key]['bloodtube_recover_time'] == null) {
				$xlsData[$key]['bloodtube_recover_time'] = '无记录';
			} else {
				$xlsData[$key]['bloodtube_recover_time'] = date('Y-m-d H:i', $xlsData[$key]['bloodtube_recover_time']);

			}
		}
		$xlsName = "BloodtubeCate";
		$xlsCell = array(array('id', '编号'), array('bloodtube_code', '采血管编号'), array('department_name', '领取单位'), array('true_name', '领取人'), array('to_user_time', '领取时间'), array('bloodtube_recover_time', '回收时间'), array('destroy_time', '销毁时间'));
		$this->exportExcel($xlsName, $xlsCell, $xlsData);
	}

	public function exportExcel($expTitle, $expCellName, $expTableData) {
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
		$fileName = '采血管派发报表' . date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
		//合并表头单元格
		$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
		//设置表格title
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '采血管派发报表-导出时间:' . date('Y-m-d H:i:s'));

		//设置表格title格式居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//设置宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7); //编号宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); //采血管宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); //领取单位宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(7); //领取人宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15); //领取时间宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15); //回收宽度设置
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15); //销毁宽度设置

		//插入表格字段名
		for ($i = 0; $i < $cellNum; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
			//设置单元格文字居中
			$objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . '2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		}
		// Miscellaneous glyphs, UTF-8
		//往表格插入对应的数据
		for ($i = 0; $i < $dataNum + 1; $i++) {

			for ($j = 0; $j < $cellNum; $j++) {
				if ($j == 1) {
					//第二列数据转字符串 setCellValueExplicit
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]], \PHPExcel_Cell_DataType::TYPE_STRING);
				} else {
					$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]], \PHPExcel_Cell_DataType::TYPE_STRING);

				}
				//设置单元格文字居中
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

				//加边框
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 2))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 2))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 2))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 2))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

			}
		}
		//合并单元格
		for ($n = 0; $n < $dataNum; $n++) {
			$objPHPExcel->getActiveSheet(0)->mergeCells($cellName[0] . ($n + 3) . ':' . $cellName[0] . ($n + 4)); //编号合并单元格
			$objPHPExcel->getActiveSheet(0)->mergeCells($cellName[2] . ($n + 3) . ':' . $cellName[2] . ($n + 4)); //领取单位合并单元格
			$objPHPExcel->getActiveSheet(0)->mergeCells($cellName[3] . ($n + 3) . ':' . $cellName[3] . ($n + 4)); //领取人合并单元格
			$objPHPExcel->getActiveSheet(0)->mergeCells($cellName[4] . ($n + 3) . ':' . $cellName[4] . ($n + 4)); //领取时间合并单元格
			$n = $n + 1;
		}
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}

}