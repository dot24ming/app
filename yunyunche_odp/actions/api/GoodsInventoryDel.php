<?php 
class Action_GoodsInventoryDel extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$inventoryId = $arrInput['inventoryId'];
		if (empty($inventoryId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsInventoryDao = new Dao_GoodsInventory();
		$ret = $goodsInventoryDao->delete($inventoryId);
		if ($ret) {
			return Tool_Util::returnJson();
		} else {
			return Tool_Util::returnJson('', 1, '删除失败');
		}
	}
}
