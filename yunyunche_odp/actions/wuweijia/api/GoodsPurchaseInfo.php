<?php 
class Action_GoodsPurchaseInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$purchaseId = $arrInput['purchaseId'];
		if (empty($purchaseId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$goodsPurchaseDao = new Dao_GoodsPurchase();
		$purchaseInfo = $goodsPurchaseDao->getInfo($purchaseId);
		if (empty($purchaseInfo) || !is_array($purchaseInfo)) {
			return Tool_Util::returnJson();
		}

		$goodsPurchaseInfoDao = new Dao_GoodsPurchaseInfo();
		$purchaseInfos = $goodsPurchaseInfoDao->getInfo($purchaseId);

		if (empty($purchaseInfos) || !is_array($purchaseInfos)) {
			return Tool_Util::returnJson();
		}
		foreach ($purchaseInfos as $purchase) {
			$goodsIds[] = $purchase['goods_id'];
		}
		$goodsIds = array_unique($goodsIds);
		$goodsInfoDao = new Dao_GoodsInfo();
		$goodsInfos = $goodsInfoDao->getInfos($goodsIds);
		foreach ($goodsInfos as $info) {
			$goodsInfoSorted[$info['goods_id']] = $info;
		}
		foreach ($purchaseInfos as $purchase) {
			$items[] = array_merge($goodsInfoSorted[$purchase['goods_id']], $purchase);
		}
		$purchaseInfo['items'] = json_encode($items);

		return Tool_Util::returnJson($purchaseInfo);
	}
}
