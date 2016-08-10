<?php 
class Action_OutstockExport extends Ap_Action_Abstract {
	public function execute() {

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];

		$timeStart = $arrInput['time_start'];
		if (empty($timeStart)){
			$timeStart = date('y-m',time());
		}
		$timeEnd = $arrInput['time_end'];
		if (empty($timeEnd)){
			$timeEnd = date('y-m-d H:i:s',time());
		}
		else{
			$halfDay = date(' H:i:s',time());
			$timeEnd = $timeEnd.$halfDay;  
		}
		$start = $arrInput['start'];
		if (empty($start)){
			$start = 0;
		}
		$end = $arrInput['end'];
		if (empty($end)){
			$end = 10;
		}

		$titlesStr = $arrInput['titles'];
		if (empty($titlesStr)){
			$titlesStr = '';
		}
		$titles = json_decode($titlesStr, true);

		//TODO
		//$goodsStorageDao = new Dao_GoodsStorage();
		//$list = $goodsStorageDao->getListByTime($start, $end, $total, $startDate, $endDate);
		//var_dump($timeStart);
		//var_dump($timeEnd);
		

		$goodsShipmentDao = new Dao_GoodsShipment();
		//$list = $goodsShipmentDao->getListByTime($start, $end, $total, $startDate, $endDate);
		$list = $goodsShipmentDao->getListByTime($start, $end, $total, $timeStart, $timeEnd);


		//exit;
		$list_size = count($list);
		for($i=0;$i<$list_size;$i++)
		{
			$shipment_id = $list[$i]['shipment_id'];
			$goodsShipmentInfoDao = new Dao_GoodsShipmentInfo();
			$goodsInfos = $goodsShipmentInfoDao->getInfo($shipment_id);
			$list[$i]['detail'] = $goodsInfos;

			//var_dump(Tool_Const::$storage_type_e2c[$list[$i]['storage_type']]);
			$list[$i]['shipment_type'] = Tool_Const::$shipment_type_e2c[$list[$i]['shipment_type']];
			$list[$i]['shipment_status'] = Tool_Const::$form_status_e2c[$list[$i]['shipment_status']];	
		}
		$data = $list;
		/*
		foreach($list as $instock_items)
		{
			$storage_id = $instock_items['storage_id'];
			$goodsStorageDetail = $goodsStorageDao->getInfo($storage_id);
		}
		 */

		//var_dump($list);
		//exit;
		$curr_time = date('y-m-d',time());
		$fileName = Tool_ExcelUtil::createExcelCommon('出库报表-'.$curr_time, $titles, $data);
		return Tool_ExcelUtil::returnFile($fileName, '出库报表-'.$curr_time.'.xlsx');
		exit;

		




		$mobileServiceFormDao = new Dao_MobileServiceForm(Tool_Const::$storeId);
		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo(Tool_Const::$storeId);
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		$serviceForms = array();
		$serviceForms = $mobileServiceFormDao->getServiceFormByTime("", $timeStart, $timeEnd);
		if (empty($serviceForms)){
			return Tool_Util::returnJson('', 1, "无查询结果");
		}
		//todo:增加其他查询方式
		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$data = array(); //详情列表
		foreach ($serviceForms as $serviceForm){
			$plateNum = $serviceForm['plate_number'];
			$formId = $serviceForm['form_id'];
			$carInfo = $carInfoDao->getInfoByPlateNumber($plateNum);
			if (empty($carInfo)){
				$serviceForm['client_name'] = "非注册用户";
				$serviceForm['phone_num'] = "非注册用户";
				continue;
			}
			$userId = $carInfo['owner_id'];
			$userInfo = $userInfoDao->getUserInfoByUserId($userId);
			if (empty($userInfo)){
				$serviceForm['client_name'] = "非注册用户";
				$serviceForm['phone_num'] = "非注册用户";
				continue;
			}
			$serviceForm['client_name'] = $userInfo['name'];
			$serviceForm['phone_num'] = $userInfo['phone_num'];
			$serviceFormInfos = $mobileServiceFormInfoDao->getServiceFormInfo($formId);
			$costSum = 0.0;
			foreach($serviceFormInfos as $serviceFormInfo){
				$costSum += $serviceFormInfo['cost'];
			}
			/*
			foreach($serviceFormInfos as $serviceFormInfo){
				$constructers = $serviceFormInfo['constructer'];
				//$constructers = "无";
				$cost = $serviceFormInfo['cost'];
				$costPerItem = 0.0;
				if ($serviceForm['settlement_amount'] == $costSum || $costSum == 0.0){
					$costPerItem = $cost;
				}
				else {
					$costPerItem = $serviceForm['settlement_amount'] * $cost / $costSum;	
				}
				if (!empty($constructers)){
					$const = explode(',', $constructers);
					foreach($const as $cons){
						$serviceForm['constructer'] = $cons;
						$serviceForm['cost_per_item'] = $costPerItem / count($const);
						$serviceForm["item_name"] = $serviceFormInfo['service_name'];
						$serviceForm["count"] = $serviceFormInfo['count'];
						array_push($data, $serviceForm);
					}
				}
				else{
					$serviceForm['constructer'] = "无";
					$serviceForm['cost_per_item'] = $costPerItem;
					$serviceForm["item_name"] = $serviceFormInfo['service_name'];
					$serviceForm["count"] = $serviceFormInfo['count'];
					array_push($data, $serviceForm);
				
				}
			}
			 */
			foreach($serviceFormInfos as $serviceFormInfo){
				$constructers = $serviceFormInfo['constructer'];
				$cost = $serviceFormInfo['cost'] * $serviceFormInfo['count'];
				$costPerItem = 0.0;
				if ($serviceForm['settlement_amount'] == $costSum || $costSum == 0.0){
					$costPerItem = $serviceFormInfo['cost'];
				}
				else {
					$costPerItem = $serviceForm['settlement_amount'] * $cost / $costSum / $serviceFormInfo['count'];
				}
				$serviceForm['constructer'] = $constructers;
				$serviceForm['cost_per_item'] = $costPerItem;
				$serviceForm["item_name"] = $serviceFormInfo['service_name'];
				$serviceForm["item_type"] = $serviceFormInfo['type'];
				$serviceForm["count"] = $serviceFormInfo['count'];
				$serviceForm["price"] = $serviceFormInfo['count'] * $costPerItem;
				if ($serviceForm["settlement"] == "联合结算"){
					$serviceForm["settlement"] = $serviceForm["settlement"].":".$serviceForm["settlement_ex"];
				}
				array_push($data, $serviceForm);
				
			}
		}
	
		foreach ($data as $dat){
			$tim[] = $dat['time'];
		}

		array_multisort($tim, SORT_DESC, $data);

		//return Tool_Util::returnJson($data);
		$fileName = Tool_Util::createExcel('业务报表', $titles, $data);
		return Tool_Util::returnFile($fileName, '业务报表.xlsx');
	}
}
