<?php 
class Dao_StoreInfo extends Dao_Base {
	const TABLE = 'store_info';

	public function __construct() {
		parent::__construct();		
	}
	
	public function usableStore() {
		$cond = array('status =' => 1);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret;
	}

	public function getStoreInfo($storeIdArr) {
		if (empty($storeIdArr) || !is_array($storeIdArr)) {
			return array();
		}

		foreach ($storeIdArr as &$storeId) {
			$storeId = intval($storeId);
		}
		$storeIdStr = implode(',', $storeIdArr);
		$cond = array("store_id in ($storeIdStr)");
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret;
	}

	public function getStoreInfoById($storeId){
		$cond = array("store_id = " => $storeId);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret[0];
	}

	public function addStore($storeName, $name, $telephone, $email, $addrProvince, $addrCity, $addrDistrict, $adminUsername) {
		$row = array(
			'name' => $storeName,
			'phone' => $telephone,
			'contact_user' => $name,
			'email' => $email, 
			'addr_province' => $addrProvince,
			'addr_city' => $addrCity,
			'addr_district' => $addrDistrict,
			'admin_account' => $adminUsername);
		$ret = $this->objDB->insert(self::TABLE, $row);
		if ($ret == false) {	
			$error = $this->objDB->error();
            $errno = $this->objDB->errno();
            return array('error' => $error, 'errno' => $errno);
		} else {
			return $this->objDB->getInsertID();
		}
	}

}

