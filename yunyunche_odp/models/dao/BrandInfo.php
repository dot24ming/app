<?php 
class Dao_BrandInfo extends Dao_Base {
	const TABLE = 'brand_info';

	public function __construct() {
    	parent::__construct();
    }
	
	public function getAllBrandInfo() {
		$ret = $this->objDB->select(self::TABLE, '*');
		return $ret;
	}
}
