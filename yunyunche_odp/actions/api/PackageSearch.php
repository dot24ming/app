<?php 
class Action_PackageSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		//var_dump($arrInput);
		//参数解析
		$packageCode = $arrInput['package_code'];
		$packageName = $arrInput['package_name'];

		if (!empty($packageCode)){
			//$packageCode = "XCK1";
			//return Tool_Util::returnJson($packageName);
			$packageDao = new Dao_Package(Tool_Const::$storeId);
			$package = $packageDao->getPackageByCode($packageCode);
			if (empty($package)){
				return Tool_Util::returnJson('', 1);
			}
			$packageInfoDao = new Dao_PackageInfo(Tool_Const::$storeId);
			$packageInfo = $packageInfoDao->getPackageInfo($packageCode);
			$package[0]['info'] = $packageInfo;

			return Tool_Util::returnJson($package);
		}
		else if (!empty($packageName)){
			//$packageName = "洗车套餐";
			$packageDao = new Dao_Package(Tool_Const::$storeId);
			$package = $packageDao->getPackageByName($packageName);
			if (empty($package)){
				return Tool_Util::returnJson('', 2);
			}
			$packageInfoDao = new Dao_PackageInfo(Tool_Const::$storeId);
			$packageInfo = $packageInfoDao->getPackageInfo($package[0]['package_code']);
			$package[0]['info'] = $packageInfo;
			return Tool_Util::returnJson($package);
		}
		else {
			$packageDao = new Dao_Package(Tool_Const::$storeId);
			$packages = $packageDao->getPackage();
			if (empty($packages)){
				return Tool_Util::returnJson('', 2);
			}
			$packageInfoDao = new Dao_PackageInfo(Tool_Const::$storeId);
			$packageinfos = array();
			foreach ($packages as $package){
				$packageInfo = $packageInfoDao->getPackageInfo($package['package_code']);
				$package['info'] = $packageInfo;
				array_push($packageinfos, $package);
			}

			return Tool_Util::returnJson($packageinfos);
		}
		return Tool_Util::returnJson('', 1);
	}
}
