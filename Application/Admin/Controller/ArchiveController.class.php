<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 档案
 */
class ArchiveController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		// 事故处理等级
		$this->assign('trafficLevel', get_dict('traffic_level'));

		// 职务
		$map = array();
		$map['is_del'] = 0;
		$post = M('Post')->where($map)->select();
		$this->assign('post', $post);

		// 角色
		$map = array();
		$map['is_del'] = 0;
		$role = M('Role')->where($map)->select();
		$this->assign('role', $role);

		// 部门
		$department = tree_to_array(list_to_tree($this->allDepartment));
		$this->assign('department', $department);

		// 渲染
		$this->display();
	}

	/**
	 * 主页表格界面
	 */
	public function indexTable() {
		//排序start
		$orderField = I('post.field', '', 'trim,htmlspecialchars');
		$orderSort = I('post.sort', '', 'int');

		switch ($orderField) {
		case 'create_time':
			$orderby = 'Archive.create_time';
			break;
		case 'case_info_code':
			$orderby = 'CaseInfo.code';
			break;
		case 'id':
			$orderby = 'Archive.id';
			break;
		default:
			$orderby = 'Archive.create_time';
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
		$map['case_info_is_del'] = 0;

		$map['department_is_del'] = 0;
		$map['user_is_del'] = 0;
		$condition = get_condition();
		isset($condition['code']) && $map['code'] = array('like', '%' . $condition['code'] . '%');
		isset($condition['status']) && $map['status'] = $condition['status'];
		isset($condition['case_info_code']) && $map['case_info_code'] = array('like', '%' . $condition['case_info_code'] . '%');
		isset($condition['catalog']) && $map['catalog'] = $condition['catalog'];
		isset($condition['dossier']) && $map['dossier'] = $condition['dossier'];
		isset($condition['department_id']) && $map['department_id'] = $condition['department_id'];
		isset($condition['name']) && $map['name'] = array('like', '%' . $condition['name'] . '%');
		isset($condition['user_name']) && $map['user_name'] = array('like', '%' . $condition['user_name'] . '%');
		isset($condition['start_time']) && $map['create_time'] = array('egt', strtotime($condition['start_time']));
		isset($condition['end_time']) && $map['create_time'] = array('elt', strtotime($condition['end_time']));
		// 列表信息
		$Model = D('ArchiveView');
		$count = $Model->where($map)->count('distinct Archive.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order($orderby)->limit($page->firstrow . ',' . $page->rows)->group('Archive.id')->select();
		$this->assign('list', $list);

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		// // 渲染
		$this->display('Archive/index/table');
	}

	/**
	 * 归档界面
	 */
	public function returnBack() {

		// 渲染
		$this->display();

	}

	/**
	 * 归档表格界面
	 */
	public function returnBackTable() {
		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['cate'] = 0;
		//$map['case_client_is_del'] = 0;
		$condition = get_condition();

		$map['accident_time'] = array('egt', '10');
		isset($condition['code']) && $map['code'] = array('like', '%' . $condition['code'] . '%');
		isset($condition['accident_place']) && $map['accident_place'] = array('like', '%' . $condition['accident_place'] . '%');
		isset($condition['case_client_name']) && $map['case_client_name'] = array('like', '%' . $condition['case_client_name'] . '%');
		isset($condition['case_client_idno']) && $map['case_client_idno'] = $condition['case_client_idno'];
		isset($condition['start_time']) && $map['accident_time'] = array('egt', strtotime($condition['start_time']));
		isset($condition['end_time']) && $map['accident_time'] = array('elt', strtotime($condition['end_time']));
		//dump($map);exit;
		// 列表信息
		$Model = D('CaseCaseClientView');
		//echo $Model->buildSql();
		//exit;
		$count = $Model->where($map)->count('distinct CaseInfo.id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseInfo.id')->select();
		//echo $Model->getLastSql();exit;
		// 查询扩展信息
		foreach ($list as $key => $value) {
			// 职务
			$where = array();
			$where['id'] = $value['case_client_id'];
			$where['is_del'] = 0;
			$caseClient = D('CaseClient')->where($where)->select();
			$list[$key]['caseClient'] = empty($caseClient) ? '' : $caseClient;
			//过滤掉已经归档的案件
			$map = array();
			$map['case_id'] = $list[$key]['id'];
			$map['is_del'] = 0;
			$caseArchive = D('Archive')->where($map)->find();
			if ($caseArchive) {
				$list[$key] = "";
			}

		}
		//dump($list);
		//dump($list[8]['caseClient']);
		$list = array_filter($list);
		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);

		$this->assign('page', $pageInfo);

		// 渲染
		$this->display('Archive/returnBack/table');
	}
	//归档插入数据
	public function returnBackInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('Archive');
		$archiveData = $Model2->create();
		if (false === $archiveData) {
			$this->error($Model2->getError());
		}
		//查询该案件是否已经归档
		$map = array();
		$map['is_del'] = 0;
		$map['case_id'] = $archiveData['case_id'];
		$findArchive = $Model->table(C('DB_PREFIX') . 'archive')->where($map)->find($map);
		if ($findArchive) {
			$Model->rollback();
			$this->error('该案件已经归档');
		}
		//查询案件的详细信息
		$map = array();
		$map['is_del'] = 0;
		$map['case_handle_is_del'] = 0;
		$map['id'] = $archiveData['case_id'];
		$caseHandleDate = D('CaseHandleView')->where($map)->find();
		if (empty($caseHandleDate)) {
			$this->error('未找到相应案件细信息');
		}

		$archiveData['handle'] = $caseHandleDate['case_handle_user_id'];
		$archiveData['department_id'] = $caseHandleDate['department_id'];
		// $archiveData['handle'] = $this->my['id'];
		// $brigade = $this->getMyBrigade();
		// $archiveData['department_id'] = $brigade['id'];
		if (empty($archiveData['handle'])) {
			$Model->rollback();
			$this->error('办案人信息读取错误');
		}
		if (empty($archiveData['department_id'])) {
			$Model->rollback();
			$this->error('办案人所在大队信息读取错误');
		}
		$addArchive = $Model->table(C('DB_PREFIX') . 'archive')->add($archiveData);
		if (!$addArchive) {
			$Model->rollback();
			$this->error('归档失败');

		}
		$caseData['id'] = $archiveData['case_id'];
		$caseData['catalog_status'] = 1;
		$result = $Model->table(C('DB_PREFIX') . 'case')->save($caseData);
		if (!$result) {
			$Model->rollback();
			$this->error('归档失败');

		}
		//设置工作日志
		//在【归档保存时间】对【事故编号】进行归档
		$L = '';
		$L = '';
		$content = '在' . $L . date('Y-m-d H:i', time()) . $R . '对' . $L . $caseHandleDate['code'] . $R . "进行归档";
		$case_id = $archiveData['case_id'];
		$this->saveCaseLog($case_id, $content);
		$Model->commit();
		$this->success('归档成功');
	}

	/**
	 * 提档界面
	 */
	public function borrowOut() {
		//权限archive_borrow_out
		if (false === is_power($this->myPower, 'archive_borrow_out')) {
			$this->error('无相关权限');
		}
		//判断参数id是否合法
		$id = I('get.id', '', 'strip_tags');
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		//判断该档案的基础归还记录
		if ($archiveData['status'] == 1) {
			$this->error('该档案已经被提档');
		}
		//查询案件
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveData['case_id'];
		$case = D('Case')->where($map)->find();

		$this->assign('case', $case);
		$this->assign('nowtime', time());
		// 渲染
		$this->display();

	}

	/**
	 *提档执行操作
	 *
	 */
	public function borrowOutInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('ArchiveLend');
		$archiveLendData = $Model2->create();
		if (false === $archiveLendData) {
			$this->error($Model2->getError());
		}
		$archiveLendData['time'] = strtotime($archiveLendData['time']);
		$addArchiveLend = $Model->table(C('DB_PREFIX') . 'archive_lend')->add($archiveLendData);
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveLendData['archive_id'];
		$changeArchive = $Model->table(C('DB_PREFIX') . 'archive')->where($map)->setField('status', 1);
		if (!$addArchiveLend || !$changeArchive) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//查询档案信息  获取案件ID case_id
		$archiveData = D('Archive')->getById($archiveLendData['archive_id']);
		//
		$user_id = $archiveLendData['user'];
		$userData = D('User')->getById($user_id);
		$department_id = $userData['department_id'];
		//更新case表 case_handle 表数据*********************************************************/
		$updateCaseData['id'] = $archiveData['case_id'];
		$updateCaseData['department_id'] = $department_id;
		$updateCaseData['catalog_status'] = 0;
		$updateCaseData['update_time'] = time();
		$result = D('Case')->save($updateCaseData);
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//查询case_handle旧数据
		$map = array();
		$map['case_id'] = $archiveData['case_id'];
		$map['is_now'] = 1;
		$oldCaseHandleData = D('CaseHandle')->where($map)->find();
		if (!$oldCaseHandleData || $oldCaseHandleData['case_id'] != $map['case_id']) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//更新case_handle数据
		$updateCaseHandleData['id'] = $oldCaseHandleData['id'];
		$updateCaseHandleData['is_now'] = 0;
		$updateCaseHandleData['end_time'] = time();
		$updateCaseHandleData['update_time'] = time();
		$result = D('CaseHandle')->save($updateCaseHandleData);
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//插入case_handle新数据
		$_POST['case_id'] = $archiveData['case_id'];
		$_POST['user_id'] = $user_id;
		$caseHandleData = D('CaseHandle')->create();
		$data = $caseHandleData;
		$data['case_id'] = $archiveData['case_id'];
		$data['user_id'] = $user_id;
		$result = D('CaseHandle')->add($data);
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}
		//更新case表 case_handle 表数据*********************************************************/

		$Model->commit();
		//返回数据
		$archiveLendData['id'] = $addArchiveLend;
		$this->success('操作成功', U('detailInfoUpload', array('archive_id' => $archiveLendData['archive_id'], 'cate' => $archiveLendData['cate'], 'id' => $archiveLendData['id'])));

	}
	/**
	 * 归还界面
	 */
	public function borrowBack() {
		//判断参数id是否合法
		$id = I('get.id', '', 'strip_tags');
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		//判断该档案的基础归还记录
		if ($archiveData['status'] == 0) {
			$this->error('该档案已经归还');
		}
		$this->assign('nowtime', time());
		// 渲染
		$this->display();

	}
	/**
	 *归还执行操作
	 *
	 */
	public function borrowBackInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('ArchiveLend');
		$archiveLendData = $Model2->create();
		if (false === $archiveLendData) {
			$this->error($Model2->getError());
		}
		$archiveLendData['time'] = strtotime($archiveLendData['time']);
		$addArchiveLend = $Model->table(C('DB_PREFIX') . 'archive_lend')->add($archiveLendData);
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveLendData['archive_id'];
		$changeArchive = $Model->table(C('DB_PREFIX') . 'archive')->where($map)->setField('status', 0);

		//查询档案信息  获取案件ID case_id
		$archiveData = D('Archive')->getById($archiveLendData['archive_id']);
		//更新case表 case_handle 表数据*********************************************************/
		$updateCaseData['id'] = $archiveData['case_id'];
		$updateCaseData['catalog_status'] = 1;
		$updateCaseData['update_time'] = time();
		$result = D('Case')->save($updateCaseData);
		if (!$result) {
			$Model->rollback();
			$this->error('操作失败');
		}

		if (!$addArchiveLend || !$changeArchive) {
			$Model->rollback();
			$this->error('操作失败');

		}
		$Model->commit();
		$this->success('操作成功');

	}

	/**
	 * 查阅界面
	 */
	public function readInfo() {
		//判断参数id是否合法
		$id = I('get.id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveData['case_id'];
		$case = D('Case')->where($map)->find();

		$this->assign('case', $case);
		$this->assign('nowtime', time());
		$this->assign('id', $id);
		// 渲染
		$this->display();
	}
	/**
	 *查阅执行操作
	 *
	 */
	public function readInfoInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('ArchiveLend');
		$archiveLendData = $Model2->create();
		if (false === $archiveLendData) {
			$this->error($Model2->getError());
		}
		$archiveLendData['time'] = strtotime($archiveLendData['time']);
		$addArchiveLend = $Model->table(C('DB_PREFIX') . 'archive_lend')->add($archiveLendData);
		if (!$addArchiveLend) {
			$Model->rollback();
			$this->error('操作失败');

		}
		$Model->commit();
		//返回数据
		$archiveLendData['id'] = $addArchiveLend;
		$this->success('操作成功', U('detailInfoUpload', array('archive_id' => $archiveLendData['archive_id'], 'cate' => $archiveLendData['cate'], 'id' => $archiveLendData['id'])));

	}

	/**
	 * 查阅图片
	 */
	public function readInfoPhotoTable() {
		$this->display('Archive/readInfo/photoTable');
	}

	/**
	 * 复制界面
	 */
	public function readCopy() {
		//判断参数id是否合法
		$id = I('get.id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveData['case_id'];
		$case = D('Case')->where($map)->find();

		$this->assign('case', $case);
		$this->assign('nowtime', time());
		$this->assign('id', $id);
		// 渲染
		$this->display();
	}
	/**
	 *复制执行操作
	 *
	 */
	public function readCopyInsert() {
		$Model = new \Think\Model();
		$Model->startTrans();
		$Model2 = D('ArchiveLend');
		$archiveLendData = $Model2->create();
		if (false === $archiveLendData) {
			$this->error($Model2->getError());
		}
		$archiveLendData['time'] = strtotime($archiveLendData['time']);
		$addArchiveLend = $Model->table(C('DB_PREFIX') . 'archive_lend')->add($archiveLendData);
		if (!$addArchiveLend) {
			$Model->rollback();
			$this->error('操作失败');
		}
		$Model->commit();
		//返回数据
		$archiveLendData['id'] = $addArchiveLend;
		$this->success('操作成功', U('detailInfoUpload', array('archive_id' => $archiveLendData['archive_id'], 'cate' => $archiveLendData['cate'], 'id' => $archiveLendData['id'])));

	}

	/**
	 * 档案信息界面
	 */
	public function archiveInfo() {
		//判断参数id是否合法
		$id = I('get.id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}

		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveDataArray = D('ArchiveView')->where($map)->find();
		//办案人信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveDataArray['create_user_id'];
		$archiveDataArray['return_man'] = D('User')->where($map)->find();
		$this->assign('archiveDataArray', $archiveDataArray);

		$Model2 = D('ArchiveLendView');
		//操作关键词
		$keyword = C('custom.archive_keyword');
		//提档记录
		$map = array();
		$map['is_del'] = 0;
		$map['archive_id'] = $archiveDataArray['id'];
		$map['cate'] = 0;
		$archiveDataArray['lend'][0]['record'] = $Model2->where($map)->order('create_time desc')->limit('1')->select();
		$archiveDataArray['lend'][0]['keyword'] = $keyword[0];
		//返还记录
		$map = array();
		$map['is_del'] = 0;
		$map['archive_id'] = $archiveDataArray['id'];
		$map['cate'] = 1;

		$archiveDataArray['lend'][1]['record'] = $Model2->where($map)->order('create_time desc')->limit('1')->select();
		$archiveDataArray['lend'][1]['keyword'] = $keyword[1];

		//查阅记录
		$map = array();
		$map['is_del'] = 0;
		$map['archive_id'] = $archiveDataArray['id'];
		$map['cate'] = 2;
		$archiveDataArray['lend'][2]['record'] = $Model2->where($map)->order('create_time desc')->limit('1')->select();
		$archiveDataArray['lend'][2]['keyword'] = $keyword[2];

		//复制记录
		$map = array();
		$map['is_del'] = 0;
		$map['archive_id'] = $archiveDataArray['id'];
		$map['cate'] = 3;
		$archiveDataArray['lend'][3]['record'] = $Model2->where($map)->order('create_time desc')->limit('1')->select();
		$archiveDataArray['lend'][3]['keyword'] = $keyword[3];
		$this->assign('archiveDataArray', $archiveDataArray);
		$this->assign('lendrecords', $archiveDataArray['lend']);
		// 渲染
		$this->display();
	}

	/**
	 * 提档 归还 查阅 复制详情列表界面
	 */
	public function detail() {
		//判断参数id是否合法
		$archive_id = I('get.archive_id', '', 'strip_tags');
		$cate = I('get.cate', '', 'strip_tags');

		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveDataArray = D('ArchiveView')->where($map)->find();
		//办案人信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveDataArray['create_user_id'];
		$archiveDataArray['return_man'] = D('User')->where($map)->find();
		$this->assign('archiveDataArray', $archiveDataArray);
		$this->assign('archive_id', $archive_id);
		$this->assign('cate', $cate);

		// 渲染
		$this->display();
	}
	/**
	 * 提档 归还 查阅 复制表格界面
	 */
	public function detailTable() {
		$condition = get_condition();
		isset($condition['archive_id']) && $archive_id = $condition['archive_id'];
		isset($condition['cate']) && $cate = $condition['cate'];
		$map = array();
		$map['is_del'] = 0;
		$map['cate'] = $cate;
		$map['archive_id'] = $archive_id;
		$Model = D('ArchiveLendView');
		$count = $Model->where($map)->count('distinct ArchiveLend.id');
		$page = new Page($count, 4);
		$list = $Model->where($map)->order('create_time desc,id desc')->limit($page->firstrow . ',' . $page->rows)->group('ArchiveLend.id')->select();
		$this->assign('list', $list);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->assign('cate', $cate);
		$this->assign('archive_id', $archive_id);
		$keyword = C('custom.archive_keyword');
		$this->assign('keyword', $keyword);

		// 渲染
		$this->display('Archive/detail/table');
	}

	/**
	 * 提档 归还 查阅 复制详情界面
	 */
	public function detailInfo() {
		//判断参数id是否合法
		$archive_id = I('get.archive_id', '', 'strip_tags');
		$cate = I('get.cate', '', 'strip_tags');
		$id = I('get.id', '', 'strip_tags');

		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveDataArray = D('ArchiveView')->where($map)->find();
		//办案人信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveDataArray['create_user_id'];
		$archiveDataArray['return_man'] = D('User')->where($map)->find();
		//
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveDataArray['lend'] = D('ArchiveLendView')->where($map)->find();

		//案件图片表中的cate 对应配置文件custom.php中的photo_type 用于读取case_photo表 获取图片
		//cate=0 提档 对应 custom.php中的photo_type 的11
		if ($archiveDataArray['lend']['cate'] == '0') {
			$newcate = 11;
		}
		//cate=2 查阅 对应 custom.php中的photo_type 的12

		if ($archiveDataArray['lend']['cate'] == '2') {
			$newcate = 12;
		}
		//cate=3 复制 对应 custom.php中的photo_type 的13

		if ($archiveDataArray['lend']['cate'] == '3') {
			$newcate = 13;
		}
		$archiveDataArray['newcate'] = $newcate;

		$this->assign('archiveDataArray', $archiveDataArray);
		$this->assign('archive_id', $archive_id);
		$this->assign('cate', $cate);
		$keyword = C('custom.archive_keyword');
		$this->assign('keyword', $keyword);

		// 渲染
		$this->display();
	}

	/**
	 * 提档 归还 查阅 复制上传图片界面
	 */
	public function detailInfoUpload() {
		//判断参数id是否合法
		$archive_id = I('get.archive_id', '', 'strip_tags');
		$cate = I('get.cate', '', 'strip_tags');
		$id = I('get.id', '', 'strip_tags');

		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData) {
			$this->error('未获取档案相关信息');
		}
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archive_id;
		$archiveDataArray = D('ArchiveView')->where($map)->find();
		//办案人信息
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $archiveDataArray['create_user_id'];
		$archiveDataArray['return_man'] = D('User')->where($map)->find();
		//
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $id;
		$archiveDataArray['lend'] = D('ArchiveLendView')->where($map)->find();
		//案件图片表中的cate 对应配置文件custom.php中的photo_type 用于读取case_photo表 获取图片
		//cate=0 提档 对应 custom.php中的photo_type 的11
		if ($archiveDataArray['lend']['cate'] == '0') {
			$newcate = 11;
		}
		//cate=2 查阅 对应 custom.php中的photo_type 的12

		if ($archiveDataArray['lend']['cate'] == '2') {
			$newcate = 12;
		}
		//cate=3 复制 对应 custom.php中的photo_type 的13

		if ($archiveDataArray['lend']['cate'] == '3') {
			$newcate = 13;
		}
		$archiveDataArray['newcate'] = $newcate;
		$this->assign('archiveDataArray', $archiveDataArray);
		$this->assign('archive_id', $archive_id);
		$this->assign('cate', $cate);
		$keyword = C('custom.archive_keyword');
		$this->assign('keyword', $keyword);

		// 渲染
		$this->display();
	}

/**
 * 加载图片
 */
	public function photoList() {
		$cate = I('post.cate', '', 'int');
		$id = I('post.id', '', 'int');
		$case_id = I('post.case_id', '', 'int');
		$list = $this->getPhotoList($cate, $id, $case_id);
		$this->assign('list', $list);
		$this->display('Archive/detailInfoUpload/photoTable');
	}

	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	protected function getPhotoList($cate = 0, $itemId = 0, $case_id = 0) {

		$map = array();
		$map['cate'] = $cate;
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$map['ext_ida'] = $itemId;
		$list = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);

		return $list;
	}

	/**
	 * 案件ID获取档案信息
	 */
	public function caseToArchive() {
		$case_id = I('get.case_id', '', 'strip_tags');

		if (!$case_id) {
			$this->error('档案信息读取失败');
		}
		$map = array();
		$map['case_id'] = $case_id;
		$archiveData = D('Archive')->where($map)->find();
		if (!$archiveData || $archiveData['case_id'] != $case_id) {
			$this->error('档案信息读取失败!');
		}
		$url = U('Archive/archiveInfo', array('id' => $archiveData['id']));
		// 渲染
		$this->redirect($url);

	}

}
