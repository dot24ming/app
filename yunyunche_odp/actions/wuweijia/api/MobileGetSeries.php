<?php 
class Action_MobileGetSeries extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$carSeriesDao = new Dao_CarSeries();
		$carSeries = $carSeriesDao->getAllInfo();
		if (empty($carSeries)){
			return Tool_Util::returnJson();
		}
		$brands = array();
		foreach ($carSeries as $carSeri){
			$brand = $carSeri['brand'];
			$series = $carSeri['series'];
			$series_id = $carSeri['series_id'];
			if (array_key_exists($brand, $brands)){
				$brands[$brand][$series] = $series_id;
				//array_push($brands[$brand], array($series=>$series_id));
			}
			else {
				$brands[$brand] = array($series=>$series_id);
				//array_push($brands[$brand], array($series=>$series_id));
			}
		}	
		$preCarSer = array();
		foreach ($carSeries as $carSeri){
			$pre = $carSeri['pre'];
			$brand = $carSeri['brand'];
			if (array_key_exists($pre, $preCarSer)){
				//continue;
				if (array_key_exists($brand, $preCarSeri[$pre])){
					continue;
				}
				else{
					$preCarSer[$pre][$brand] = $brands[$brand];
				}
			}
			else{
				$preCarSer[$pre] = array();
				$preCarSer[$pre][$brand] = $brands[$brand];
			}
		}
		return Tool_Util::returnJson(array('info' => $preCarSer));
	}
}
