<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 测试-工具类
 */
class TestController extends CommonController {
	public function __construct() {
	    parent::__construct();
		import("Org.Util.tcpdf.tcpdf_include");
		import("Org.Util.tcpdf.tcpdf");
	}
	//导入日历表
	public function importCalendar() {
		$largeMonth = array(1, 3, 5, 7, 8, 10, 12);
		$smallMonth = array(4, 6, 9, 11);
		$model = M('Calendar');
		$model->startTrans();
		for ($i = 2016; $i < 2067; $i++) {
			for ($j = 1; $j <= 12; $j++) {
				$day = 0;
				if ($j == 2) {
					if ($i % 4 == 0) {
						$day = 29;
					} else {
						$day = 28;
					}
				} else {
					if (in_array($j, $largeMonth)) {
						$day = 31;
					} else {
						$day = 30;
					}
				}
				for ($k = 1; $k <= $day; $k++) {
					$map = array();
					$map['year'] = $i;
					$map['month'] = $j;
					$map['day'] = $k;
					$map['is_weekend'] = 0;
					if (date('w', strtotime("{$i}-{$j}-{$k}")) == 6 || date('w', strtotime("{$i}-{$j}-{$k}")) == 0) {
						$map['is_holidays'] = 1;
					} else {
						$map['is_holidays'] = 0;
					}
					$map['create_time'] = time();
					$map['create_user_id'] = 1;
					$map['update_time'] = time();
					$map['update_user_id'] = 1;
					$rs = $model->add($map);
					if (!$rs) {
						$model->rollback();
						echo "数据保存失败";
					}
				}
			}
		}
		$model->commit();
		echo "数据保存成功";
	}
	public function testRename() {
		$str = '[{
                    "id": 6,
                    "case_id": null,
                    "cate": 1,
                    "ext_ida": 1,
                    "ext_idb": 0,
                    "ext_idc": 0,
                    "ext_idd": 0,
                    "ext_ide": 0,
                    "file_id": 428,
                    "train": 0,
                    "create_time": 1470195827,
                    "create_user_id": 1,
                    "update_time": 1470195827,
                    "update_user_id": 1,
                    "is_del": 0,
                    "name": "WIFI\u901a\u4fe1\u8bf4\u660e3.8.docx",
                    "file_path": "Uploads\/\/file\/1\/bacf1bd255ef27222b748c1fb6d22553.docx",
                    "file_size": "77641",
                    "ext": "docx"
                },
                {
                    "id": 9,
                    "case_id": null,
                    "cate": 1,
                    "ext_ida": 1,
                    "ext_idb": 0,
                    "ext_idc": 0,
                    "ext_idd": 0,
                    "ext_ide": 0,
                    "file_id": 431,
                    "train": 0,
                    "create_time": 1470211345,
                    "create_user_id": 1,
                    "update_time": 1470211345,
                    "update_user_id": 1,
                    "is_del": 0,
                    "name": "WIFI\u901a\u4fe1\u8bf4\u660e3.8(1).docx",
                    "file_path": "Uploads\/\/file\/1\/da61a2082a1b947a47aa77014765287e.docx",
                    "file_size": "77641",
                    "ext": "docx"
                },
                {
                    "id": 10,
                    "case_id": null,
                    "cate": 1,
                    "ext_ida": 1,
                    "ext_idb": 0,
                    "ext_idc": 0,
                    "ext_idd": 0,
                    "ext_ide": 0,
                    "file_id": 432,
                    "train": 0,
                    "create_time": 1470211375,
                    "create_user_id": 1,
                    "update_time": 1470211375,
                    "update_user_id": 1,
                    "is_del": 0,
                    "name": "WIFI\u901a\u4fe1\u8bf4\u660e3.8.docx",
                    "file_path": "Uploads\/\/file\/1\/a9de8261adc112e99f805a52ad6d4884.docx",
                    "file_size": "77641",
                    "ext": "docx"
                }]';
		$file = json_decode($str, true);
		$common = new CommonController();
//		$common->renameList = $file;
//		foreach ($common->renameList as $key => $val) {
//			$common->renameFiles($key);
//		}
//		echo json_encode($common->renameList);
	}

	public function getGlobals() {
		echo json_encode($GLOBALS);
	}

	/**
	 * 生成PDF
	 */
	public function createPDF() {
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// add a page
		$pdf->AddPage();
		$pdf->Image('E:\\a.jpg', '', '', 500, 700, '', '', '', false, 300, '', false, false, 1, false, false, false);
		$pdf->Output('example_009.pdf', 'I');
	}

	/**
	 * 生成Excel
	 */
	public function createExcel(){
		$fileHandle = new FileHandleController();
		$array = array(
			array('id' => 1,'name'=>'n1'),
			array('id' => 2,'name'=>'n2'),
			array('id' => 3,'name'=>'n3'),
		);
		$titleArray = array('第一列','第二列');
		$fileHandle->createExcel('测试','测试',$titleArray,$array);
	}
	//导入cate表
    public function importCaseCate(){
        $cateArray = get_custom_config('photo_cate');
        foreach($cateArray as $key => $val){
            $map = array();
            $map['cate'] = $key;
            $map['name'] = $val;
            $map['create_time'] = time();
            $map['create_user_id'] = 1;
            $map['update_time'] = time();
            $map['update_user_id'] = 1;
            M("Cate")->add($map);
        }
    }

    //生成二维码
    public function createQRCode(){
        $text = '[{"case":10,"name":"123456"}]';
        $file = parent::createQRCode($text,true);
        $this->assign('file',$file);
        $text1 = '[{"case":101,"name":"123456"}]';
        $file1 = parent::createQRCode($text1,true);
        $this->assign('file1',$file1);
        $this->display('index');
    }
}