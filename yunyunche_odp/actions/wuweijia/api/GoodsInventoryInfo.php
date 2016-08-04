<?php 
class Action_GoodsInventoryInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$inventoryId = $arrInput['inventoryId'];
		if (empty($inventoryId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$goodsInventoryDao = new Dao_GoodsInventory();
		$inventoryInfo = $goodsInventoryDao->getInfo($inventoryId);

		$goodsInventoryInfoDao = new Dao_GoodsInventoryInfo();
		$goodsList = $goodsInventoryInfoDao->getInfo($inventoryId);

		if (empty($goodsList) || !is_array($goodsList)) {
			return Tool_Util::returnJson();
		}

		$goodsIds = array();
		foreach ($goodsList as $item) {
			$goodsIds[] = $item['goods_id'];
		}
		$goodsInfoDao = new Dao_GoodsInfo();
		$goodsInfos = $goodsInfoDao->getInfos($goodsIds);
		if (is_array($goodsInfos) && $goodsInfos) {
			foreach ($goodsInfos as $item) {
				$goodsInfoSorted[$item['goods_id']] = $item;
			}
		}
		foreach ($goodsList as $goods) {
			$goodsItems[] = array_merge($goods, $goodsInfoSorted[$goods['goods_id']]);
		}
		$inventoryInfo['items'] = json_encode($goodsItems);
		return Tool_Util::returnJson($inventoryInfo);
	}
}
