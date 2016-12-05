<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 受案信息 - 关联报警信息
 */
class CaseAlarmHandleController extends CaseController {

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
	 * 未关联表格
	 */
	public function searchTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0; // 未作废
		$map['is_link'] = 0; // 未关联
		$map['handle_type'] = array('neq', 0); // 处理状态不为未处理的

		$condition = get_condition();
		if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
			$map['alarm_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['end_time'])) {
			$map['alarm_time'] = array(array('elt', strtotime($condition['end_time'])));
		} else if (is_time($condition['start_time'])) {
			$map['alarm_time'] = array(array('egt', strtotime($condition['start_time'])));
		}
		isset($condition['case_source']) && $map['case_source'] = $condition['case_source'];
		isset($condition['alarm_name']) && $map['alarm_name'] = $condition['alarm_name'];

		// 列表信息
		$Model = D('AlarmView');
		$count = $Model->where($map)->count('distinct Alarm.id');
		$page = new Page($count, 10);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('Alarm.id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseAlarmHandle/index/searchTable');
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
	 * 关联
	 */
	public function link() {
		// 获取参数
		$ids = I('post.ids');
		$time = time();
		$userId = $this->my['id'];
		$caseId = $this->case['id'];

		// 报警信息编号验证
		if (empty($ids)) {
			$this->error('请选择需要关联的报警信息');
		}
		if (!is_array($ids) || count($ids) <= 0) {
			$this->error('非法操作');
		}

		// 报警信息是否有效
		$map = array();
		$map['id'] = array('in', $ids);
		$map['handle_type'] = array('neq', 0);
		$map['is_link'] = 0;
		$map['is_del'] = 0;
		$alarmIds = M('Alarm')->where($map)->getField('id', true);
		if (empty($alarmIds) || count($ids) !== count($alarmIds)) {
			$this->error('非法操作');
		}

		// 实例化数据模型
		$Model = M('CaseAlarm');

		// 报警信息是否已被绑定
		$map = array();
		$map['alarm_id'] = array('in', $alarmIds);
		$unique = $Model->where($map)->find();
		if (!empty($unique)) {
			$this->error('非法操作');
		}

		// 开启事务
		$Model->startTrans();

		// 处理报警信息
		$data = array();
		foreach ($alarmIds as $value) {
			$item = array();
			$item['case_id'] = $caseId;
			$item['alarm_id'] = $value;
			$item['create_time'] = $time;
			$item['create_user_id'] = $userId;
			$data[] = $item;

			// 更新报警信息关联状态
			$alarmData = array();
			$alarmData['id'] = $value;
			$alarmData['is_link'] = 1;
			$alarmData['case_id'] = $caseId;
			$alarmData['update_time'] = $time;
			$alarmData['update_user_id'] = $userId;
			$res = M('Alarm')->save($alarmData);

			// 保存异常
			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		}

		// 保存关联信息
		$result = $Model->addAll($data);

		// 异常
		if (!$result) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 提交
		$Model->commit();

		// 返回结果
		$this->success('关联成功');
	}

	/**
	 * 取消关联
	 */
	public function unlink() {
		// 获取参数
		$time = time();
		$ids = I('post.ids');
		$caseId = $this->case['id'];
		$userId = $this->my['id'];

		// 报警信息编号验证
		if (empty($ids)) {
			$this->error('请选择需要取消关联的报警信息');
		}
		if (!is_array($ids) || count($ids) <= 0) {
			$this->error('非法操作');
		}

		// 实例化数据模型
		$Model = M('CaseAlarm');

		// 开启事务
		$Model->startTrans();

		// 删除关联
		$map = array();
		$map['case_id'] = $caseId;
		$map['alarm_id'] = array('in', $ids);
		$deleteNum = $Model->where($map)->delete();
		if (!$deleteNum || intval($deleteNum) !== count($ids)) {
			$Model->rollback();
			$this->error('取消关联失败');
		}

		// 处理报警信息
		foreach ($ids as $value) {
			// 更新报警信息关联状态
			$alarmData = array();
			$alarmData['id'] = $value;
			$alarmData['is_link'] = 0;
			$alarmData['case_id'] = 0;
			$alarmData['update_time'] = $time;
			$alarmData['update_user_id'] = $userId;
			$res = M('Alarm')->save($alarmData);

			// 保存异常
			if (!$res) {
				$Model->rollback();
				$this->error('数据保存失败');
			}
		}

		// 提交
		$Model->commit();

		// 返回结果
		$this->success('取消关联成功');
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