<?php
namespace Admin\Widget;
use Think\Controller;

class PublicWidget extends Controller {

	//获取照片数量
	public function getPicNumber($caseId = '', $cate = '', $ext_ida = '', $ext_idb = '', $ext_idc = '', $ext_idd = '', $ext_ide = '') {
		$map = array();
		$map['ext_ida'] = $ext_ida;
		$map['ext_idb'] = $ext_idb;
		$map['cate'] = $cate; //cate值参照custom.php 中的 photo_cate
		$map['case_id'] = $caseId;
		$result = D('CasePhotoView')->where($map)->count();
		if (!$result) {
			$result = '0';
		}
		echo $result;
	}

	//获取检验鉴定状态
	public function getCaseCheckupStatus($id = '', $case_id = '') {

		$map = array();
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $id;
		$reportData = D('CaseCheckupReport')->where($map)->select();
		if ($reportData) {
			foreach ($reportData as $key1 => $value1) {
				if ($reportData[$key1]['is_back'] == 0) {
					echo '结束';return;
				}
			}

			echo '已退回';return;
		}

		$map = array();
		$map['case_id'] = $case_id;
		$map['case_checkup_id'] = $id;
		$entrustData = D('CaseCheckupEntrust')->where($map)->find();
		if ($entrustData) {

			if ($entrustData['is_finish'] == 1) {
				echo '完成';return;
			} elseif ($entrustData['is_submit'] == 1) {

				echo '送审中';return;

			} else {

				echo '委托中';return;
			}

		} else {

			$map = array();
			$map['case_id'] = $case_id;
			$map['case_checkup_id'] = $id;
			$reviewData = D('CaseCheckupReview')->where($map)->select();
			if ($reviewData) {
				foreach ($reviewData as $key2 => $value2) {
					if ($reviewData[$key2]['status'] != '0') {
						echo '审批结束';return;
					}
				}

				echo '送审中';return;
			}

		}

		echo '委托中';return;

	}
}
