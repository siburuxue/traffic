<?php
namespace Admin\Widget;
use Think\Controller;

class CasePhotoWidget extends Controller {

	public function index($title = '', $caseId = 0, $cate = 0, $idA = 0, $idB = 0, $idC = 0, $idD = 0, $idE = 0) {

		$param = array(
			'case_id' => $caseId,
			'cate' => $cate,
			'ext_ida' => $idA,
			'ext_idb' => $idB,
			'ext_idc' => $idC,
			'ext_idd' => $idD,
			'ext_ide' => $idE,
		);
		$sample = array(
			'case_id' => '__CASEID__',
			'cate' => '__CATE__',
			'ext_ida' => '__IDA__',
			'ext_idb' => '__IDB__',
			'ext_idc' => '__IDC__',
			'ext_idd' => '__IDD__',
			'ext_ide' => '__IDE__',
		);

		$this->assign('title', $title);
		$this->assign('param', $param);
		$this->assign('sample', $sample);

		$this->display('Common/casePhoto');
	}

	public function detail($title = '', $caseId = 0, $cate = 0, $idA = 0, $idB = 0, $idC = 0, $idD = 0, $idE = 0) {

		$param = array(
			'case_id' => $caseId,
			'cate' => $cate,
			'ext_ida' => $idA,
			'ext_idb' => $idB,
			'ext_idc' => $idC,
			'ext_idd' => $idD,
			'ext_ide' => $idE,
		);
		$sample = array(
			'case_id' => '__CASEID__',
			'cate' => '__CATE__',
			'ext_ida' => '__IDA__',
			'ext_idb' => '__IDB__',
			'ext_idc' => '__IDC__',
			'ext_idd' => '__IDD__',
			'ext_ide' => '__IDE__',
		);

		$this->assign('title', $title);
		$this->assign('param', $param);
		$this->assign('sample', $sample);

		$this->display('Common/casePhotoDetail');
	}

}
