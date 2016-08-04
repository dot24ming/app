<?php 
class Action_PackageAdd extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		//var_dump($arrInput);
		//参数解析
		$userId = $arrInput['user_id'];
		if (empty($userId)){
			$userId = -1;
		}
		$packageCode = $arrInput['package_code'];
		if (empty($packageCode)){
			$packageCode = "";
		}
		$packageName = $arrInput['package_name'];
		if (empty($packageName)){
			$packageName = "";
		}
		$packagePrice = $arrInput['package_price'];
		if (empty($packagePrice)){
			$packagePrice = 0.0;
		}
		$packageType = $arrInput['package_type'];
		if (empty($packageType)){
			$packageType = 0;
		}
		$packageExpiration = $arrInput['package_expiration'];
		if (empty($packageExpiration)){
			$packageExpiration = 0;
		}
		$integral = $arrInput['integral'];
		if (empty($integral)){
			$integral = "";
		}
		$packageStr = $arrInput['info'];
		if (empty($packageStr)){
			return Tool_Util::returnJson('', 1, "项目信息有误");
		}
		$package = json_decode($packageStr, true);
		if (empty($package)){
			return Tool_Util::returnJson('', 1, "项目信息有误");
		}
		
		$pack = array();
		$pack['package_code'] = $packageCode;
		$pack['package_name'] = $packageName;
		$pack['package_price'] = $packagePrice;
		$pack['package_type'] = $packageType;
		$pack['package_expiration'] = $packageExpiration;
		$pack['integral'] = $integral;

		$packageDao = new Dao_Package(Tool_Const::$storeId);
		$packageDao->startTransaction();
		$ret = $packageDao->addPackage($pack);
		if (!$ret){
			$packageDao->rollback();
			return Tool_Util::returnJson('', 1, "记录套餐信息失败");
		}
		$packageInfoDao = new Dao_PackageInfo(Tool_Const::$storeId);
		foreach ($package as $packageInfo){
			$packInfo = array();
			$packInfo['package_code'] = $packageCode;
			$packInfo['item_name'] = $packageInfo['item_name'];
			$packInfo['item_type'] = $packageInfo['item_type'];
			$packInfo['item_counts'] = $packageInfo['item_counts'];
			$packInfo['item_cost'] = $packageInfo['item_cost'];
			$ret = $packageInfoDao->addPackageInfo($packInfo);
			if (!$ret){
				$packageDao->rollback();
				return Tool_Util::returnJson('', 1, "记录套餐详情失败");
			}
		}

		if (!$ret){
			//todo:如果插入详情不成功,需要删除刚插入的信息
			$packageDao->rollback();
			return Tool_Util::returnJson('', 1, "入库失败");
		}
		else {
			$packageDao->commit();
			return Tool_Util::returnJson('', 0, "套餐入库成功");
		}
	}
}
