<?php 
class Action_SupplierList extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();

		$supplierDao = new Dao_SupplierInfo();
		$list = $supplierDao->getInfo();

		return Tool_Util::returnJson(array('list' => $list));
	}
}
