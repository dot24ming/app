<?php
class Action_MobileServiceSelect extends Ap_Action_Abstract {
	public function execute() {
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		$services = $mobileServicesDao->getServicesInfo();

		$type1Names = array();	
		foreach ($services as $service){
			$type1Name = $service['type1_name'];
			if (!array_key_exists($type1Name, $type1Names)){
				$type1Names[$type1Name] = array();
			}
			array_push($type1Names[$type1Name], $service);
		}
		$servicesRes = array();
		foreach ($type1Names as $type1Name => $services){
			$servicesInfo = array();
			foreach ($services as $service){
				$type2Name = $service['type2_name'];
				if (array_key_exists($type2Name, $servicesInfo)){
					array_push($servicesInfo[$type2Name], $service);
				}
				else {
					$servicesInfo[$type2Name] = array();
					array_push($servicesInfo[$type2Name], $service);
				}
			}
			$servicesRes[$type1Name] = $servicesInfo;
		}
		return Tool_Util::returnJson(array('info' => $servicesRes));

	}

}
