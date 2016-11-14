<?php 
namespace Common\Utils;
class EXCELBuilder{
	private $execlBuilder=null;
	private $objPHPExcel=null;
	private $objWriter=null;
	
	private $style=null;
	
	private function __construct(){
		import("Org.Util.PHPExcel");
    	import("Org.PHPExcel.IOFactory");
		import("Org.Util.PHPExcel.PHPExcel");
		
		$this->objPHPExcel=new \PHPExcel();
		$this->objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
		//$this->style=\PHPExcel_Style();
	}
	
	public function getInstance(){
		if($this->execlBuilder==null){
			$this->execlBuilder=new EXCELBuilder();
		}
		return $this->execlBuilder;
	}
	
	public function getObjPHPExcel(){
		return $this->objPHPExcel;
	}
	
	
	public function setCellValue($activeSheet,$position,$value){
		$this->objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue($position,$value);
	}
	
	public function setCellValueExplicit($activeSheet,$position,$value){
		$this->objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValueExplicit($position,$value);
	}
	
	public function setColumnWidth($activeSheet,$column,$width){
		$this->objPHPExcel->setActiveSheetIndex($activeSheet)->getColumnDimension($column)->setWidth($width);
	}
	/*
	 * 	$style="none";
		$style="dashDot";
		$style="dashDotDot";
		$style="dashed";
		$style="dotted";
		$style="double";
		
		
		$style="hair";
		$style="medium";
		$style="mediumDashDot";
		
		$style="mediumDashDotDot";
		$style="mediumDashed";
		$style="slantDashDot";
		
		$style="thick";
		$style="thin";
	 */
	public function setOutlineBorder($activeSheet,$position,$style){

		
		//$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getRight()->setBorderStyle($style); 
		//$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getTop()->setBorderStyle($style);  
		//$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getLeft()->setBorderStyle($style);  
		//$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getBottom()->setBorderStyle($style);
		
		
		$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getOutline()->setBorderStyle($style);
		
		//$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getAllBorders()->setBorderStyle($style);
		
		
	}
	public function setInsideBorder($activeSheet,$position,$style){
		$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getBorders()->getInside()->setBorderStyle($style);
	}
	
	public function setHorizontal($activeSheet,$position,$style){
		$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getAlignment()->setHorizontal("center");
	}
	
	public function setVertical($activeSheet,$position,$style){
		$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getAlignment()->setVertical("center");
	}
	
	public function setFontSize($activeSheet,$position,$size){
		$this->objPHPExcel->getActiveSheet($activeSheet)->getStyle($position)->getFont()->setSize($size); 
	}
	
	public function mergeCells($activeSheet,$cells){
		$this->objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells($cells);
	}
	
	public function FileOutput($activeSheet,$fileName){
		$this->objPHPExcel->setActiveSheetIndex($activeSheet);
		// 输出
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
		header('Cache-Control: max-age=0');
		header("Pragma: no-cache");
		
		
		$this->objWriter->save('php://output');
	}
	

}
