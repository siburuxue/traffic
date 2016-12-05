<?php
namespace Admin\Controller;
use Think\Model;
use \Lib\Page;

/**
 * 用户
 */
class CustomSearchController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
		$bridgeInfo = $this->getMyBrigade();
		// 搜索条件
		$map = array();
		$map['bridge_id'] = $bridgeInfo['id'];
		$map['is_del'] = 0;
		// 列表信息
		$Model = M('CustomSearchTemplate');
		$count = $Model->where($map)->count('distinct id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('id desc')->limit($page->firstrow . ',' . $page->rows)->select();

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
		$this->assign('list', $list);
		// 渲染
		$this->display('CustomSearch/index/table');
	}

	public function query() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		$info = M('CustomSearchTemplate')->getById($id);
		$this->assign('id', $id);
		$this->assign('info', $info);
		// 渲染
		$this->display();
	}

	public function queryTable() {
	    $config = get_custom_config();
		$model = new Model();
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		$info = M('CustomSearchTemplate')->getById($id);
		$sql = $info['content'];
		$countSql = "select count(1) as num from ({$sql}) A";
		$countRs = $model->query($countSql);
		$count = $countRs[0]['num'];
		$page = new Page($count, 15);
		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$sql = $sql . " limit " . $page->firstrow . "," . $page->rows;
		$list = $model->query($sql);
		$keys = array_keys($list[0]);
        foreach($keys as $key => $val){
            if(strpos($val,'_') === 0){
                $array = explode('__',$val);
                $keys[$key] = $array[1];
                $type = substr($array[0],1);
                $subConfig = $config[$type];
                foreach ($list as $k => $v){
                    $item = $v[$val];
                    $list[$k][$val] = $subConfig[$item];
                }
            }
        }
		$this->assign('page', $pageInfo);
		$this->assign('list', $list);
		$this->assign('keys', $keys);
		// 渲染
		$this->display('CustomSearch/query/table');
	}

	public function exportExcel() {
        $config = get_custom_config();
		$model = new Model();
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		$info = M('CustomSearchTemplate')->getById($id);
		$sql = $info['content'];
		$allArray = $model->query($sql);
		$titleArray = array_keys($allArray[0]);
		//行位置
		$xAxisArray = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		//一共有多少行数据
		$rows = count($allArray);
		//一共有多少列数据
		$cells = count($titleArray);
        foreach($titleArray as $key => $val){
            if(strpos($val,'_') === 0){
                $array = explode('__',$val);
                $keys[$key] = $array[1];
                $type = substr($array[0],1);
                $subConfig = $config[$type];
                foreach ($titleArray as $k => $v){
                    $item = $v[$val];
                    $list[$k][$val] = $subConfig[$item];
                }
            }
        }
		$xlsTitle = iconv('utf-8', 'gb2312', $info['name']);
		Vendor("PHPExcel.PHPExcel");
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//写表头
		$objPHPExcel->getActiveSheet()->mergeCells("A1:{$xAxisArray[$cells - 1]}1");
		//设置表格样式
		$objPHPExcel->getActiveSheet()->setCellValue("A1", $xlsTitle);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//写title
		for ($i = 0; $i < $cells; $i++) {
			//写入title
			$objPHPExcel->getActiveSheet()->setCellValue("{$xAxisArray[$i]}2", $titleArray[$i]);
			//设置表格样式
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//设置表格宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension($xAxisArray[$i])->setWidth(20);
			//加边框
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle("{$xAxisArray[$i]}2")->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

		}
		//设置背景色
		$objPHPExcel->getActiveSheet()->getStyle("A2:{$xAxisArray[$cells - 1]}2")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A2:{$xAxisArray[$cells - 1]}2")->getFill()->getStartColor()->setARGB("#0f0f0f");
		//写数据
		for ($j = 0; $j < $rows; $j++) {
			$k = 0;
			foreach ($allArray[$j] as $key => $val) {
				$objPHPExcel->getActiveSheet()->setCellValueExplicit($xAxisArray[$k] . ($j + 3), $val, \PHPExcel_Cell_DataType::TYPE_STRING);
				//设置表格样式
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
				//加边框
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$objPHPExcel->getActiveSheet()->getStyle($xAxisArray[$k] . ($j + 3))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				++$k;
			}
		}
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
		header("Content-Disposition:attachment;filename=$xlsTitle.xls"); //attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
}
