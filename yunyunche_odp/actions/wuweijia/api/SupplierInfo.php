<?php 
class Action_SupplierInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$supplierId = $arrInput['supplierId'];

		$supplierDao = new Dao_SupplierInfo(Tool_Const::$storeId);
		$info = $supplierDao->getSupplierInfo($supplierId);

		return Tool_Util::returnJson(array('info' => $info));
	}
}
