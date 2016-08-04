<?php 
class Dao_CarSeries extends Dao_Base {
	const TABLE = 'car_series';

	public function __construct() {
    	parent::__construct();
    }
	
	public function getSeriesInfoByName($name) {
		$cond = array('brand =' => $name);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret;
	}
	
	public function getInfoBySeries($seriesId) {
		$cond = array('series_id = ' => intval($seriesId));
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret[0];
	}
	public function getAllBrandInfo() {
		$append = array('group by brand');
		$ret = $this->objDB->select(self::TABLE, 'brand', null, null, $append);	
		return $ret;
	}
	public function getAllInfo() {
		$append = array('group by series_id');
		$ret = $this->objDB->select(self::TABLE, '*', null, null, $append);	
		return $ret;
	}
}
