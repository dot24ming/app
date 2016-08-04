<?php 
class Action_AddStore extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$storeName = Tool_Util::filter($arrInput['store_name']);	
		$name = Tool_Util::filter($arrInput['name']);
		$telephone = Tool_Util::filter($arrInput['telphone']);
		$email = Tool_Util::filter($arrInput['email']);
		$addrProvince = Tool_Util::filter($arrInput['addr_province']);
		$addrCity = Tool_Util::filter($arrInput['addr_city']);
		$addrDistrict = Tool_Util::filter($arrInput['addr_district']);

		$adminName = Tool_Util::filter($arrInput['admin_name']);
		$password = Tool_Util::filter($arrInput['password']);

		if (empty($storeName) || empty($name) || empty($telephone) 
			|| empty($addrProvince) || empty($addrCity) 
			|| empty($addrDistrict) || empty($adminName)) {
			return Tool_Util::returnJson('', '参数错误', 1);
		}
		$storeInfoDao = new Dao_StoreInfo();
		$storeInfoDao->startTransaction();
	
		$newStoreId = $storeInfoDao->addStore($storeName, $name, $telephone, 
			$email, $addrProvince, $addrCity, $addrDistrict, $adminName);
		if (is_array($newStoreId) || $newStoreId <= 0) {
			$storeInfoDao->rollback();
			return Tool_Util::returnJson('', $ret['errno'], '新增失败');
		}
		$adminInfoDao = new Dao_AdminInfo();
		$ret = $adminInfoDao->addAdmin($adminName, $password, $newStoreId);
		if ($ret !== true) {
			$storeInfoDao->rollback();
			return Tool_Util::returnJson('', $ret['errno'], '新增失败');
		}

		// 添加门店相关表
  		$new_tables = array(
            'admin_permission',
            'after_sale_push',
            'car_illegal_info',
            'car_info',
            'department',
            'insurance_push',
            'licence_push',
            'maintenance_info',
            'maintenance_service',
            'push_history',
            'service_detail',
            'service_info',
            'service_type',
            'traffic_peccancy_push',
            'user_feedback',
            'user_info',
			'verification_push',
			'goods_info',
			'goods_inventory',
			'goods_inventory_info',
			'goods_purchase',
			'goods_purchase_info',
			'goods_quote',
			'goods_quote_info',
			'goods_shipment',
			'goods_shipment_info',
			'goods_storage',
			'goods_storage_info',
			'goods_transfer',
			'goods_transfer_info',
			'supplier_info',
        );
		foreach($new_tables as $table_name) {
        	$sql = 'CREATE TABLE '.$newStoreId.'_'.$table_name.' LIKE model_'.$table_name.' ;';
            try {
            	$ret = $storeInfoDao->query($sql);
            } catch (Exception $e) {
				$storeInfoDao->rollback();
            	Bd_Log::warning($e->getMessage());
				return Tool_Util::returnJson('', 1, '新增失败');
            }
		}	
		$adminPermission = new Dao_AdminPermission($newStoreId);
		$permissions = array_keys(Tool_Authority::$pageAuth);
		foreach ($permissions as $key => &$permission) {
			if ($permission < 1000) {
				unset($permissions[$key]);
			}
		}
		$ret = $adminPermission->addBatch($adminName, $permissions);

		$storeInfoDao->commit();
		return Tool_Util::returnJson('');
	}
}
