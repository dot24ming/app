<?php 
class Action_ReportSearchLoad extends Ap_Action_Abstract {
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

		//return Tool_Util::returnJson($titles);
		//$plateNum = "粤B0M881";
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
