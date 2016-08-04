<?php 
class Action_GoodsInventoryList extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$start = $arrInput['start'];
		$end = $arrInput['end'];
		$startDate = isset($arrInput['startDate']) ? $arrInput['startDate'] : '';
		$endDate = isset($arrInput['endDate']) ? $arrInput['endDate'] : '';

		$goodsInventoryDao = new Dao_GoodsInventory();
		$list = $goodsInventoryDao->getList($start, $end, $total, $startDate, $endDate);

		return Tool_Util::returnJson(array('list' => $list, 'total' => $total));
	}
}
