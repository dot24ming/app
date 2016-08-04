<?php 
class Action_PackageCardSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		//var_dump($arrInput);
		//参数解析
		$userId = $arrInput['user_id'];
		$packageCardId = $arrInput['package_card_id'];
		$used = $arrInput['used'];
		if (!empty($userId)){

			//$packageCode = "XCK1";
			//$userId = "1";
			$packageCardDao = new Dao_PackageCard(Tool_Const::$storeId);
			$packageCards = $packageCardDao->getPackageCardByUid($userId);
			if (empty($packageCards)){
				return Tool_Util::returnJson($packageCards, 0, '');
			}
			$packageCardInfoDao = new Dao_PackageCardInfo(Tool_Const::$storeId);
			$packageCardInfos = array();
			foreach ($packageCards as $packageCard){
				$packageCardInfo = $packageCardInfoDao->getPackageCardInfoByCardId($packageCard['package_card_id']);
				if ($used == 1){
					//if ($packageCard['deadline'] > date('y-m-d h:i:s',time())) {
					//	continue;
					//}
					$infos = array();
					foreach ($packageCardInfo as $packageCardInf){
						if ($packageCardInf['deadline'] < date('y-m-d',time())){
							$packageCardInf['expired'] = 1;	
						}
						else {
							$packageCardInf['expired'] = 0;
						}
						if ($packageCardInf['item_left_counts'] > 0){
							array_push($infos, $packageCardInf);
						}
					}
					if (!empty($infos)){
						$packageCard['info'] = $infos;
						array_push($packageCardInfos, $packageCard);
					}
				}
				else{
					$packageCard['info'] = $packageCardInfo;
					array_push($packageCardInfos, $packageCard);
				}
			}

			return Tool_Util::returnJson($packageCardInfos);

		}
		else if(!empty($packageCardId)){
			//$packageCardId = "1";
			$packageCardDao = new Dao_PackageCard(Tool_Const::$storeId);
			$packageCard = $packageCardDao->getPackageCardById($packageCardId);
			if (empty($packageCard)){
				return Tool_Util::returnJson('', 1);
			}
			$packageCardInfoDao = new Dao_PackageCardInfo(Tool_Const::$storeId);
			$packageCardInfos = array();
			$packageCardInfo = $packageCardInfoDao->getPackageCardInfoByCardId($packageCard['package_card_id']);
			if (empty($packageCardInfo)){
				return Tool_Util::returnJson('', 1);
			}
			if ($packageCardInfo['deadline'] < date('y-m-d',time())){
				$packageCardInfo['expired'] = 1;	
			}
			else {
				$packageCardInfo['expired'] = 0;
			}
			$packageCard['info'] = $packageCardInfo;

			return Tool_Util::returnJson($packageCard);
			
		}
		return Tool_Util::returnJson('', 1);
	}
}
