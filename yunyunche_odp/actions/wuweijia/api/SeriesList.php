<?php 
class Action_SeriesList extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$name = strval($arrInput['brandId']);
		if (empty($name)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$carSeriesDao = new Dao_CarSeries();
		$series = $carSeriesDao->getSeriesInfoByName($name);
		$seriesList = array();
		foreach ($series as $item) {
			$seriesList[] = array('seriesId' => $item['series_id'], 'seriesName' => $item['series']);
		}

		return Tool_Util::returnJson(array("seriesList" => $seriesList));			
	}
}
