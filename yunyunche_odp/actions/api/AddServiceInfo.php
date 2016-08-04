<?php
class Action_AddServiceInfo extends Ap_Action_Abstract {
	public function execute() {
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		$data = $arrInput['service'];
		$serviceInfo = json_decode($data, true);
		//$a = array(
		//	'type1_name' => 'hello',
		//	'type2_name' => 'world',
		//	'type3_name' => 'omg',
		//	'price' => 120,
		//	);
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		$status = $mobileServicesDao->setInfo($serviceInfo);
		//if ($status){
		//	$status = 0;
		//}
		//else {
		//	$status = 1;
		//}
		return Tool_Util::returnJson($status);
	}
}
