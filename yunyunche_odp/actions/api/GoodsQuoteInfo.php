<?php 
class Action_GoodsQuoteInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$quoteId = $arrInput['quoteId'];

		if (empty($quoteId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$goodsQuoteDao = new Dao_GoodsQuote();
		$quoteDetail = $goodsQuoteDao->getInfo($quoteId);
		if (empty($quoteDetail) || !is_array($quoteDetail)) {
			return Tool_Util::returnJson();
		}

		$goodsQuoteInfoDao = new Dao_GoodsQuoteInfo();
		$quoteInfo = $goodsQuoteInfoDao->getInfo($quoteId);

		$items = array();
		$goodsIds = array();
		if (empty($quoteInfo) || !is_array($quoteInfo)) {
			return Tool_Util::returnJson();
		}

		foreach ($quoteInfo as $quote) {
			$goodsIds[] = $quote['goods_id'];
		}
		$goodsInfoDao = new Dao_GoodsInfo();
		$goodsInfos = $goodsInfoDao->getInfos($goodsIds);

		if (empty($goodsInfos) || !is_array($goodsInfos)) {
			return Tool_Util::returnJson();
		}
		foreach ($goodsInfos as $item) {
			$goodsInfoSorted[$item['goods_id']] = $item;
		}

		$goodsQuoteDetailDao = new Dao_GoodsQuoteDetail();
		$quoteInfos = $goodsQuoteDetailDao->getInfos($goodsIds, $quoteId);

		if (empty($quoteInfos) || !is_array($quoteInfos)) {
			return Tool_Util::returnJson();
		}
		$supplierDao = new Dao_SupplierInfo();
		$suppliers = $supplierDao->getInfo();
		foreach ($quoteInfos as $quoteInfo) {
			$quoteDetails[$quoteInfo['goods_id']][] = $suppliers[$quoteInfo['supplier_id']];
		}
		foreach ($goodsInfos as &$item) {
			$item['quote_details'] = $quoteDetails[$item['goods_id']];
		}
		$quoteDetail['quote_items'] = json_encode($goodsInfos);
		return Tool_Util::returnJson($quoteDetail);
	}
}
