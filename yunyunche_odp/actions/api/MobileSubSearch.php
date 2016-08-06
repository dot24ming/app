<?php 
class Action_MobileSubSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$query = urldecode($arrInput['address']);
		$type = $arrInput['type'];
		$num = $arrInput['num'];

		$datas = array();
		if ($type == 0 || $type == "service"){
			$mobileServices = new Dao_MobileServices();
			$services = $mobileServices->getServiceInType3Name($query, $num);
			//return Tool_Util::returnJson($services);
			foreach ($services as $service){
				$data = array();
				$data['service_id'] = $service['id'];
				$data["type1_name"] = $service["type1_name"];
				$data["type2_name"] = $service["type2_name"];
				$data["type3_name"] = $service["type3_name"];
				$data["price"] = $service["price"];
				$data["type1_id"] = $service["type1_id"];
				$data["type2_id"] = $service["type2_id"];
				$data["type3_id"] = $service["type3_id"];
				array_push($datas, $data);
			}

		}
		else {
			$goodsInfoDao = new Dao_GoodsInfo();
			$goods = $goodsInfoDao->getInfosInName($query, $num);
			//return Tool_Util::returnJson($goods);
			foreach ($goods as $good){
				$data = array();
				$data['type3_name'] = $good['name'];
				$data['service_id'] = $good['goods_id'];
				$data['price'] = $good['instock_avg'];
				$data['instock_price'] = $good['instock_price'];
				$data['instock_count'] = $good['instock_count'];
				$data['type1_name'] = "汽修";
				$data['type2_name'] = "汽修商品";
				$data = array_merge($data, $good);
				array_push($datas, $data);
			}
		}
		return Tool_Util::returnJson($datas);
	}
}
