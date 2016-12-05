<?php
namespace Admin\Controller;

/**
 * 复核科
 * 需要是已完成的案件才可以复核
 */
class CaseReviewHandleInfoController extends CommonController {

	/**
	 * 详细
	 */
	public function detail() {
		$caseReviewId = I('get.id', '', 'int');

		$Model = D('CaseReview', 'Service');
		$info = $Model->detail($caseReviewId);
		if (false === $info) {
			$this->error($Model->getError());
		}

		$this->assign('info', $info);

		$this->display();
	}

	public function getDetailInfo() {
		$caseReviewId = I('get.id', '', 'int');

		$Model = D('CaseReview', 'Service');
		$info = $Model->getDetailInfo($caseReviewId);
		if (false === $info) {
			$this->error($Model->getError());
		}

		$this->success($info);
	}
}