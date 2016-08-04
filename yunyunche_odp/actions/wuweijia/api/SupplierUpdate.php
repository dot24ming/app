<?php 
class Action_SupplierUpdate extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		$supplierId = $arrInput['supplier_id'];
		$supplierName = Tool_Util::filter($arrInput['supplier_name']);
		$info = Tool_Util::filter($arrInput['info']);
		$address = Tool_Util::filter($arrInput['address']);
		$phone = Tool_Util::filter($arrInput['phone']);
		$linkman = Tool_Util::filter($arrInput['linkman']);

		$supplierDao = new Dao_SupplierInfo(Tool_Const::$storeId);
		$ret = $supplierDao->updateSupplierInfo($supplierId, $supplierName, $info, $address, $phone, $linkman);
		if ($ret) {
			return Tool_Util::returnJson();
		} else {
			return Tool_UTil::returnJson('', 1, '更新失败');
		}
	}
}
