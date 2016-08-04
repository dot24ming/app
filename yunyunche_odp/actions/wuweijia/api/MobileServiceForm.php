<?php 
class Action_MobileServiceForm extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$plateNum = $arrInput['plate_number'];
		if (empty($plateNum)) {
			return Tool_Util::returnJson('', 1);
		}
		$checkClientDao = new Dao_CarInfo(Tool_Const::$storeIdb);
		$carinfo = $checkClientDao->getInfoByPlateNumber($plateNum);
		if (empty($carinfo)) {
			return Tool_Util::returnJson('', 1);
		}
		return Tool_Util::returnJson();
	}
}
