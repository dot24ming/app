<?php 
class Action_ReportSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$timeStart = $arrInput['time_start'];
		if (empty($timeStart)){
			$timeStart = date('y-m',time());
		}
		$timeEnd = $arrInput['time_end'];
		if (empty($timeEnd)){
			$timeEnd = date('y-m-d h:i:s',time());
		}
		$start = $arrInput['start'];
		if (empty($start)){
			$start = 0;
		}
		$end = $arrInput['end'];
		if (empty($end)){
			$end = 10;
		}

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
			$costSum = 0;
			foreach($serviceFormInfos as $serviceFormInfo){
				$costSum += $serviceFormInfo['cost']*$serviceFormInfo['count'];
			}
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

		$count = count($data);
		if ($end > $count){
			$end = $count;
		}
		$len = $end - $start;

		$balancesRes = array_splice($data, $start, $len);
		return Tool_Util::returnJsonEx($balancesRes, 0, "", $count);
	}
}
