<?php 
class Action_MobileServiceFormSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$plateNum = $arrInput['plate_number'];
		if (empty($plateNum)){
			$plateNum = "";
		}
		$timeStart = $arrInput['time_start'];
		if (empty($timeStart)){
			$timeStart = "";
		}
		$timeEnd = $arrInput['time_end'];
		if (empty($timeEnd)){
			$timeEnd = "";
		}
		else{
			$halfDay = date(' H:i:s',time());
			$timeEnd = $timeEnd.$halfDay;  
		}
		
		$userId = $arrInput['user_id'];
		if (empty($userId)){
			$userId = "";
		}

		$settlementTimeStart = $arrInput['settlement_time_start'];
		$settlementTimeEnd = $arrInput['settlement_time_end'];

		$start = $arrInput['start'];
		if (empty($start)){
			$start = 0;
		}
		$end = $arrInput['end'];
		if (empty($end)){
			$end = 10;
		}

		$formId = $arrInput['form_id'];
		if (empty($formId)){
			$formId = -1;
		}

		$status = $arrInput['status'];
		if (is_null($status)){
			$status = "";
		}
		//return Tool_Util::returnJson($status);

		$settlement = $arrInput['settlement'];
		if (empty($settlement)){
			$settlement = "";
		}

		//$plateNum = "粤B0M881";
		$mobileServiceFormDao = new Dao_MobileServiceForm(Tool_Const::$storeId);
		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo(Tool_Const::$storeId);
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		if ($userId != ""){
			$serviceForms = $mobileServiceFormDao->getServiceFormByUID($userId, $status);
		} else {
			$serviceForms = array();
			$serviceForms0 = array();
			$serviceForms1 = array();
			$serviceForms2 = array();
			$serviceForms3 = array();
			$serviceForms4 = array();
			if ($formId > -1){//优先用单号查询
				$serviceFormDB = $mobileServiceFormDao->getServiceFormById($formId);
				array_push($serviceForms0, $serviceFormDB);
			}
			if ($plateNum != ""){
				$serviceForms1 = $mobileServiceFormDao->getServiceFormByPlateNum($plateNum);
			}
			if ($timeStart != "" && $timeEnd != "") { //无单号用时间查询, 如果时间为空,则读取最新的服务单数据
				$serviceForms2 = $mobileServiceFormDao->getServiceFormByTime($plateNum, $timeStart, $timeEnd);
			}
			if ($status != ""){
				$serviceForms3 = $mobileServiceFormDao->getServiceFormByStatus($status);
			}
			if ($settlement != ""){
				$serviceForms4 = $mobileServiceFormDao->getServiceFormBySettlement($settlement);
			}
			$serviceIds = array();
			$serviceIds0 = array();
			$serviceIds1 = array();
			$serviceIds2 = array();
			$serviceIds3 = array();
			$serviceIds4 = array();
			if (!empty($serviceForms0)){
				foreach($serviceForms0 as $serviceForm){
					array_push($serviceIds0, $serviceForm['form_id']);
				}
				$serviceIds = $serviceIds0;
			}
			if (!empty($serviceForms1)){
				foreach($serviceForms1 as $serviceForm){
					array_push($serviceIds1, $serviceForm['form_id']);
				}
				$serviceIds = $serviceIds1;
			}
			if (!empty($serviceForms2)){
				foreach($serviceForms2 as $serviceForm){
					array_push($serviceIds2, $serviceForm['form_id']);
				}
				$serviceIds = $serviceIds2;
			}
			if (!empty($serviceForms3)){
				foreach($serviceForms3 as $serviceForm){
					array_push($serviceIds3, $serviceForm['form_id']);
				}
				$serviceIds = $serviceIds3;
			}
			if (!empty($serviceForms4)){
				foreach($serviceForms4 as $serviceForm){
					array_push($serviceIds4, $serviceForm['form_id']);
				}
				$serviceIds = $serviceIds4;
			}

			if (empty($serviceIds0)){
				$serviceIds0 = $serviceIds;
			}
			if (empty($serviceIds1)){
				$serviceIds1 = $serviceIds;
			}
			if (empty($serviceIds2)){
				$serviceIds2 = $serviceIds;
			}
			if (empty($serviceIds3)){
				$serviceIds3 = $serviceIds;
			}
			if (empty($serviceIds4)){
				$serviceIds4 = $serviceIds;
			}
			$serviceIds = array_intersect($serviceIds0, $serviceIds1, $serviceIds2, $serviceIds3, $serviceIds4);
			//return Tool_Util::returnJson($serviceIds);
			$serviceFormsTmp = array_merge($serviceForms0, $serviceForms1, $serviceForms2, $serviceForms3, $serviceForms4);
			foreach ($serviceIds as $serviceId){
				foreach ($serviceFormsTmp as $serviceForm){
					if ($serviceId == $serviceForm['form_id']){
						array_push($serviceForms, $serviceForm);
						break;
					}
				}
			}
			if (empty($serviceForms) && $timeStart == "" && $timeEnd == ""){//兼容wise参数无时间模式
				$timeEndNew = date('Y-m-d H:i:s',time());
				$timeStartNew = date('Y-m-d', time()-2592000);
				$serviceForms = $mobileServiceFormDao->getServiceFormByTime($plateNum, $timeStartNew, $timeEndNew);
			}
		} 
		if (empty($serviceForms)){
			return Tool_Util::returnJson('', 1, "无查询结果");
		}
		//todo:增加其他查询方式
		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$total = count($serviceForms);
		for ($i = 0; $i < count($serviceForms); $i++){
			$plateNum = $serviceForms[$i]['plate_number'];
			$carInfo = $carInfoDao->getInfoByPlateNumber($plateNum);
			if (empty($carInfo)){
				$serviceForms[$i]['client_name'] = "非注册用户";
				$serviceForms[$i]['phone_num'] = "非注册用户";
				continue;
			}
			$userId = $carInfo['owner_id'];
			$userInfo = $userInfoDao->getUserInfoByUserId($userId);
			if (empty($userInfo)){
				$serviceForms[$i]['client_name'] = "非注册用户";
				$serviceForms[$i]['phone_num'] = "非注册用户";
				continue;
			}
			$serviceForms[$i]['client_name'] = $userInfo['name'];
			$serviceForms[$i]['phone_num'] = $userInfo['phone_num'];
		}

		$data = array(); //详情列表
		//return Tool_Util::returnJson($serviceForms);
		$index = 0;
		foreach ($serviceForms as $serviceForm){
			if ( $formId < 0){
				$index = $index + 1;
				if ($index < $start+1 || $index > $end){
					continue;
				}
			}
			$serviceFormInfos = $mobileServiceFormInfoDao->getServiceFormInfo($serviceForm['form_id']);
			$serviceForm['services'] = array();
			foreach ($serviceFormInfos as $serviceFormInfo){
				$serviceId = $serviceFormInfo['service_id'];
				if (empty($serviceId)){
					$newService = array();
					$newService['type3_name'] = $serviceFormInfo['service_name'];
					$newService['service_id'] = $serviceFormInfo['service_id'];
					$newService['count'] = $serviceFormInfo['count'];
					$newService['remarks'] = $serviceFormInfo['remarks'];
					$newService['price'] = $serviceFormInfo['price'];
					$newService['cost'] = $serviceFormInfo['cost'];
					$newService['constructer'] = $serviceFormInfo['constructer'];
					$newService['type'] = $serviceFormInfo['type'];
					$newService['package_card_id'] = $serviceFormInfo['package_card_id'];
					array_push($serviceForm['services'], $newService);
				}
				else {
					$service = $mobileServicesDao->getInfoByType3Id($serviceId);
					$service['type3_name'] = $serviceFormInfo['service_name'];
					$service['service_id'] = $serviceFormInfo['service_id'];
					$service['count'] = $serviceFormInfo['count'];
					$service['remarks'] = $serviceFormInfo['remarks'];
					$service['price'] = $serviceFormInfo['price'];
					$service['cost'] = $serviceFormInfo['cost'];
					$service['constructer'] = $serviceFormInfo['constructer'];
					$service['type'] = $serviceFormInfo['type'];
					$service['package_card_id'] = $serviceFormInfo['package_card_id'];
					array_push($serviceForm['services'], $service);
				}
			}
			array_push($data, $serviceForm);
		}
		return Tool_Util::returnJsonEx($data, 0, "", $total);
	}
}
