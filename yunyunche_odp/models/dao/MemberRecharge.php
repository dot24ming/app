<?php 
class Dao_MemberRecharge extends Dao_Base {
	const TABLE = 'member_recharge';
	static $table = 'model_member_recharge';

	public function __construct($storeId = null) {
		parent::__construct();
		if ($storeId) {
			//self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
    }

	public function getAll() {
		$ret = $this->objDB->select(self::$table, '*');	
		return $ret;
	}

	public function getInfoByTime($timeStart, $timeEnd){
		$cond = array("recharge_datetime >= " => $timeStart,
			"recharge_datetime <=" => $timeEnd);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getInfoBySettlement($settlement){
		$cond = array("recharge_way = " => $settlement);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}
}


