<?php 
class Action_AddWarehouse extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$name = Tool_Util::filter($arrInput['name']);

		if (empty($name)) {
			return Tool_Util::returnJson('', '参数错误', 1);
		}
		$warehouseInfoDao = new Dao_WarehouseInfo();
	
		$ret = $warehouseInfoDao->addWarehouse($name);

		if ($ret < 0 || is_array($ret)) {
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
		}

		return Tool_Util::returnJson('');
	}
}
