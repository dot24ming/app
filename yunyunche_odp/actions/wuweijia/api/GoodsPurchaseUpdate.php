<?php 
class Action_GoodsPurchaseUpdate extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$purchaseId = $arrInput['purchase_id'];

		if (empty($purchaseId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsPurchaseDao = new Dao_GoodsPurchase();
		$goodsPurchaseDao->startTransaction();
		$goodsPurchaseDetail['shipment_type'] = $arrInput['outstock_type'];
		$goodsPurchaseDetail['operator'] = $arrInput['delivery_man'];
		$goodsPurchaseDetail['censor'] = $arrInput['warehouse_auditor'];
		$goodsPurchaseDetail['remarks'] = $arrInput['remarks'];
		$goodsPurchaseDetail['total_num'] = $arrInput['sum_count'];
		$goodsPurchaseDetail['total_price'] = $arrInput['sum_price'];
		$goodsPurchaseDetail['total_net_price'] = $arrInput['sum_net_price'];
		$ret = $goodsShipmentDao->update($shipmentId, $goodsShipmentDetail);
		if (!$ret) {
			$goodsShipmentDao->rollback();
			return Tool_Util::returnJson('', 1, '更新失败');
		}
		$goodsShipmentInfoDao = new Dao_GoodsShipmentInfo();
		$goodsInfos = $goodsShipmentInfoDao->getInfo($shipmentId);
		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {
				$oldItems[$goodsInfo['goods_id']] = array(
					'goods_id' => $goodsInfo['goods_id'],
					'price' => $goodsInfo['price'],
					'number' => $goodsInfo['number'],
					'storage_price' => $goodsInfo['storage_price'],
					'shipment_price' => $goodsInfo['shipment_price'],
					'remarks' => $goodsInfo['remarks'],
				);
			}
		} else {
			$oldItems = array();
		}
		
		$items = json_decode($arrInput['outstock_items'], true);
		if (!empty($items) && is_array($items)) {
			foreach ($items as $item) {
				$newItems[$item['goods_id']] = array(
					'goods_id' => $item['goods_id'],
					'price' => $item['sum'],
					'number' => $item['count'],
					'storage_price' => $item['storage_price'],
					'shipment_price' => $item['shipment_price'],
					'remarks' => $item['remarks'],
				);
			}
		} else {
			$newItems = array();	
		}
		$oldGoodsIds = array_keys($oldItems);
		$newGoodsIds = array_keys($newItems);
		$delete = array_diff($oldGoodsIds, $newGoodsIds);
		$update = array_intersect($oldGoodsIds, $newGoodsIds);
		$add = array_diff($newGoodsIds, $oldGoodsIds);
		
		if (!empty($delete) && is_array($delete)) {
			$ret = $goodsShipmentInfoDao->deleteItems($shipmentId, $delete);
			if (!$ret) {
				$goodsShipmentInfoDao->rollback();
				return Tool_Util::returnJson('', 2, '更新失败');
			}
		}

		if (!empty($update) && is_array($update)) {
			foreach ($update as $item) {
				$updateCond[$item] = $newItems[$item];
			}
			$ret = $goodsShipmentInfoDao->updateItems($shipmentId, $updateCond);
			if (!$ret) {
				$goodsShipmentInfoDao->rollback();
				return Tool_Util::returnJson('', 3, '更新失败');
			}
		}
		if (!empty($add) && is_array($add)) {
			foreach ($add as $item) {
				$addCond[] = $newItems[$item];
			}
			$ret = $goodsShipmentInfoDao->addItems($shipmentId, $addCond);
			if (!$ret) {
				$goodsShipmentInfoDao->rollback();
				return Tool_Util::returnJson('', 4, '更新失败');
			}
		}
		$goodsShipmentInfoDao->commit();
		return Tool_Util::returnJson();
	}
}
