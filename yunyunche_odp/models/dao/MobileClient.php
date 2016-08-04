<?php 
class Dao_MobileClient extends Dao_Base {
	const TABLE = 'permission_info';

	public function __construct() {
		parent::__construct();
	}
	
	public function getAll() {
		$cond = array('permission_id > 1000');
		$append = array('order by permission_id');
		$ret = $this->objDB->select(self::TABLE, '*', $cond, null, $append);
		return $ret;
	}	
}
