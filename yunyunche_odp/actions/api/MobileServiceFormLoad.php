<?php 
include 'phpexcel/PHPExcel.php';
include 'phpexcel/PHPExcel/Writer/Excel2007.php';
class Action_MobileServiceFormLoad extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$plateNum = $arrInput['plate_number'];
		//$formIds = $arrInput['form_ids'];
		$timeStart = $arrInput['time_start'];
		$timeEnd = $arrInput['time_end'];
		//$start = $arrInput['start'];
		//$end = $arrInput['end'];

		$mobileServiceFormDao = new Dao_MobileServiceForm(Tool_Const::$storeId);
		$serviceForms = $mobileServiceFormDao->getServiceFormByTime($plateNum, $timeStart, $timeEnd);
		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo(Tool_Const::$storeId);
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		$data = array();
		foreach ($serviceForms as $serviceForm){
			$serviceFormInfos = $mobileServiceFormInfoDao->getServiceFormInfo($serviceForm['form_id']);
			$serviceFormNew = array();
			foreach ($serviceFormInfos as $serviceFormInfo){
				$serviceId = $serviceFormInfo['service_id'];
				if (empty($serviceId)){
					$newService = array();
					$newService = $serviceForm;
					$newService['type3_name'] = $serviceFormInfo['service_name'];
					$newService['count'] = $serviceFormInfo['count'];
					$newService['remarks'] = $serviceFormInfo['remarks'];
					$newService['price'] = $serviceFormInfo['price'];
					array_push($data, $newService);
				}
				else {
					$newService = array();
					$newService = $serviceForm;
					$service = $mobileServicesDao->getInfoByType3Id($serviceId);
					$service['count'] = $serviceFormInfo['count'];
					$service['remarks'] = $serviceFormInfo['remarks'];
					$service['price'] = $serviceFormInfo['price'];
					$newService = $newService + $service;
					array_push($data, $newService);
				}
			}
		}
		$objPHPExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$rowNames = array("单号","车牌号码", "消费时间", "消费项目", "服务类型", "数量", "金额", "收讫状态", "收银员", "备注说明");
		$index = 'A';
		$rowIndex = 1;
		$rowNamesLink = array("form_id", "plate_number", "time", "type2_name", "type3_name", "count", "price", "settlement", "person", "remarks");
		foreach ($rowNames as $rowName){
			$position = $index.$rowIndex;
			$objPHPExcel->getActiveSheet()->setCellValue($position, $rowName);
			$objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->getStartColor()->setARGB('FF808080');
			$index++;
		}
		$rowIndex++;
		foreach ($data as $record){
			$index = 'A';
			foreach ($rowNamesLink as $rowNameLink){
				$position = $index.$rowIndex;
				if (empty($record[$rowNameLink])){
					$record[$rowNameLink] = "";
				}
				$objPHPExcel->getActiveSheet()->setCellValue($position, $record[$rowNameLink]);
				$index++;
			}
			$rowIndex++;
		}
		$fileName = rand();
		$objWriter->save($fileName);
		$file = fopen($fileName,"r"); // 打开文件
		// 输入文件标签
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($fileName));
		Header("Content-Disposition: attachment; filename=" . "form.xlsx");
		// 输出文件内容
		echo fread($file,filesize($fileName));
		fclose($fileName);
		@unlink($fileName);
	}
}
