<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 呈请延期
 */
class CaseCheckupDelayController extends CaseCommonController {

/**
 * 首页界面
 */
	public function index() {
		$cate = I('get.cate', '', 'strip_tags');
		//判断案件是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_id', '', 'strip_tags');
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('无效的案件编号');
		}
		//判断检验鉴定是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_checkup_id', '', 'strip_tags');
		$caseCheckupData = D('CaseCheckup')->where($map)->find();

		if (!$caseCheckupData) {
			$this->error('无效的检验鉴定编号');
		}
		$this->assign('caseCheckupData', $caseCheckupData);
		$this->assign('cate', $cate);
		//照片存储类型
		$photoCate = $cate + 40;
		$this->assign('photoCate', $photoCate);

		// 渲染
		$this->display();
	}

	/**
	 * 查询界面
	 */
	public function query() {
		$cate = I('get.cate', '', 'strip_tags');
		//判断案件是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_id', '', 'strip_tags');
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('无效的案件编号');
		}
		//判断检验鉴定是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_checkup_id', '', 'strip_tags');
		$caseCheckupData = D('CaseCheckup')->where($map)->find();

		if (!$caseCheckupData) {
			$this->error('无效的检验鉴定编号');
		}
		$this->assign('caseCheckupData', $caseCheckupData);

		$this->assign('cate', $cate);

		//照片存储类型
		$photoCate = $cate + 40;
		$this->assign('photoCate', $photoCate);
		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {

		// 搜索条件
		$map = array();
		$map['is_del'] = 0;
		$map['cate'] = I('post.cate', '', 'int');
		$map['case_id'] = I('post.case_id', '', 'int');
		$map['case_checkup_id'] = I('post.case_checkup_id', '', 'int');
		//$condition = get_condition();

		// 列表信息
		$Model = D('CaseCheckupReview');
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
		$this->display('CaseCheckCheckup/index/table');
	}
	/**
	 * 加载图片
	 */
	public function photoList() {
		$cate = I('post.cate', '', 'int');
		$id = I('post.id', '', 'int');
		$case_id = I('post.case_id', '', 'int');
		$lists = $this->getPhotoList($cate, $id, $case_id);
		$this->assign('lists', $lists);
		$this->display('CaseCheckCheckup/index/photoTable');
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
	 * 新增审批
	 */
	public function add() {
		$review_cate = I('get.cate', '', 'strip_tags'); //参照custom.php 'case_checkup_review_type' 检验鉴定审核类型
		$check_cate = 10 + $review_cate; //参照custom.php 'check_type' 审批类型
		$this->assign('review_cate', $review_cate);
		$this->assign('check_cate', $check_cate);

		//判断案件是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_id', '', 'strip_tags');
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('无效的案件编号');
		}
		//判断检验鉴定是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_checkup_id', '', 'strip_tags');
		$caseCheckupData = D('CaseCheckup')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('无效的检验鉴定编号');
		}
		if ($caseCheckupData['is_delay'] == 1) {
			$this->error('该检验鉴定已经延期，不可再次进行设置');
		}
		$list = $this->getCheckUserList("case_checkup_check_delay_0");
		$this->assign('caseCheckupData', $caseCheckupData);
		$this->assign('list', $list);

		//从配置文件custom.php中读取所有标题类型
		$case_checkup_review_title_type = C('custom.case_checkup_review_title_type');
		//判断超期延期类型 插入对应标题
		if ($review_cate == 0) {
			$title = $case_checkup_review_title_type[0];
		}
		if ($review_cate == 1) {
			$title = $case_checkup_review_title_type[1];
		}
		if ($review_cate == 2) {
			$title = $case_checkup_review_title_type[2];
		}
		$this->assign('title', $title);

		//当前时间60个自然日时间点
		$time = date('Y-m-d', time());
		$time = strtotime($time);
		$datetime = $time + 61 * 24 * 3600 - 1;
		$date = date('Y-m-d H:i', $datetime);
		$this->assign('date', $date);

		$this->display();

	}
	/**
	 * 新增审批 插入数据
	 */
	public function insert() {
		$Model = new \Think\Model();
		$Model->startTrans();

		// 实例化模型
		$Model1 = D('CaseCheck');
		$Model2 = D('CaseCheckupReview');
		$caseCheckData = $Model1->create();
		$caseCheckupReviewData = $Model2->create();

		// 创建数据
		if (false === $caseCheckData) {
			$Model->rollback();
			$this->error($Model1->getError());
		}

		//获取并赋值给审批表case_check 字段cate
		$caseCheckData['cate'] = I('post.check_cate', '', 'strip_tags');
		//获取并赋值给审批表case_check 字段item_id
		$caseCheckData['item_id'] = I('post.case_checkup_id', '', 'strip_tags');
		// 创建数据
		if (false === $caseCheckupReviewData) {
			$Model->rollback();
			$this->error($Model2->getError());
		}
		if (empty($caseCheckData['check_user_id'])) {
			$Model->rollback();
			$this->error('审核人不能为空');
		}
		if (empty($caseCheckData['case_id'])) {
			$Model->rollback();
			$this->error('案件ID不能为空');
		}
		$caseCheckupReviewData['case_checkup_id'] = I('post.case_checkup_id', '', 'strip_tags');
		$caseCheckupReviewData['to_userid'] = I('post.check_user_id', '', 'strip_tags');
		$caseCheckupReviewData['content'] = I('post.content', '', 'strip_tags');
		$caseCheckupReviewData['case_id'] = I('post.case_id', '', 'strip_tags');
		$caseCheckupReviewData['cate'] = I('post.review_cate', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('post.case_checkup_id', '', 'strip_tags');
		$caseCheckUpData = D('CaseCheckup')->where($map)->find();

		if (!$caseCheckUpData || $caseCheckUpData['id'] != $map['id']) {
			$Model->rollback();
			$this->error('检验鉴定信息未读取到');
		}
		//获取新设置的延期时间
		$finish_time = I('post.finish_time', '', 'strip_tags');
		$caseCheckupReviewData['delay_time'] = strtotime($finish_time);
		//从配置文件custom.php中读取所有标题类型
		$case_checkup_review_title_type = C('custom.case_checkup_review_title_type');
		//延期类型 插入对应标题
		$caseCheckupReviewData['title'] = $case_checkup_review_title_type[0];

		//获取检验鉴定审核类型
		$caseCheckData['cate'] = I('post.check_cate', '', 'strip_tags');
		$caseCheckupReviewData['cate'] = I('post.review_cate', '', 'strip_tags');
		//获取检验鉴定报告内容
		$caseCheckupReviewData['content'] = I('post.content', '', 'strip_tags');
		$caseCheckupDataNew['id'] = $caseCheckUpData['id'];
		$caseCheckupDataNew['is_delay'] = 1;
		$caseCheckupDataNew['finish_time'] = strtotime($finish_time);
		if ($caseCheckupDataNew['finish_time'] < $caseCheckUpData['create_time']) {
			$this->error('延期时间不能小于检验鉴定生成时间');
		}
		$time = date('Y-m-d', time());
		$time = strtotime($time);
		//判断约定时间是否在60个自然日以内
		if ($caseCheckupDataNew['finish_time'] >= $time + 61 * 24 * 3600 - 1) {
			$date = $time + 61 * 24 * 3600 - 1;
			$this->error('延期时间不可超过当前时间之后60个自然日');

		}
		//插入数据

		$review_id = $Model->table(C('DB_PREFIX') . 'case_checkup_review')->add($caseCheckupReviewData);
		$caseCheckData['origin_id'] = $review_id;
		$check_id = $Model->table(C('DB_PREFIX') . 'case_check')->add($caseCheckData);
		$save_checkup = $Model->table(C('DB_PREFIX') . 'case_checkup')->save($caseCheckupDataNew);
		// 数据保存失败
		if (!$check_id || !$review_id || !$save_checkup) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		// 成功
		$Model->commit();
		$this->success('操作成功');

	}

	/**
	 * 详情
	 */
	public function detail() {
		//判断案件是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_id', '', 'strip_tags');
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('无效的案件编号');
		}
		//判断检验鉴定是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.case_checkup_id', '', 'strip_tags');
		$caseCheckupData = D('CaseCheckup')->where($map)->find();
		if (!$caseCheckupData) {
			$this->error('无效的检验鉴定编号');
		}

		//判断检验鉴定审核是否有效
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = I('get.id', '', 'int');
		$caseCheckupReviewData = D('CaseCheckupReview')->where($map)->find();
		if (!$caseCheckupReviewData) {
			$this->error('无效的检验鉴定审核编号');
		}
		$this->assign('caseCheckupReviewData', $caseCheckupReviewData);

		$map = array();
		$map['is_del'] = 0;
		$map['cate'] = 10 + $caseCheckupReviewData['cate'];
		$map['case_id'] = I('get.case_id', '', 'int');
		$map['item_id'] = I('get.case_checkup_id', '', 'int');
		$map['origin_id'] = $caseCheckupReviewData['id'];

		$caseCheckData = D('CaseCheckView')->where($map)->order('create_time desc')->select();
		$this->assign('caseCheckData', $caseCheckData);

		// 渲染
		$this->display();
	}
}
