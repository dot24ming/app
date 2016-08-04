<?php 
class Dao_SampleServiceForm extends Dao_Base {
	static $table = "model_sample_service_form";

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }

	public function getSampleServiceFormByName($sampleServiceName){
		$cond = array("sample_service_name = " => $sampleServiceName);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getSampleServiceForm(){
		$ret = $this->objDB->select(self::$table, '*');
		return $ret;
	}

}


