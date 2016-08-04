<?php 
class Action_GoodsStorageUpdate extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$storageId = $arrInput['storage_id'];

		if (empty($storageId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsStorageDao = new Dao_GoodsStorage();

		$goodsStorageDao->startTransaction();

		$goodsStorageDetail['storage_type'] = Tool_Util::filter($arrInput['instock_type']);
		$goodsStorageDetail['censor'] = Tool_Util::filter($arrInput['warehouse_auditor']);
		$goodsStorageDetail['department'] = Tool_Util::filter($arrInput['instock_department']);
		$goodsStorageDetail['storehouse_name'] = Tool_Util::filter($arrInput['instock_warehouse']);
		$goodsStorageDetail['total_num']	 = Tool_Util::filter($arrInput['sum_count']);
		$goodsStorageDetail['total_price']	 = Tool_Util::filter($arrInput['sum_price']);
		$goodsStorageDetail['purchaser'] = Tool_Util::filter($arrInput['purchaser']);

		$ret = $goodsStorageDao->update($storageId, $goodsStorageDetail);
		if (!$ret) {
			$goodsStorageDao->rollback();
			return Tool_Util::returnJson('', 1, '更新失败');
		}
		$goodsStorageInfoDao = new Dao_GoodsStorageInfo();
		$goodsInfos = $goodsStorageInfoDao->getInfo($storageId);

		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {
				$oldItems[$goodsInfo['goods_id']] = array(
					'goods_id' => $goodsInfo['goods_id'],
					'price' => $goodsInfo['price'],
					'number' => $goodsInfo['number'],
					'unit_price' => $goodsInfo['unit_price'],
					'supplier_id' => $goodsInfo['supplier_id'],
					'settlement' => $goodsInfo['settlement'],
					'remarks' => $goodsInfo['remarks'],
					'supplier_name' => $goodsInfo['supplier_name'],
				);
			}
		} else {
			$oldItems = array();
		}
		
		$items = json_decode($arrInput['instock_items'], true);
		if (!empty($items) && is_array($items)) {
			foreach ($items as $item) {
				$newItems[$item['goods_id']] = array(
					'goods_id' => $item['goods_id'],
					'price' => $item['sum'],
					'number' => $item['count'],
					'unit_price' => $item['unit_price'],
					'supplier_id' => $item['supplier_id'],
					'settlement' => $item['settlement'],
					'remarks' => $item['remarks'],
					'supplier_name' => $item['supplier_name'],
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
			$ret = $goodsStorageInfoDao->deleteItems($storageId, $delete);
			if (!$ret) {
				$goodsStorageInfoDao->rollback();
				return Tool_Util::returnJson('', 2, '更新失败');
			}
		}

		if (!empty($update) && is_array($update)) {
			foreach ($update as $item) {
				$updateCond[$item] = $newItems[$item];
			}
			$ret = $goodsStorageInfoDao->updateItems($storageId, $updateCond);
			if (!$ret) {
				$goodsStorageInfoDao->rollback();
				return Tool_Util::returnJson('', 3, '更新失败');
			}
		}
		if (!empty($add) && is_array($add)) {
			foreach ($add as $item) {
				$addCond[] = $newItems[$item];
			}
			$ret = $goodsStorageInfoDao->addItems($storageId, $addCond);
			if (!$ret) {
				$goodsStorageInfoDao->rollback();
				return Tool_Util::returnJson('', 4, '更新失败');
			}
		}
		$goodsStorageInfoDao->commit();
		return Tool_Util::returnJson();
	}
}
