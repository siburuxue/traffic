<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 受案信息 - 关联报警信息
 */
class CaseAlarmHandleInfoController extends CommonController {

	public function __construct() {

		parent::__construct();

		// 获取案件编号
		$caseId = I('request.case_id', '', '');
		if ($caseId === '') {
			$this->error('无效的案件编号');
		}

		// 获取案件信息
		$case = D('CaseView')->getById($caseId);

		if (empty($case)) {
			$this->error('案件不存在');
		}

		if ($case['is_del'] == 1) {
			$this->error('案件已作废');
		}

		// 当前案件信息
		$this->case = $case;

		// 当前用户所在大队信息
		$myBrigade = $this->getMyBrigade();

		// if ($case['case_handle_user_id'] != $this->my['id']) {
		// 	$this->error('当前用户不是该案件办案人');
		// }

		// if (empty($myBrigade)) {
		// 	$this->error('当前用户无大队信息');
		// }

		// if ($myBrigade['id'] !== $case['department_id']) {
		// 	$this->error('当前案件所在部门与当前用户所在部门不符合');
		// }

		// 当前用户所在大队
		$this->myBrigade = $myBrigade;

	}

	/**
	 * 首页
	 */
	public function index() {
		// 案件来源
		$this->assign('caseSource', get_dict('case_source'));

		// 渲染
		$this->display();
	}

	/**
	 * 已关联表格
	 */
	public function linkedTable() {
		// 搜索条件
		$map = array();
		$map['case_id'] = $this->case['id'];

		// 列表信息
		$Model = D('CaseAlarmView');
		$count = $Model->where($map)->count('distinct CaseAlarm.alarm_id');
		$page = new Page($count, 10);
		$list = $Model->where($map)->order('create_time desc,alarm_id desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseAlarm.alarm_id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseAlarmHandle/index/linkedTable');
	}

	/**
	 * 报警信息详情
	 */
	public function detail() {
		// 报警编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 报警信息
		$info = D('AlarmView')->getById($id);
		if (empty($info)) {
			$this->error('报警信息不存在');
		}
		$this->assign('info', $info);

		// 肇事车辆
		$map = array();
		$map['alarm_id'] = $id;
		$alarmAccidentCar = D('AlarmAccidentCarView')->where($map)->select();
		$this->assign('alarmAccidentCar', $alarmAccidentCar);

		// 逃逸车辆
		$map = array();
		$map['alarm_id'] = $id;
		$alarmEscapeCar = D('AlarmEscapeCarView')->where($map)->select();
		$this->assign('alarmEscapeCar', $alarmEscapeCar);

		// 现场处置
		$map = array();
		$map['alarm_id'] = $id;
		$alarmProcess = M('AlarmProcess')->where($map)->select();
		$this->assign('alarmProcess', $alarmProcess);

		// 渲染
		$this->display();
	}

}