<?php 
class Action_GoodsInventoryUpdate extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$inventoryId = $arrInput['inventory_id'];

		if (empty($inventoryId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsInventoryDao = new Dao_GoodsInventory();

		$goodsInventoryDao->startTransaction();

		$goodsInventoryDetail['storehouse_name'] = Tool_Util::filter($arrInput['storehouse_name']);
		$goodsInventoryDetail['censor'] = Tool_Util::filter($arrInput['warehouse_auditor']);
		$goodsInventoryDetail['storer'] = Tool_Util::filter($arrInput['storer']);
		$goodsInventoryDetail['remarks']	 = Tool_Util::filter($arrInput['remarks']);
		$goodsInventoryDetail['sum_instock_count']	 = $arrInput['sum_instock_count'];
		$goodsInventoryDetail['sum_price']	 = $arrInput['sum_price'];
		$goodsInventoryDetail['sum_inventory_count']	 = $arrInput['sum_inventory_count'];
		$goodsInventoryDetail['sum_count']	 = $arrInput['sum_count'];

		$ret = $goodsInventoryDao->update($inventoryId, $goodsInventoryDetail);
		if (!$ret) {
			$goodsInventoryDao->rollback();
			return Tool_Util::returnJson('', 1, '更新失败');
		}
		$goodsInventoryInfoDao = new Dao_GoodsInventoryInfo();
		$goodsInfos = $goodsInventoryInfoDao->getInfo($inventoryId);

		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {
				$oldItems[$goodsInfo['goods_id']] = $goodsInfo;
			}
		} else {
			$oldItems = array();
		}
		
		$items = json_decode($arrInput['inventory_items'], true);
		if (!empty($items) && is_array($items)) {
			foreach ($items as $item) {
				$newItems[$item['goods_id']] = $item;
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
			$ret = $goodsInventoryInfoDao->deleteItems($inventoryId, $delete);
			if (!$ret) {
				$goodsInventoryInfoDao->rollback();
				return Tool_Util::returnJson('', 2, '更新失败');
			}
		}

		if (!empty($update) && is_array($update)) {
			foreach ($update as $item) {
				$updateCond[$item] = $newItems[$item];
			}
			$ret = $goodsInventoryInfoDao->updateItems($inventoryId, $updateCond);
			if (!$ret) {
				$goodsInventoryInfoDao->rollback();
				return Tool_Util::returnJson('', 3, '更新失败');
			}
		}
		if (!empty($add) && is_array($add)) {
			foreach ($add as $item) {
				$addCond[] = $newItems[$item];
			}
			$ret = $goodsInventoryInfoDao->addItems($storageId, $addCond);
			if (!$ret) {
				$goodsInventoryInfoDao->rollback();
				return Tool_Util::returnJson('', 4, '更新失败');
			}
		}
		$goodsInventoryInfoDao->commit();
		return Tool_Util::returnJson();
	}
}
