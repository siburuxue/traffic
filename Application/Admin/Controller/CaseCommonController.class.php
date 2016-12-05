<?php
namespace Admin\Controller;

/**
 * 案件通用类
 */
class CaseCommonController extends CommonController {

	/**
	 * 构造函数
	 */
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

		if ($case['case_handle_user_id'] != $this->my['id']) {
			$this->error('当前用户不是该案件办案人');
		}

		if (empty($myBrigade)) {
			$this->error('当前用户无大队信息');
		}

		if ($myBrigade['id'] !== $case['department_id']) {
			$this->error('当前案件所在部门与当前用户所在部门不符合');
		}

		// 当前用户所在大队
		$this->myBrigade = $myBrigade;

	}

	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	protected function getPhotoList($cate = 0, $extIdA = 0, $extIdB = 0, $extIdC = 0, $extIdD = 0, $extIdE = 0) {

		$map = array();
		$map['cate'] = $cate;
		$map['case_id'] = $this->case['id'];
		$map['is_del'] = 0;

		$extIdA && $map['ext_ida'] = $extIdA;
		$extIdB && $map['ext_idb'] = $extIdB;
		$extIdC && $map['ext_idc'] = $extIdC;
		$extIdD && $map['ext_idd'] = $extIdD;
		$extIdE && $map['ext_ide'] = $extIdE;

		$list = D('CasePhotoView')->where($map)->select();
		
		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);

		return $list;
	}

	/**
	 * 获取审批人员列表
	 * $powerName string 权限名称
	 */
	protected function getCheckUserList($powerName = '') {
		$list = array();

		// 当前用户所在大队下所有子部门
		$departmentIds = get_all_child($this->allDepartment, $this->myBrigade['id']);

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
	 * 提审
	 * $checkUserId int 审批人用户编号
	 * $checkType int 审批类型
	 * $itemId int 审批对象主键
	 */
	protected function submitCheck($checkUserId = 0, $checkType = 0, $itemId = 0) {

		$userId = $this->my['id'];
		$time = time();

		$data = array();
		$data['case_id'] = $this->case['id'];
		$data['cate'] = $checkType;
		$data['item_id'] = $itemId;
		$data['check_user_id'] = $checkUserId;
		$data['check_time'] = 0;
		$data['submit_user_id'] = $userId;
		$data['submit_time'] = $time;
		$data['status'] = 0;
		$data['remark'] = '';
		$data['create_time'] = $time;
		$data['create_user_id'] = $userId;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['is_del'] = 0;
		$model = D('CaseCheck');
		$para = $model->create($data);
		if ($para === false) {
			$this->error($model->getError());
		}
		return $model->add($para);
	}
}