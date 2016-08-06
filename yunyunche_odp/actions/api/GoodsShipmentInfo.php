<?php 
class Action_GoodsShipmentInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$shipmentId = $arrInput['shipmentId'];

		if (empty($shipmentId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$goodsShipmentDao = new Dao_GoodsShipment();
		$shipmentDetail = $goodsShipmentDao->getInfo($shipmentId);
		$result['outstock_type'] = $shipmentDetail['shipment_type'];
		$result['outstock_warehouse'] = $shipmentDetail['storehouse_name'];
		$result['outstock_department'] = $shipmentDetail['department'];
		$result['delivery_man'] = $shipmentDetail['operator'];
		$result['warehouse_auditor'] = $shipmentDetail['author'];
		$result['remarks'] = $shipmentDetail['remarks'];
		$result['sum_count'] = $shipmentDetail['total_num'];
		$result['sum_price'] = $shipmentDetail['total_price'];
		$result['sum_net_price'] = $shipmentDetail['total_net_price'];
		$result['plate_number'] = $shipmentDetail['plate_number'];

		$goodsShipmentInfoDao = new Dao_GoodsShipmentInfo();
		$info = $goodsShipmentInfoDao->getInfo($shipmentId);

		$items = array();
		$goodsIds = array();
		if (!empty($info) && is_array($info)) {
			foreach ($info as $item) {
				$goodsIds[] = $item['goods_id'];
				$items[] = array(
					'goods_id' => $item['goods_id'],
					'sum' => $item['price'],
					'name' => '',
					'count' => $item['number'],
					'storage_price' => $item['storage_price'],
					'shipment_price' => $item['shipment_price'],
					'ser_num' => '',
					'unit' => '',
					'spec' => '',
					'remarks' => $item['remarks'], 
				);
			}
		}
		$goodsInfoDao  = new Dao_GoodsInfo();
		$goodsInfos = $goodsInfoDao->getInfos($goodsIds);
		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {
				$goods[$goodsInfo['goods_id']] = $goodsInfo;
			}
		}
		foreach ($items as &$item) {
			$item['name'] = $goods[$item['goods_id']]['name'];
			$item['ser_num'] = $goods[$item['goods_id']]['ser_num'];
			$item['unit'] = $goods[$item['goods_id']]['unit'];
			$item['spec'] = $goods[$item['goods_id']]['spec'];
		}
		$result['outstock_items'] = json_encode($items);
		return Tool_Util::returnJson($result);
	}
}
