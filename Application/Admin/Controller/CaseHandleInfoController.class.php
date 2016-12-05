<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 办案人 案件
 */
class CaseHandleInfoController extends CommonController {
	/**
	 * 编辑案件
	 */
	public function edit() {

		// 获取编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		if ($info['is_del'] == 1) {
			$this->error('案件已作废');
		}
		if ($info['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		// 案件初查
		$info['first_cognizance'] = explode(',', $info['first_cognizance']);

		$this->assign('info', $info);

		// 事故类型
		$this->assign('accidentType', get_custom_config('accident_type'));
		// 事故初查
		$this->assign('firstCognizance', get_dict('first_cognizance'));
		// 财产损失
		$this->assign('propertyLoss', get_dict('property_loss'));
		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {

		// 实例化模型
		$Model = D('Case');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 案件编号
		$id = $data['id'];

		// 案件权限
		$case = D('CaseView')->getById($id);
		if (empty($case)) {
			$this->error('案件不存在');
		}
		if ($case['is_del'] == 1) {
			$this->error('案件已作废');
		}
		if ($case['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		// 事故发生时间
		$data['accident_time'] = strtotime($data['accident_time']);
		// 死亡人数
		$data['death_num'] = empty($data['death_num']) ? 0 : $data['death_num'];
		// 受伤人数
		$data['hurt_num'] = empty($data['hurt_num']) ? 0 : $data['hurt_num'];
		// 事故初查
		$data['first_cognizance'] = '';
		$firstCognizance = I('post.first_cognizance');
		if (is_array($firstCognizance) && count($firstCognizance) > 0) {
			$map = array();
			$map['id'] = array('in', $firstCognizance);
			$firstCognizanceIds = M('DictOption')->where($map)->getField('id', true);
			if (count($firstCognizance) !== count($firstCognizanceIds)) {
				$this->error('请正确选择事故初查项');
			}
			$data['first_cognizance'] = implode(',', $firstCognizanceIds);
		} else {
			$this->error('事故初查至少选择一项');
		}

		// 开启事务
		$Model->startTrans();

		// 保存
		$result = $Model->save($data);

		// 数据保存失败
		if (!$result) {
			$Model->rollback();
			$this->error('数据保存失败');
		}

		// 成功
		$Model->commit();
		$this->success('更新成功');
	}

	/**
	 * 案件详情 办理页面
	 */
	public function detail() {
		// 案件编号
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$info = D('CaseView')->getById($id);
		if (empty($info)) {
			$this->error('案件不存在');
		}
		if ($info['is_del'] == 1) {
			$this->error('案件已作废');
		}
		if ($info['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		// 案件类型
		$info['accident_type_name'] = get_custom_config('accident_type.' . $info['accident_type']);

		// 案件初查
		$firstCognizanceStr = '';
		if (!empty($info['first_cognizance'])) {
			$firstCognizanceIds = explode(',', $info['first_cognizance']);
			$map = array();
			$map['id'] = array('in', $firstCognizanceIds);
			$map['is_del'] = 0;
			$firstCognizance = M('DictOption')->where($map)->getField('name', true);
			if (!empty($firstCognizance)) {
				$firstCognizanceStr = implode('，', $firstCognizance);
			}
		}
		$info['first_cognizance_name'] = $firstCognizanceStr;

		$this->assign('info', $info);

		// 搜索条件
		$map = array();
		$map['case_id'] = $id;

		// 关联报警信息列表
		$caseAlarmList = D('CaseAlarmView')->where($map)->order('create_time desc,alarm_id desc')->limit(2)->group('CaseAlarm.alarm_id')->select();
		$this->assign('caseAlarmList', $caseAlarmList);
		// dump($caseAlarmList);

		// 受案登记信息
		$map = array();
		$map['case_id'] = $id;
		$caseAccept = M('CaseAccept')->where($map)->find();
		$this->assign('caseAccept', $caseAccept);

		// 现场勘察
		$map = array();
		$map['case_id'] = $id;
		$caseSurvey = M('CaseSurvey')->where($map)->find();
		$this->assign('caseSurvey', $caseSurvey);

		// 当事人
		$map = array();
		$map['case_id'] = $id;
		$caseClient = M('CaseClient')->where($map)->limit(4)->select();
		$this->assign('caseClient', $caseClient);

		// 询问笔录
		$map = array();
		$map['case_id'] = $id;
		$caseRecord = M('CaseRecord')->where($map)->order('create_time desc')->limit(4)->group('id')->select();
		$this->assign('caseRecord', $caseRecord);

		// 事故认定
		//是否有有效的事故认定
		$map = array();
		$map['case_id'] = $id;
		$map['is_back'] = 0;
		$caseCognizance = M('CaseCognizance')->where($map)->find();
		$this->assign('caseCognizance',$caseCognizance);
		//事故认定是无法认定还是一般认定 0：一般认定
		$map = array();
		$map['case_id'] = $id;
		$map['traffic_type'] = array('neq',8);
		$map['blame_type'] = 6;
		$caseClientTypeList = M('CaseClient')->where($map)->select();
		$this->assign('cognizance_type',count($caseClientTypeList));
		//历史简易事故认定列表
		$map = array();
		$map['case_id'] = $id;
		$map['cognizance_type'] = 0;
		$simpleList = M('CaseCognizance')->where($map)->order('id desc')->select();
		//调查报告列表
		$map = array();
		$map['case_id'] = $id;
		$map['cognizance_type'] = 1;
		$map['is_last'] = 1;
		$normalList = D('CaseCognizanceReportView')->where($map)->order('id desc')->select();
		//按照时间倒序
		$list = array_merge($simpleList,$normalList);
		usort($list,function($a,$b){
			return $a['update_time'] < $b['update_time'];
		});
		$this->assign('list',$list);
		// 渲染
		$this->display();
	}
}