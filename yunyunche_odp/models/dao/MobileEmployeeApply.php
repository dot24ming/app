<?php 
class Dao_MobileEmployeeApply extends Dao_Base {
	const TABLE = 'mobile_employee_apply';
	static $table = 'model_mobile_employee_apply';

	public function __construct($storeId = null) {
		parent::__construct();		
		if ($storeId) {
			self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
	}
	
	public function getEmployeeApplyInfoByOpenId($openId){
		$cond = array("wechat = " => $openId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}
	/*
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
		$ret = $this->objDB->insert(self::$table, $serviceInfo);
		return $this->objDB->getInsertID();
	}
	*/

	///end///
}
