<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 支队鉴定中心
 */
class CaseCheckupDetachmentController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {

		// 部门
		$department = $this->allDepartment;
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);
		//委托事项
		$case_checkup_obj_type = C('custom.case_checkup_obj_type');
		$this->assign('case_checkup_obj_type', $case_checkup_obj_type);
		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'entrust_time':
			$orderby = 'CaseCheckupEntrust.entrust_time';
			break;
		case 'code':
			$orderby = 'CaseCheckupEntrust.code';
			break;
		case 'time_limit':
			$orderby = 'CaseCheckupEntrust.create_time';
			if ($orderSort == 1) {
				$time_limit = 1;
			} else {
				$time_limit = 2;
			}

			break;
		default:
			$orderby = 'CaseInfo.create_time';
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
		$map['status'] = 0;
		$map['is_finish'] = 0;
		$map['is_submit'] = 1;
		$map['is_first'] = 1;
		$map['case_checkup_is_del'] = 0;
		$map['case_checkup_is_cancel'] = 0;
		$condition = get_condition();
		isset($condition['accident_type']) && $map['case_accident_type'] = $condition['accident_type'];
		isset($condition['checkup_org_item_pid']) && $map['checkup_org_item_pid'] = $condition['checkup_org_item_pid'];
		isset($condition['checkup_org_item_id']) && $map['checkup_org_item_id'] = $condition['checkup_org_item_id'];
		isset($condition['target_name']) && $map['target_name'] = array('like', "%" . $condition['target_name'] . "%");
		isset($condition['code']) && $map['code'] = $condition['code'];
		isset($condition['from_department_id']) && $map['from_department_id'] = $condition['from_department_id'];
		isset($condition['start_time']) && $map['entrust_time'] = array('egt', strtotime($condition['start_time']));
		isset($condition['end_time']) && $map['entrust_time'] = array('elt', strtotime($condition['end_time']));
		// 列表信息
		$Model = D('CaseCheckupEntrustOrgCheckupView');
		$count = $Model->where($map)->count('distinct CaseCheckupEntrust.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('CaseCheckupEntrust.id')->select();
		$list = $this->calculateTimeLimit($list, 'case_id', $time_limit);
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		// 渲染
		$this->display('CaseCheckupDetachment/index/table');
	}

	/**
	 * 首页界面
	 */
	public function finish() {

		// 部门
		$department = $this->allDepartment;
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);

		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function finishTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'entrust_time':
			$orderby = 'CaseCheckupEntrust.entrust_time';
			break;
		case 'code':
			$orderby = 'CaseCheckupEntrust.code';
			break;
		default:
			$orderby = 'CaseInfo.create_time';
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
		$map['status'] = array('in', '1,2');
		$map['is_finish'] = 1;
		$map['is_submit'] = 1;
		$map['case_checkup_is_del'] = 0;
		$map['case_checkup_is_cancel'] = 0;
		$condition = get_condition();
		isset($condition['accident_type']) && $map['case_accident_type'] = $condition['accident_type'];
		isset($condition['target_name']) && $map['target_name'] = array('like', "%" . $condition['target_name'] . "%");
		isset($condition['code']) && $map['code'] = $condition['code'];
		isset($condition['from_department_id']) && $map['from_department_id'] = $condition['from_department_id'];
		isset($condition['start_time']) && $map['entrust_time'] = array('egt', strtotime($condition['start_time']));
		isset($condition['end_time']) && $map['entrust_time'] = array('elt', strtotime($condition['end_time']));

		// 列表信息
		$Model = D('CaseCheckupEntrustOrgCheckupView');
		$count = $Model->where($map)->count('distinct CaseCheckupEntrust.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('CaseCheckupEntrust.id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('CaseCheckupDetachment/finish/table');
	}

	/**
	 * 详情
	 */
	public function detail() {
		$id = I('get.id', '', 'strip_tags');
		$entrustData = D('CaseCheckupEntrustOrgCheckupView')->getById($id);
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $entrustData['case_id'];
		$caseData = M('Case')->where($map)->find();
		if ($caseData['id'] == $entrustData['case_id']) {
			$this->assign('caseData', $caseData);
		}
		$this->assign('entrustData', $entrustData);
		// 渲染
		$this->display();
	}
	//ajax接受委托
	public function setAccept() {
		$id = I('post.id', '', 'strip_tags');
		$data['id'] = $id;
		$data['status'] = 1;
		$data['is_finish'] = 1;
		$data['update_time'] = time();
		$res = D('CaseCheckupEntrust')->save($data);
		if ($res) {
			$this->success('接受委托成功');
		} else {
			$this->error('接受委托失败');

		}

	}

	/**
	 * 新增界面
	 */
	public function reEntrust() {
		$id = I('get.id', '', 'strip_tags');
		$entrustData = D('CaseCheckupEntrustOrgCheckupView')->getById($id);
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $entrustData['case_id'];
		$caseData = M('Case')->where($map)->find();
		if ($caseData['id'] == $entrustData['case_id']) {
			$this->assign('caseData', $caseData);
		}
		$this->assign('entrustData', $entrustData);

		//获取大队ID
		$myBrigade = $this->getMyBrigade();
		$brigadeId = $myBrigade['id'];
		$this->assign('brigadeId', $brigadeId);
		// 部门
		$department = $this->allDepartment;
		$department = list_to_tree($department);
		$department = tree_to_array($department);
		$this->assign('department', $department);

		//鉴定机构鉴定机构名称
		$map = array();
		$map['is_del'] = 0;
		$orgData = D('CheckupOrg')->where($map)->select();
		$this->assign('orgData', $orgData);

		// 渲染
		$this->display();
	}

	public function reEntrustInsert() {

		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('CaseCheckupEntrust');
		$caseCheckupEntrustData = $Model2->create();
		if ($caseCheckupEntrustData === false) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		$id = I('post.id', '', 'strip_tags');
		$caseCheckupEntrustData['is_first'] = 0;
		$caseCheckupEntrustData['train'] = 0;
		$caseCheckupEntrustData['code'] = '20160000';
		if ($id) {
			$result = M('CaseCheckupEntrust')->save($caseCheckupEntrustData);

		} else {
			$map = array();
			$map['case_checkup_id'] = I('post.case_checkup_id', '', 'strip_tags');
			$map['status'] = 0;
			$entrustDataOne = M('CaseCheckupEntrust')->where($map)->find();
			if ($entrustDataOne['case_checkup_id'] != $map['case_checkup_id'] || $entrustDataOne['status'] != 0) {
				$Model->rollback();
				$this->error('操作失败');
			}
			$data['id'] = $entrustDataOne['id'];
			$data['status'] = 2;
			$data['is_finish'] = 1;
			$data['update_time'] = time();
			$save = M('CaseCheckupEntrust')->save($data);
			if (!$save) {
				$Model->rollback();
				$this->error('操作失败');
			}
			$result = M('CaseCheckupEntrust')->add($caseCheckupEntrustData);
			$caseCheckupEntrustData['id'] = $result;
			$id = $result;

		}
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		$case_id = I('post.case_id', '', 'strip_tags');
		$case_checkup_id = I('post.case_checkup_id', '', 'strip_tags');
		$url = U('reEntrust', array('case_id' => $case_id, 'case_checkup_id' => $case_checkup_id, 'id' => $id, 'save' => '1'));
		$this->success('操作成功', $url);
	}

	/**
	 * AJAX读取委托事项
	 */
	public function getTypeChild() {
		$type_id = I('post.pid', '', 'strip_tags');
		//鉴定类型
		$map = array();
		$map['is_del'] = 0;
		$map['checkup_org_obj'] = $type_id;
		$allChildren['checktype'] = D('CheckupOrgItem')->where($map)->order('create_time desc')->select();
		$this->success($allChildren);
	}

}
