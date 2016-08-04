<?php 
class Action_WarehouseList extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();

		$warehouseInfoDao = new Dao_WarehouseInfo();
	
		$ret = $warehouseInfoDao->getInfo();
		return Tool_Util::returnJson($ret);
	}
}
