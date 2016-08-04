<?php 
class Dao_MobileServices extends Dao_Base {
	const TABLE = 'mobile_services';
	static $table = 'model_mobile_services';

	public function __construct($storeId = null) {
		parent::__construct();		
		/*
		if ($storeId) {
			self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
		 */
	}

	public function getServicesInfo(){
		$ret = $this->objDB->select(self::$table, '*');
		return $ret;
	}

	public function getServiceByType1Id($type1Id){
		$cond = array("type1_id = " => $type1Id);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getServiceByType3Name($type3Name){
	
		$cond = array("type3_name = " => $type3Name);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getServiceInType3Name($type3Name, $num){

		$cond = array("type3_name like '%$type3Name%'");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return array_slice($ret, 0, $num);
	}

	public function getInfoByType3Id($type3Id){

		$cond = array("type3_id = " => $type3Id);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function setInfo($serviceInfo){
		//var_dump($serviceInfo);
		$service_item = array();
		$service_item['type1_name'] = $serviceInfo['type1_name'];
		$service_item['type2_name'] = $serviceInfo['type2_name'];
		$service_item['type3_name'] = $serviceInfo['type3_name'];

		$service_item['price'] = $serviceInfo['price'];
		$service_item['type'] = $serviceInfo['type'];

		//$ret = $this->objDB->insert(self::$table, $serviceInfo);
		$ret = $this->objDB->insert(self::$table, $service_item);
		return $this->objDB->getInsertID();
	}

	///end///
}
