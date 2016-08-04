<?php 
class Dao_MobileServiceFormInfo extends Dao_Base {
	const TABLE = 'mobile_service_form_info';
	static $table = 'model_mobile_service_form_info';

	public function __construct($storeId = null) {
		parent::__construct();		
		if ($storeId) {
			//self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
	}

	public function setServiceFormInfo($serviceFormInfo){
		$ret = $this->objDB->insert(self::$table, $serviceFormInfo);
		return $ret;
	}

	public function updateServiceFormInfo($serviceFormInfo, $formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->update(self::$table, $serviceFormInfo, $cond);
		return $ret;
	}

	public function getServiceFormInfo($formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->select(self::$table, "*", $cond);
		return $ret;
	}

	public function deleteServiceFormInfo($formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->delete(self::$table, $cond);
		return $ret;
	}

	////////end////
}
