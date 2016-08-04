<?php 
class Dao_WarehouseInfo extends Dao_Base {
	const TABLE = 'warehouse_info';

	public function __construct() {
		parent::__construct();		
	}
	

	public function getInfo() {
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret;
	}

	public function addWarehouse($name) {
		$row = array(
			'warehouse_name' => $name,
		);
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

