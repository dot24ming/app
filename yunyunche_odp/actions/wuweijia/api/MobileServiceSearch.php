<?php 
class Action_MobileServiceSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$type3Name = $arrInput['type3_name'];
		$type3Name = '高级蜡';
		if (empty($type3Name)) {
			return Tool_Util::returnJson('', 1);
		}
		$mobileServicesDao = new Dao_MobileServices(Tool_Const::$storeId);
		$service = $mobileServicesDao->getServiceByType3Name($type3Name);
		if (empty($service)) {
			return Tool_Util::returnJson('', 1);
		}
		return Tool_Util::returnJson(array("info"=>$service));
	}
}
