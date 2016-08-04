<?php 
class Dao_SupplierInfo extends Dao_Base {
	const TABLE = 'supplier_info';
	static $table = "supplier_info";

	public function __construct($storeId = null) {
		parent::__construct();
		self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo() {
        $ret = $this->objDB->select(self::$table, '*', $cond); 
        if (empty($ret)) {
            return array();
        }      
		
		if (!empty($ret) && is_array($ret)) {
			foreach ($ret as $supplier) {
				#$supplierInfos[$supplier['supplier_id']] = $supplier;
				$supplierInfos[] = $supplier;
			}
		}
        return $supplierInfos;
	}

	public function getSupplierInfo($id) {
		$cond = array('supplier_id =' => $id);
		$info = $this->objDB->select(self::$table, '*', $cond);
		return $info[0];

	}

	public function updateSupplierInfo($supplierId, $supplierName, $info, $address, $phone, $linkman) {
		$cond = array('supplier_id =' => $supplierId);
		$row = array(
			'supplier_name' => $supplierName,
			'info' => $info,
			'address' => $address,
			'phone' => $phone,
			'linkman' => $linkman,
		);
		$this->objDB->update(self::$table, $row, $cond);
		$error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();
		if (empty($error) && empty($errno)) {
			return true;
		} else {
			return false;
		}
	}

}
