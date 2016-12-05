<?php
namespace Admin\Controller;
use Lib\FileUtil;
use Lib\Ftp;

/**
 * 文件处理
 */
class FileHandleController extends CommonController {
    /**
     * 导出Excel
     * @param $title        Excel表格Title
     * @param $fileName     Excel文件名
     * @param $titleArray   Excel列头数组
     * @param $data         Excel结果集
     * @return bool         如果参数缺失返回false
     */
    public function createExcel($title,$fileName,$titleArray,$data){
        if(!isset($title) || !isset($fileName) || !isset($titleArray) || !isset($data)){
            return false;
        }
        $cellNum = count($titleArray);
        $dataNum = count($data);
        Vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        //合并表头单元格
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        //设置表格title
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //设置表格title格式居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置宽度
        for($i = 0;$i < $cellNum;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(20);
        }

        //插入表格字段名
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $titleArray[$i]);
            //设置单元格文字居中
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . 2)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . 2)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . 2)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . 2)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i] . 2)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        //设置背景色
        $objPHPExcel->getActiveSheet()->getStyle("A2:{$cellName[$cellNum - 1]}2")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle("A2:{$cellName[$cellNum - 1]}2")->getFill()->getStartColor()->setARGB("#0f0f0f");
        // Miscellaneous glyphs, UTF-8
        //往表格插入对应的数据
        for ($i = 0; $i < $dataNum; $i++) {
            $j = 0;
            foreach ($data[$i] as $key => $val) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $val, \PHPExcel_Cell_DataType::TYPE_STRING);
                //设置单元格文字居中
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                //加边框
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j] . ($i + 3))->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $j++;
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $fileName . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }
}