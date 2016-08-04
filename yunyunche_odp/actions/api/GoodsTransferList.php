<?php 
class Action_GoodsTransferList extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$start = $arrInput['start'];
		$end = $arrInput['end'];

		$goodsTransferDao = new Dao_GoodsTransfer();
		$list = $goodsTransferDao->getList($start, $end, $total);

		return Tool_Util::returnJson(array('list' => $list, 'total' => $total));
	}
}
