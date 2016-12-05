<?php
namespace Admin\Service;

class CaseReviewService extends CommonService {

	public function detail($caseReviewId) {
		$data = array();

		if (empty($caseReviewId)) {
			$this->error = '非法操作';
			return false;
		}

		$caseReview = $this->table('__CASE_REVIEW__')->getById($caseReviewId);
		if (empty($caseReview)) {
			$this->error = '复核信息不存在';
			return false;
		}

		$data['caseReview'] = $caseReview;
		return $data;

	}

	/**
	 * 获取指定类型的案件复核信息
	 * @param  integer 复核编号
	 * @param  string 返回类型名称集合
	 * @return [type]
	 */
	public function getDetailInfo($caseReviewId = 0) {
		// 返回数据集
		$data = array();

		if (empty($caseReviewId)) {
			$this->error = '非法操作';
			return false;
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = D('CaseReviewView')->where($map)->find();
		if (empty($caseReview)) {
			$this->error = '复核信息不存在';
			return false;
		}
		$data['caseReview'] = $caseReview;

		// 答复信息
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$caseReply = D('CaseReplyView')->where($map)->order('create_time desc')->group('CaseReply.id')->find();
		$data['caseReply'] = empty($caseReply) ? array() : $caseReply;

		// 领导指示
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$caseDirective = D('CaseDirectiveView')->where($map)->order('create_time desc')->group('CaseDirective.id')->find();
		$data['caseDirective'] = empty($caseDirective) ? array() : $caseDirective;

		// 集体研究
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$caseDiscuss = D('CaseDiscussView')->where($map)->order('create_time desc')->group('CaseDiscuss.id')->find();
		if (empty($caseDiscuss)) {
			$caseDiscuss = array();
		} else {
			$caseDiscuss['discuss_time'] = date('Y-m-d H:i', $caseDiscuss['discuss_time']);
		}
		$data['caseDiscuss'] = $caseDiscuss;

		return $data;
	}

}
?>