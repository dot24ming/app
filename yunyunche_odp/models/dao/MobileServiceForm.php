<?php 
class Dao_MobileServiceForm extends Dao_Base {
	const TABLE = 'mobile_service_form';
	static $table = 'model_mobile_service_form';

	public function __construct($storeId = null) {
		parent::__construct();		
		if ($storeId) {
			//self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
	}

	public function setServiceForm($serviceForm){
		$ret = $this->objDB->insert(self::$table, $serviceForm);
		return $this->objDB->getInsertID();
	}

	public function updateServiceForm($serviceForm, $formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->update(self::$table, $serviceForm, $cond);
	}

	public function getServiceFormByPlateNum($plateNum){
		$cond = array("plate_number = " => $plateNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getServiceFormByTime($plateNum, $timeStart, $timeEnd){
		if (!empty($plateNum) && !empty($timeStart) && !empty($timeEnd)){
			$cond = array("plate_number = " => $plateNum,
						"time >= " => $timeStart,
						"time <= " => $timeEnd,
				);
		}
		else if (!empty($timeStart) && !empty($timeEnd)){
			$cond = array("time >= " => $timeStart,
						"time <= " => $timeEnd,
				);
		}
		else if (!empty($plateNum)){
			$cond = array("plate_number = " => $plateNum,
				);
		}
		else{
			$cond = null;	
		}
		$append = array(
					"order by time desc"
		);
		$ret = $this->objDB->select(self::$table, '*', $cond, null, $append);
		return $ret;
	}

	public function getServiceFormBySTime($status, $timeStart, $timeEnd){
		if (!empty($status) && !empty($timeStart) && !empty($timeEnd)){
			$cond = array("status = " => $status,
						"time >= " => $timeStart,
						"time <= " => $timeEnd,
				);
		}
		else if (!empty($timeStart) && !empty($timeEnd)){
			$cond = array("time >= " => $timeStart,
						"time <= " => $timeEnd,
				);
		}
		else if (!empty($status)){
			$cond = array("status = " => $status,
				);
		}
		else{
			$cond = null;	
		}
		$append = array(
					"order by time desc"
		);
		$ret = $this->objDB->select(self::$table, '*', $cond, null, $append);
		return $ret;
	}

	// 结算时间
	public function getServiceFormBySSTime($status, $timeStart, $timeEnd){
		if (!empty($status) && !empty($timeStart) && !empty($timeEnd)){
			$cond = array("status = " => $status,
						"settlement_time >= " => $timeStart,
						"settlement_time <= " => $timeEnd,
				);
		}
		else if (!empty($timeStart) && !empty($timeEnd)){
			$cond = array("settlement_time >= " => $timeStart,
						"settlement_time<= " => $timeEnd,
				);
		}
		else if (!empty($status)){
			$cond = array("status = " => $status,
				);
		}
		else{
			$cond = null;	
		}
		$append = array(
					"order by settlement_time desc"
		);
		$ret = $this->objDB->select(self::$table, '*', $cond, null, $append);
		return $ret;
	}

	public function getServiceFormByFormIds($formIds){
		
		$cond = array(
					"form_id in (".$formIds.")",
				);
		$append = array(
					"order by time desc"
		);
		//$options = array("time => " => $timeStart)
		$ret = $this->objDB->select(self::$table, '*', $cond, null, $append);
		return $ret;
	}

	public function getServiceFormById($formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getServiceFormByUID($userId, $status){
		$cond = array("user_id =" => $userId,
			"status =" => $status);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getServiceFormByStatus($Status){
		$cond = array("status = " => $Status);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getServiceFormBySettlement($settlement){
		$cond = array("settlement = " => $settlement);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function deleteServiceForm($formId){
		$cond = array("form_id = " => $formId);
		$ret = $this->objDB->delete(self::$table, $cond);
		return $ret;
	}
	//end

}
