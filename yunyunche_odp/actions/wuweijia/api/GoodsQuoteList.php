<?php 
class Action_GoodsQuoteList extends Saf_Api_Base_Action {
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

		$goodsQuoteInfoDao = new Dao_GoodsQuoteInfo();
		$goodsQuoteList = $goodsQuoteInfoDao->getList($start, $end, $total, $startDate, $endDate);
		$goods = array();
		$quotes = array();
		if (empty($goodsQuoteList) || !is_array($goodsQuoteList)) {
			return Tool_Util::returnJson(array('list' => array(), 'total' => 0));
		}

		foreach ($goodsQuoteList as $quote) {
			$goods[] = $quote['goods_id'];
			$quotes[] = $quote['quote_id'];
		}
		$goods = array_unique($goods);
		$quotes = array_unique($quotes);

		$goodsInfoes = array();
		$quoteInfoes = array();
		if ($goods) {
			$goodsInfoDao = new Dao_GoodsInfo();
			$goodsInfo = $goodsInfoDao->getInfos($goods);
			if ($goodsInfo) {
				foreach ($goodsInfo as $info) {
					$goodsInfoes[$info['goods_id']] = $info;
				}
			}
		}
		if ($quotes) {
			$goodsQuoteDao = new Dao_GoodsQuote();
			$quoteInfo = $goodsQuoteDao->getInfos($quotes);
			if ($quoteInfo) {
				foreach ($quoteInfo as $info) {
					$quoteInfoes[$info['quote_id']] = $info;
				}
			}
		}
		foreach ($goodsQuoteList as &$quote) {
			$quote = array_merge($goodsInfoes[$quote['goods_id']], $quote);
			$quote = array_merge($quoteInfoes[$quote['quote_id']], $quote);
		}

		return Tool_Util::returnJson(array('list' => $goodsQuoteList, 'total' => $total));
	}
}
