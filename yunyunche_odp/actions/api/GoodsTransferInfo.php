<?php 
class Action_GoodsTransferInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$transferId = $arrInput['transferId'];
		if (empty($transferId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsTransferDao = new Dao_GoodsTransfer();
		$transferInfo = $goodsTransferDao->getInfo($transferId);
		if (empty($transferInfo) || !is_array($transferInfo)) {
			return Tool_Util::returnJson();
		}

		$goodsTransferInfoDao = new Dao_GoodsTransferInfo();
		$goodsTransferInfos = $goodsTransferInfoDao->getInfo($transferId);
		if (empty($goodsTransferInfos) || !is_array($goodsTransferInfos)) {
			return Tool_Util::returnJson();
		}
		$goodsIds = array();
		foreach ($goodsTransferInfos as $goodsInfo) {
			$goodsIds[] = $goodsInfo['goods_id'];
		}

		if (empty($goodsIds) || !is_array($goodsIds)) {
			return Tool_Util::returnJson();
		}
		$goodsInfoDao = new Dao_GoodsInfo();
		$goodsInfos = $goodsInfoDao->getInfos($goodsIds);
		foreach ($goodsInfos as $goods) {
			$goodsInfoSorted[$goods['goods_id']] = $goods;
		}
		$item = array();
		foreach ($goodsTransferInfos as $goodsInfo) {
			$item[] = array_merge($goodsInfoSorted[$goodsInfo['goods_id']], $goodsInfo);
		}
		$transferInfo['items'] = json_encode($item);
		return Tool_Util::returnJson($transferInfo);
	}
}
