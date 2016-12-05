<?php
namespace Lib;
/**
 * 案件状态
 */
Class CaseStatus {

	// 案件信息
	private $case;
	private $caseCognizance;
	private $caseCognizanceSimple;

	// 使用到的状态
	private $status = array();

	// 返回的状态描述
	private $result = '';

	public function __construct($caseId = 0) {
		$map = array();
		$map['id'] = $caseId;
		$this->case = M('Case')->where($map)->find();
	}

	public function getStatus() {

		// 如果已归档
		if ($this->case['catalog_status'] == 1) {
			$this->result = '已归档';
			return $this->result;
		}

		// 优先级最高的，案件如果存在正在被领导审批的记录返回当前审批状态
		if ($this->checkCaseCheck()) {
			return $this->result;
		}

		if ($this->case['cognizance_status'] == 0) {
			$this->result = '事故认定待保存';
			return $this->result;
		}
		// 获取有效的案件认定记录
		if ($this->getCaseCognizance()) {
			return $this->result;
		}

		if ($this->caseCognizance['cognizance_type'] == 0) {
			// 简单事故认定
			if ($this->checkSimpleCognizance()) {
				return $this->result;
			}
		} else {
			// 普通事故认定
			if ($this->isSubmitCaseCognizance()) {
				return $this->result;
			}
		}

		return $this->result;
	}

	// 检查审批状态
	private function checkCaseCheck() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['status'] = 0;
		$caseCheck = M('CaseCheck')->where($map)->select();

		if (empty($caseCheck)) {
			return false;
		}

		foreach ($caseCheck as $key => $value) {
			if ($value['cate'] == 0) {
				$this->result = '受案登记待审批';
				return true;
			}
		}

		foreach ($caseCheck as $key => $value) {
			if ($value['cate'] == 12) {
				$this->result = '检验鉴定延期、超期待审批';
				return true;
			}
		}

		foreach ($caseCheck as $key => $value) {
			if ($value['cate'] == 11) {
				$this->result = '检验鉴定超期待审批';
				return true;
			}
		}

		foreach ($caseCheck as $key => $value) {
			if ($value['cate'] == 10) {
				$this->result = '检验鉴定延期待审批';
				return true;
			}
		}

		return false;

	}

	// 获取当前有效的案件认定记录
	private function getCaseCognizance() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$caseCognizance = M('CaseCognizance')->where($map)->order('id desc')->find();
		if (empty($caseCognizance)) {
			$this->result = '事故认定待保存';
			return true;
		} else {
			$this->caseCognizance = $caseCognizance;
			return false;
		}
	}

	// 简易事故认定流程
	private function checkSimpleCognizance() {

		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['case_cognizance_id'] = $this->caseCognizance['id'];
		$caseCognizanceSimple = M('CaseCognizanceSimple')->where($map)->order('create_time desc')->find();
		if (empty($caseCognizanceSimple)) {
			$this->result = '简易事故认定待保存';
			return true;
		} else {
			$this->caseCognizanceSimple = $caseCognizanceSimple;
			return $this->isHaveMediate();
		}

	}

	// 调查报告是否已提交
	private function isSubmitCaseCognizance() {
		if (in_array($this->case['cognizance_check_status'], array('1', '2', '6'))) {
			return $this->isMakeCaseCognizance();
		} else {
			return $this->isOverCaseAccept();
		}

	}

	// 受案登记是否完成
	private function isOverCaseAccept() {
		if ($this->case['accept_check_status'] == 2) {
			return $this->isOverCaseCheckup();
		} else {
			$this->result = '受案登记待提交';
			return true;
		}
	}

	// 检验鉴定状态
	private function isOverCaseCheckup() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$map['is_del'] = 0;
		$map['is_cancel'] = 0;
		$map['is_over'] = 0;
		$unique = M('CaseCheckup')->where($map)->find();

		if (empty($unique)) {
			$this->result = '调查报告待提交';
		} else {
			$this->result = '委托检验鉴定';
		}
		return true;

	}

	// 事故认定是否制作
	private function isMakeCaseCognizance() {
		if ($this->case['cognizance_check_status'] == 6) {
			if ($this->case['review_submit_status'] == 1) {
				return $this->caseReview('', 'isSendCognizance');
			} else {
				return $this->isSendCognizance();
			}

		} else {
			$this->result = '待制作事故认定';
			return true;
		}
	}

	// 是否事故认定全部送达
	private function isSendCognizance() {
		if ($this->case['cognizance_send_status'] == 1) {
			// 是否有复核申请
			if ($this->case['review_submit_status'] == 1) {
				return $this->caseReview('', 'isHaveMediate');
			} else {
				return $this->isHaveMediate();
			}
		} else {
			$this->result = '事故认定待送达';
			return true;
		}
	}

	// 是否有调解
	private function isHaveMediate() {
		if ($this->case['mediate_status'] == 1) {
			return $this->isOverMediate();
		} else {
			return $this->isHavePunish();
		}
	}

	// 调解是否完成
	private function isOverMediate() {
		if ($this->case['mediate_complete_status'] == 1) {
			// 是否有复核申请
			if ($this->case['review_submit_status'] == 1) {
				return $this->caseReview('待归档');
			} else {
				$this->result = '待归档';
				return true;
			}
		} else {
			$this->result = '待调解';
			return true;
		}
	}

	// 是否有处罚
	private function isHavePunish() {
		if ($this->case['punish_status'] == 1) {
			// 是否有复核申请
			if ($this->case['review_submit_status'] == 1) {
				return $this->caseReview('', 'isOverPunish');
			} else {
				return $this->isOverPunish();
			}
		} else {
			$this->result = '待归档';
			return true;
		}
	}

	// 处罚是否完成
	private function isOverPunish() {
		if ($this->case['punish_complete_status'] == 1) {
			// 是否有复核申请
			if ($this->case['review_submit_status'] == 1) {
				return $this->caseReview('待归档', '');
			} else {
				$this->result = '待归档';
				return true;
			}
		} else {
			$this->result = '待处罚';
			return true;
		}
	}

	// 复核科流程
	private function caseReview($notice = '', $func = '') {
		if ($this->case['review_result_status'] == 2) {
			$this->result = '待提交调查报告';
		} elseif ($this->case['review_result_status'] == 1) {
			if ($notice != '') {
				$this->result = $notice;
				return true;
			} else {
				return call_user_func(array($this, $func));
			}
		} else {
			if ($this->case['review_accept_status'] == 1) {
				$this->result = '复核受理';
			} elseif ($this->case['review_accept_status'] == 2) {
				if ($notice != '') {
					$this->result = $notice;
					return true;
				} else {
					return call_user_func(array($this, $func));
				}
			} else {

				$map = array();
				$map['case_id'] = $this->case['id'];
				$caseReview = M('CaseReview')->where($map)->order('id desc')->find();

				$map = array();
				$map['case_review_id'] = $caseReview['id'];
				$name = M('CaseReviewApply')->where($map)->order('create_time asc')->getField('name');

				$this->result = $name . '提请复核';
				return true;
			}
		}

	}

}
?>