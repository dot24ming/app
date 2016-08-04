<?php 
class Action_GoodsTransferUpdate extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$transferId = $arrInput['transfer_id'];
		if (empty($transferId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsTransferDao = new Dao_GoodsTransfer();

		$goodsTransferDao->startTransaction();

		$goodsTransferDetail['out_warehouse_name'] = Tool_Util::filter($arrInput['out_warehouse_name']);
		$goodsTransferDetail['in_warehouse_name'] = Tool_Util::filter($arrInput['in_warehouse_name']);
		$goodsTransferDetail['warehouse_auditor'] = Tool_Util::filter($arrInput['warehouse_auditor']);
		$goodsTransferDetail['transfer_man']	 = Tool_Util::filter($arrInput['transfer_man']);
		$goodsTransferDetail['remarks']	 = Tool_Util::filter($arrInput['remarks']);
		$goodsTransferDetail['sum_count']	 = $arrInput['sum_count'];

		$ret = $goodsTransferDao->update($transferId, $goodsTransferDetail);
		if (!$ret) {
			$goodsTransferDao->rollback();
			return Tool_Util::returnJson('', 1, '更新失败');
		}
		$goodsTransferInfoDao = new Dao_GoodsTransferInfo();
		$goodsInfos = $goodsTransferInfoDao->getInfo($transferId);

		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {
				$oldItems[$goodsInfo['goods_id']] = $goodsInfo;
			}
		} else {
			$oldItems = array();
		}
		
		$items = json_decode($arrInput['transfer_items'], true);
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
			$ret = $goodsTransferInfoDao->deleteItems($transferId, $delete);
			if (!$ret) {
				$goodsTransferInfoDao->rollback();
				return Tool_Util::returnJson('', 2, '更新失败');
			}
		}

		if (!empty($update) && is_array($update)) {
			foreach ($update as $item) {
				$updateCond[$item] = $newItems[$item];
			}
			$ret = $goodsTransferInfoDao->updateItems($transferId, $updateCond);
			if (!$ret) {
				$goodsTransferInfoDao->rollback();
				return Tool_Util::returnJson('', 3, '更新失败');
			}
		}
		if (!empty($add) && is_array($add)) {
			foreach ($add as $item) {
				$addCond[] = $newItems[$item];
			}
			$ret = $goodsTransferInfoDao->addItems($transferId, $addCond);
			if (!$ret) {
				$goodsTransferInfoDao->rollback();
				return Tool_Util::returnJson('', 4, '更新失败');
			}
		}
		$goodsTransferInfoDao->commit();
		return Tool_Util::returnJson();
	}
}
