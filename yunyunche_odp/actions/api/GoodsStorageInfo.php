<?php 
class Action_GoodsStorageInfo extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$storageId = $arrInput['storageId'];
		if (empty($storageId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsStorageDao = new Dao_GoodsStorage();
		$goodsStorageDetail = $goodsStorageDao->getInfo($storageId);

		//var_dump($goodsStorageDetail);
		$result['instock_type'] = $goodsStorageDetail['storage_type'];
		$result['instock_warehouse'] = $goodsStorageDetail['storehouse_name'];
		$result['instock_department'] = $goodsStorageDetail['department'];
		$result['warehouse_auditor'] = $goodsStorageDetail['censor'];
		$result['plate_number'] = $goodsStorageDetail['plate_number'];
		$result['purchaser'] = $goodsStorageDetail['purchaser'];
	
		$goodsStorageInfoDao = new Dao_GoodsStorageInfo();
		$goodsInfos = $goodsStorageInfoDao->getInfo($storageId);
		$items = array();
		$goodsIds = array();
		if (!empty($goodsInfos) && is_array($goodsInfos)) {
			foreach ($goodsInfos as $goodsInfo) {	
				$goodsIds[] = $goodsInfo['goods_id'];
				$items[] = array(
					'goods_id' => $goodsInfo['goods_id'],
					'sum' => $goodsInfo['price'],
					'name' => '',
					'ser_num' => '',
					'unit' => '',
					'spec' => '',
					'count' => $goodsInfo['number'],
					'unit_price' => $goodsInfo['unit_price'],
					'supplier_name' => '',
					'supplier_id' => $goodsInfo['supplier_id'],
					'settlement' => $goodsInfo['settlement'],
					'remarks' => $goodsInfo['remarks'],
					'sales_quote' => $goodsInfo['sales_quote'],
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
		$supplierInfoDao =  new Dao_SupplierInfo();       
		$suppliers = $supplierInfoDao->getInfo();
        foreach ($items as &$item) {
            $item['name'] = $goods[$item['goods_id']]['name'];
            $item['ser_num'] = $goods[$item['goods_id']]['ser_num'];
            $item['unit'] = $goods[$item['goods_id']]['unit'];
            $item['spec'] = $goods[$item['goods_id']]['spec'];
			$item['supplier_name'] = $suppliers[$item['supplier_id']]['name'];
        }      
		$result['items']  = json_encode($items);
		return Tool_Util::returnJson($result);
	}
}
