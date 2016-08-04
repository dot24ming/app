<?php 
class Action_ReportBalanceSearchLoad extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		//var_dump($arrInput);
		//参数解析
		$timeStart = $arrInput['time_start'];
		if (empty($timeStart)){
			$timeStart = date('y-m',time());
			//$timeStart = "0";
		}
		$timeEnd = $arrInput['time_end'];
		if (empty($timeEnd)){
			$timeEnd = date('y-m-d h:i:s',time());
		}
		$start = $arrInput['start'];
		if (empty($start)){
			$start = 0;
		}
		$end = $arrInput['end'];
		if (empty($end)){
			$end = 100;
		}
		$settlement = $arrInput['settlement'];
		if (empty($settlement)){
			$settlement = "";
		}
		$titlesStr = $arrInput['titles'];
		if (empty($titlesStr)){
			$titlesStr = '';
		}
		$titles = json_decode($titlesStr, true);

		//return Tool_Util::returnJson($titles);
		$balances = array();

		$ret = self::packageCardCharge($timeStart, $timeEnd, $balances);
		$ret = self::memberRecharge($balances);
		$ret = self::goodsStorageCharge($balances);

		foreach ($balances as $balance){
			$dates[] = $balance['time'];
		}

		array_multisort($dates, SORT_DESC, $balances);

		//return Tool_Util::returnJson($balances);
		$fileName = Tool_Util::createExcel('财务报表', $titles, $balances);
		return Tool_Util::returnFile($fileName, '财务报表.xlsx');
	}

	public static function packageCardCharge($timeStart, $timeEnd, &$balances){
		$packageCardDao = new Dao_PackageCard(Tool_Const::$storeId);
		$packageCards = $packageCardDao->getPackageCardByTime($timeStart, $timeEnd);
		if (empty($packageCards)){
			return -1;
			//return Tool_Util::returnJson('', $packageCards, "无查询结果");
		}
		$carInfoDao = new Dao_CarInfo();
		$index = 0;
		$totalIncome = 0.0;
		$totalPay = 0.0;
		foreach ($packageCards as $packageCard){
			$balance = array();
			$userId = $packageCard['user_id'];
			$carInfo = $carInfoDao->getInfoByOwnerId($userId);
			if (empty($carInfo)){
				$balance['pay_man'] = "未知";
			}
			else{
				$balance['pay_man'] = $carInfo['plate_number'];
			}
			$balance['time'] = $packageCard['active_date'];
			$balance['balance_type'] = "收入";
			$balance['item_name'] = "套餐卡购买";
			$balance['price'] = $packageCard['package_cost'];
			$totalIncome = $totalIncome + $packageCard['package_cost'];
			$balance['count'] = 1;
			//结算方式
			if ($settlement != "" && $settlement != $packageCard['settlement']){
				continue;
			}
			$balance['settlement'] = $packageCard['settlement'];
			$balance['saleman'] = $packageCard['saleman'];
			array_push($balances, $balance);
		}
		return 0;
	}

	public static function memberRecharge($timeStart, $timeEnd, &$balances){
		$memberRechargeDao = new Dao_MemberRecharge();
		$userInfoDao = new Dao_UserInfo();
		$memberRechargeInfos = $memberRechargeDao->getInfoByTime($timeStart, $timeEnd);
		foreach($memberRechargeInfos as $memberRechargeInfo){
			//$index = $index + 1;
			//if ($index < $start+1 || $index > $end){
			//	continue;
			//}
			$userId = $memberRechargeInfo['uid'];
			$userInfo = $userInfoDao->getUserInfoByUserId($userId);
			if (empty($userInfo)){
				continue;
			}
			$balance = array();
			$carInfo = $carInfoDao->getInfoByOwnerId($userId);
			if (empty($carInfo)){
				$balance['pay_man'] = "未知";
			}
			else{
				$balance['pay_man'] = $carInfo['plate_number'];
			}
			$balance['time'] = $memberRechargeInfo['recharge_datetime'];
			$balance['balance_type'] = "收入";
			$balance['item_name'] = "会员卡充值";
			$balance['price'] = $memberRechargeInfo['recharge_amount'];
			$totalIncome = $totalIncome + $memberRechargeInfo['recharge_amount'];
			$balance['count'] = 1;
			//结算方式
			if ($settlement != "" && $settlement != $memberRechargeInfo['recharge_way']){
				continue;
			}
			$balance['settlement'] = $memberRechargeInfo['recharge_way'];
			$balance['saleman'] = $memberRechargeInfo['saleman'];
			array_push($balances, $balance);
		}
		return 0;
	}

	public static function goodsStorageCharge($timeStart, $timeEnd, &$balances){
		//仓库支出
		//
		/*
		$goodsStorageInfoDao = new Dao_GoodsStorageInfo();
		$goodsStorageDao = new Dao_GoodsStorage();
		$goodsInfoDao = new Dao_GoodsInfo();
		$supplierInfoDao = new Dao_SupplierInfo();
		$goodsStorageInfos = $goodsStorageInfoDao->getStorageInfoByTime($timeStart, $timeEnd);
		foreach($goodsStorageInfos as $goodsStorageInfo){
			$balance = array();	
			$goodsId = $goodsStorageInfo['goods_id'];
			$goodsInfo = $goodsInfoDao->getInfo($goodsId);
			if (empty($goodsInfo)){
				continue;	
			}
			$supplierId = $goodsStorageInfo['supplier_id'];
			$storageId = $goodsStorageInfo['storage_id'];
			$supplierInfo = $supplierInfoDao->getSupplierInfo($supplierId);
			if (empty($supplierInfo)){
				$balance['supplier'] = "未知";
			}
			else{
				$balance['supplier'] = $supplierInfo['supplier_name'];
			}
			$goodsStorage = $goodsStorageDao->getInfo($storageId);
			$balance['time'] = $goodsStorage['time'];
			$balance['balance_type'] = "支出";
			$balance['item_name'] = $goodsInfo['name'];
			$balance['price'] = $goodsStorageInfo['price'];
			$totalPay = $totalPay + $goodsStorageInfo['price'];
			$balance['count'] = $goodsStorageInfo['number'];
			//结算方式
			if ($settlement != "" && $settlement != $goodsStorageInfo['settlement']){
				continue;
			}
			$balance['pay_settlement'] = $goodsStorageInfo['settlement'];
			$balance['purchaser'] = $goodsStorage['purchaser'];
			array_push($balances, $balance);
		}
		*/
		$goodsStorageInfoDao = new Dao_GoodsStorageInfo();
		$goodsShipmentInfoDao = new Dao_GoodsShipmentInfo();
		$goodsStorageDao = new Dao_GoodsStorage();
		$goodsShipmentDao = new Dao_GoodsShipment();
		$goodsInfoDao = new Dao_GoodsInfo();
		$supplierInfoDao = new Dao_SupplierInfo();
		//$goodsStorageInfos = $goodsStorageInfoDao->getStorageInfoByTime($timeStart, $timeEnd);
		$goodsShipmentInfos = $goodsShipmentInfoDao->getShipmentInfoByTime($timeStart, $timeEnd);
		foreach($goodsShipmentInfos as $goodsShipmentInfo){
			$balance = array();	
			$goodsId = $goodsShipmentInfo['goods_id'];
			$goodsInfo = $goodsInfoDao->getInfo($goodsId);
			if (empty($goodsInfo)){
				continue;	
			}
			//$supplierId = $goodsStorageInfo['supplier_id'];
			$shipmentId = $goodsStorageInfo['shipment_id'];
			//$supplierInfo = $supplierInfoDao->getSupplierInfo($supplierId);
			//if (empty($supplierInfo)){
			//	$balance['supplier'] = "未知";
			//}
			//else{
			//	$balance['supplier'] = $supplierInfo['supplier_name'];
			//}
			//$goodsStorage = $goodsStorageDao->getInfo($storageId);
			$goodsShipment = $goodsShipmentDao->getInfo($shipmentId);
			//$balance['time'] = $goodsStorage['time'];
			$balance['time'] = $goodsShipment['time'];
			$balance['balance_type'] = "支出";
			$balance['item_name'] = $goodsInfo['name'];
			//$balance['price'] = $goodsStorageInfo['price'];
			$balance['price'] = $goodsShipmentInfo['price'];
			//$totalPay = $totalPay + $goodsStorageInfo['price'];
			$totalPay = $totalPay + $goodsShipmentInfo['price'];
			//$balance['count'] = $goodsStorageInfo['number'];
			$balance['count'] = $goodsShipmentInfo['number'];
			//结算方式
			//if ($settlement != "" && $settlement != $goodsStorageInfo['settlement']){
			//	continue;
			//}
			//$balance['pay_settlement'] = $goodsStorageInfo['settlement'];
			$balance['pay_settlement'] = '未知';
			//$balance['purchaser'] = $goodsStorage['purchaser'];
			$balance['purchaser'] = $goodsShipment['author'];
			array_push($balances, $balance);
		}
		return 0;
	}
}
