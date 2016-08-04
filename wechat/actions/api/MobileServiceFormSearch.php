<?php 
class Action_MobileServiceFormSearch extends Ap_Action_Abstract {
	public function execute() {
		Bd_Log::addNotice('out', 'asdasd');
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$plateNum = $arrInput['plate_number'];
		$timeStart = $arrInput['time_start'];
		$timeEnd = $arrInput['time_end'];

		$store_id = Tool_Const::$storeId;

		$mobileServiceFormDao = new Dao_MobileServiceForm($store_id);
		$serviceForms = $mobileServiceFormDao->getServiceForm($plateNum);

		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo($store_id);
		Bd_Log::addNotice('out', $store_id);

		$mobileServicesDao = new Dao_MobileServices();

		$data = array();
		//return Tool_Util::returnJson($serviceForms);
		foreach ($serviceForms as $serviceForm){
			$serviceFormInfos = $mobileServiceFormInfoDao->getServiceFormInfo($serviceForm['form_id']);
			array_push($serviceForm['services'], $serviceForm);
			//return Tool_Util::returnJson($serviceFormInfos);
			$serviceForm['services'] = array();
			foreach ($serviceFormInfos as $serviceFormInfo){
				$serviceId = $serviceFormInfo['service_id'];
				if (empty($serviceId)){
					$newService = array();
					$newService['name'] = $serviceFormInfo['service_name'];
					$newService['count'] = $serviceFormInfo['count'];
					$newService['remark'] = $serviceFormInfo['remark'];
					$newService['price'] = $serviceFormInfo['price'];
					array_push($serviceForm['services'], $newService);
				}
				else {
					$service = $mobileServicesDao->getInfoByType3Id($serviceId);
					$service['count'] = $serviceFormInfo['count'];
					$service['remark'] = $serviceFormInfo['remark'];
					$service['price'] = $serviceFormInfo['price'];
					array_push($serviceForm['services'], $service);
				}
			}
			array_push($data, $serviceForm);
		}

		return Tool_Util::returnJson($data);
	}
}
