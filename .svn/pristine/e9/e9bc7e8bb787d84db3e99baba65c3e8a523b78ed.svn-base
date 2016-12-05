<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 采血管(组)
 */
class BloodtubeCateController extends CommonController {

	/**
	 * 权限
	 */
	public function __construct() {
		parent::__construct();
		if (false === is_power($this->myPower, 'bloodtubecate_normal,bloodtubecate_advance,bloodtubecate_low,bloodtubecate_dispatch_one,bloodtubecate_dispatch_two,bloodtubecate_recover', 'or')) {
			$this->error('没有权限');
		}

	}
	/**
	 * 采血管查询
	 */
	public function index() {
		if (true === is_power($this->myPower, 'bloodtubecate_advance')) {
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
			if (true === is_power($this->myPower, 'bloodtubecate_normal')) {
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
		$this->assign('department', $department);

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
		case 'to_user_time':
			$orderby = 'BloodtubeCate.to_user_time';
			break;
		case 'case_id':
			$orderby = 'Cases.id';
			break;
		case 'accident_time':
			$orderby = 'Cases.accident_time';
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
		$map['bloodtube_is_del'] = 0;
		//$map['case_is_del'] = array('exp', ' is NULL or Cases.is_del = 0');

		$condition = get_condition();
		isset($condition['case_id']) && $map['case_id'] = $condition['case_id'];
		isset($condition['bloodtube_code']) && $map['bloodtube_code'] = $condition['bloodtube_code'];
		isset($condition['is_used']) && $map['is_used'] = $condition['is_used'];
		isset($condition['is_recover']) && $map['bloodtube_is_recover'] = $condition['is_recover'];
		isset($condition['recover_type']) && $map['bloodtube_recover_type'] = $condition['recover_type'];
		//高级权限可筛选大队
		if (true === is_power($this->myPower, 'bloodtubecate_advance')) {
			isset($condition['target_department_id']) && $map['target_department_id'] = $condition['target_department_id'];
		} else {
			//普通权限不可筛选大队
			$myBrigade = $this->getMyBrigade();
			$map['target_department_id'] = $myBrigade['id'];

		}
		//低级权限值能看到派发给自己的采血管数据
		if (true === is_power($this->myPower, 'bloodtubecate_low') && false === is_power($this->myPower, 'bloodtubecate_advance') && false === is_power($this->myPower, 'bloodtubecate_normal')) {
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
		$this->display('BloodtubeCate/index/table');
	}

	/**
	 * 修改使用状态
	 */
	public function used() {
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if (!$id) {
			$this->error('数据异常');
		}
		$Model = D('BloodtubeCateUsedView');

		$map = array();
		$map['bloodtube_id'] = $id;
		$map['bloodtube_is_del'] = 0;
		$info = $Model->where($map)->find();
		$this->assign('info', $info);
		$this->display();
	}

	/**
	 * 执行修改使用状态
	 */
	public function usedUpdate() {
		$Model = new \Think\Model();
		$Model->startTrans();

		$Model2 = D('BloodtubeUsedLog');
		$UsedLogData = $Model2->create();
		if (false === $UsedLogData) {
			$this->error($Model2->getError());
		}
		$UsedLogData['reason'] = I('post.reason', '', 'strip_tags');
		$addUsedLog = $Model->table(C('DB_PREFIX') . 'bloodtube_used_log')->add($UsedLogData);

		$BloodtubeCateDate['id'] = I('post.bloodtube_cate_id', '', 'strip_tags');
		$BloodtubeCateDate['is_used'] = I('post.status', '', 'strip_tags');
		$BloodtubeCateDate['update_time'] = time();
		$BloodtubeCateDate['update_user_id'] = I('post.user_id', '', 'strip_tags');
		$saveCate = $Model->table(C('DB_PREFIX') . 'bloodtube_cate')->save($BloodtubeCateDate);

		if (!$saveCate or !$addUsedLog) {
			$Model->rollback();
			$this->error('使用状态修改失败');

		}
		$Model->commit();
		$this->success('使用状态修改成功');
	}

	/**
	 * 使用状态修改记录
	 */
	public function usedLog() {
		$bloodtube_cate_id = I('get.bloodtube_cate_id', '', 'strip_tags');
		if (!$bloodtube_cate_id) {
			$this->error('参数无效');
		}
		$this->bloodtube_cate_id = $bloodtube_cate_id;
		$this->display();
	}

	/**
	 * 使用状态修改表格
	 */
	public function usedLogTable() {

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['bloodtube_cate_id'] = I('get.bloodtube_cate_id', '', 'strip_tags');
		// 列表信息
		$Model = D('BloodtubeUsedLog');
		$count = $Model->where($map)->count();
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->select();

		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('BloodtubeCate/usedLog/table');
	}

	/**
	 * 支队采血管派发
	 */
	public function grantDetachment() {
		if (false === is_power($this->myPower, 'bloodtubecate_dispatch_one')) {
			$this->error('没有相关权限');
		}

		// 部门
		$department = $this->allDepartment;
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);
		$this->assign('nowtime', time());
		$this->display();
	}

	/**
	 * 执行支队采血管派发
	 */
	public function grantDetachmentInsert() {
		if (false === is_power($this->myPower, 'bloodtubecate_dispatch_one')) {
			$this->error('没有相关权限');
		}

		//将日期转换为秒
		$_POST['to_department_time'] = strtotime($_POST['to_department_time']);
		$Model = new \Think\Model();
		$Model->startTrans();
		//采血分组表操作
		$Model2 = D('BloodtubeCate');
		$BloodtubeCateData = $Model2->create();
		if (false === $BloodtubeCateData) {
			$this->error($Model2->getError());
		}
		$cateid = $Model->table(C('DB_PREFIX') . 'bloodtube_cate')->add($BloodtubeCateData);

		if (!$cateid) {
			$Model->rollback();
			$this->error('采血管编组数据添加');

		}
		//采血表操作
		//获取同组两支采血管
		$codes = array();
		$codes[0] = $_POST['code_1'];
		$codes[1] = $_POST['code_2'];
		if (empty($codes[0]) || empty($codes[1])) {
			$Model->rollback();
			$this->error('请填写两支采血管的编号');
		}
		if ($_POST['code_1'] == $_POST['code_2']) {
			$Model->rollback();
			$this->error('请勿重复扫描同一个采血管');
		}
		$Model3 = D('Bloodtube');
		//添加同组两支采血管记录 for循环添加
		for ($i = 0; $i < count($codes); $i++) {
			$_POST['code'] = $codes[$i];
			$_POST['bloodtube_cate_id'] = $cateid;
			if (!$_POST['code']) {
				$Model->rollback();
				$this->error('采血管编号获取失败');
			}
			$code = $_POST['code'];
			$existCode = $Model->table(C('DB_PREFIX') . 'bloodtube')->where("code='$code' and is_del=0")->find();
			if ($existCode) {
				$Model->rollback();
				$this->error('编号为' . $code . '采血管已经派发,不可重复派发');
			}
			if ($_POST['code'] != "") {
				$BloodtubeData = "";
				$BloodtubeData = $Model3->create();
				if (false === $BloodtubeData) {
					$this->error($Model3->getError());
				}
				$bloodtubeid = "";
				$bloodtubeid = $Model->table(C('DB_PREFIX') . 'bloodtube')->add($BloodtubeData);
				if (!$bloodtubeid) {
					$Model->rollback();
					$this->error('采血管数据添加失败');
				}

			}
		}
		//最后再次确认两支采血管完全添加是否成功
		if (!$bloodtubeid) {
			$Model->rollback();
			$this->error('采血管数据添加失败');
		}
		$Model->commit();
		$this->success('采血管数据添加成功');

	}

	/**
	 * 大队采血管派发
	 */
	public function grantRegiment() {
		if (false === is_power($this->myPower, 'bloodtubecate_dispatch_two')) {
			$this->error('没有相关权限');
		}

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
		$map['department_id'] = array('in', $allChild);
		$allUsers = M('User')->where($map)->select();
		$this->assign('allUsers', $allUsers);
		$time = time() + 300;
		$this->assign('nowtime', $time);

		$this->display();
	}
	/**
	 * 执行大队采血管派发
	 */
	public function grantRegimentUpdate() {

		if (false === is_power($this->myPower, 'bloodtubecate_dispatch_two')) {
			$this->error('没有相关权限');
		}

		$id = I('post.id', '', 'strip_tags');
		if (!$id) {
			$this->error('未能读取到有效采血管分组信息');

		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$bloodtubeCateData = M('BloodtubeCate')->where($map)->find();
		//检验数据是否合法
		if (!$bloodtubeCateData) {
			$this->error('未能读取到有效采血管信息');
		}
		if ($bloodtubeCateData['target_user_id'] != '0') {
			$this->error('操作失败：该组采血管已从大队派发');
		}
		if (false === is_power($this->myPower, 'bloodtubecate_advance')) {

			$myBrigade = $this->getMyBrigade();
			if ($bloodtubeCateData['target_department_id'] != $myBrigade['id']) {
				$this->error('操作失败：该组采血管不属于当前操作人员所在大队');
			}
		}
		//开启事务
		$Model = new \Think\Model();
		$Model->startTrans();

		$Model2 = D('BloodtubeCate');
		$data = $Model2->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['id'] = $bloodtubeCateData['id'];
		//更新添加值
		$data['to_user_time'] = strtotime($_POST['to_user_time']);
		$data['to_user_user_id'] = $_POST['to_user_user_id'];
		$data['target_user_id'] = $_POST['target_user_id'];
		$data['is_to_user'] = 1;
		// if ($data['to_user_time'] < time()) {
		// 	$this->error('派发时间不能晚于' . date('Y-m-d H:i', time()));
		// }
		if ($data['to_user_user_id'] == "") {
			$this->error('请选择派发人');
		}
		if ($data['target_user_id'] == "") {
			$this->error('请选择被派发人');
		}

		$cateSave = $Model->table(C('DB_PREFIX') . 'bloodtube_cate')->save($data);
		if (!$cateSave) {
			$Model->rollback();
			$this->error('采血管派发失败');
		}
		$Model->commit();
		$this->success('采血管派发成功');
	}

	/**
	 * 大队采血管派发---扫码读数据---Ajax
	 */
	public function grantRegimentAjax() {

		$code = I('post.code', '', 'strip_tags');
		if (empty($code)) {
			$this->error('未能读取到有效编码');
		}

		//读取采血管信息 获取采血管分组id bloodtube_cate_id
		$map = array();
		$map['is_del'] = 0;
		$map['code'] = $code;
		$bloodtubeData = M('Bloodtube')->where($map)->find();

		//读取采血管分组信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $bloodtubeData['bloodtube_cate_id'];
		$allData = D('BloodtubeCateView')->where($map)->order('bloodtube_id desc')->select();
		if (!$allData) {
			$this->error('采血管信息获取失败！');
		}
		//获取当前操作人员大队ID
		$myBrigade = $this->getMyBrigade();
		$pid = $myBrigade['id'];
		//判断该组采血管是否分配到当前的操作人员所在大队
		if ($allData[0]['target_department_id'] != $pid) {
			$this->error('该采血管未分配到当前操作人员所在的大队');
		}
		$this->success($allData);
	}

	/**
	 * 采血管回收
	 */
	public function recover() {
		if (false === is_power($this->myPower, 'bloodtubecate_recover')) {
			$this->error('没有相关权限');
		}

		$recover_type = C('custom.recover_type');
		$this->assign('recover_type', $recover_type);
		$time = time() + 300;
		$this->assign('nowtime', $time);

		$this->display();
	}

	/**
	 * 采血管回收执行
	 */
	public function recoverUpdate() {
		if (flase === is_power($this->myPower, 'bloodtubecate_recover')) {
			$this->error('没有相关权限');
		}

		$code = I('post.code', '', 'strip_tags');
		$recover_time = I('post.recover_time', '', 'strip_tags');
		$recover_time = strtotime($recover_time);
		$recover_type = I('post.recover_type', '', 'strip_tags');
		$recover_user_id = I('post.recover_user_id', '', 'strip_tags');

		if (empty($code)) {
			$this->error('采血管编码未读取');

		}
		if (empty($recover_time)) {
			$this->error('回收时间未填写');

		}
		if (empty($recover_type)) {
			$this->error('回收方式未选择');

		}
		if (empty($recover_user_id)) {
			$this->error('回收人员未信息不存在');

		}

		if ($recover_time < time()) {
			$this->error('回收时间不能晚于' . date('Y-m-d H:i', time()));
		}
		//开启事务
		$Model = new \Think\Model();
		$Model->startTrans();

		$map = array();
		$map['is_del'] = 0;
		$map['code'] = $code;
		$bloodtubeData = $Model->table(C('DB_PREFIX') . 'bloodtube')->where($map)->find();
		if (!$bloodtubeData) {
			$this->error('未能读取到有效采血管信息');

		}
		if ($bloodtubeData['is_recover'] == 1) {
			$this->error('该采血管已经在' . date('Y-m-d H:i', $bloodtubeData['recover_time']) . "被回收<br/>请不要重复回收");
		}

		$Model2 = D('Bloodtube');
		$newData = $Model2->create();
		if (false === $newData) {
			$this->error($Model->getError());
		}
		$newData['id'] = $bloodtubeData['id'];
		$newData['recover_time'] = $recover_time;
		$newData['recover_type'] = $recover_type;
		$newData['recover_user_id'] = $recover_user_id;
		$newData['is_recover'] = 1;
		$bloodtubeSave = $Model->table(C('DB_PREFIX') . 'bloodtube')->save($newData);
		if (!$bloodtubeSave) {
			$this->error('采血管回收失败');
		}

		$Model->commit();
		$this->success('采血管回收成功');

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
		$fileTitle = iconv('utf-8', 'gb2312', '采血管派发报表');
		$fileName = $fileTitle . date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
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