<?php 
class Dao_Department extends Dao_Base {
	const TABLE = 'department';
	static $table = '';

	public function __construct($storeId) {
		parent::__construct();		
		self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
	}
	
	public function getDepartmentInfo() {
		$ret = $this->objDB->select(self::$table, '*');
		return $ret;
	}	

	public function addDepartment($name) {
		$row = array(	
			'name' => $name
		);
		$ret = $this->objDB->insert(self::$table, $row);
		if ($ret) {
			return true;
		} else {
			$error = $this->objDB->error();
			$errno = $this->objDB->errno();
			return array('error' => $error, 'errno' => $errno);
		}
	}
}
