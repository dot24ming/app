<?php 
class Action_BrandList extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$carSeriesDao = new Dao_CarSeries();
		$brands = $carSeriesDao->getAllBrandInfo();
		if (empty($brands)) {
			return Tool_Util::returnJson();
		}
		foreach ($brands as $brand) {
			$brandList[] = array(		
				'brandId' => $brand['brand'],
				'brandName' => $brand['brand'],
			);
		}
		return Tool_Util::returnJson(array('brandList' => $brandList));			
	}
}
