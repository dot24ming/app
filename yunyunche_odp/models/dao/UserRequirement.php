<?php 
class Dao_UserRequirement extends Dao_Base {
	const TABLE = 'user_requirement';
	static $table = '';

	const STATUS_INIT = 1;


	public function __construct($storeId) {
		parent::__construct();		
		self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
	}
	
	public function add($userId, $plateNumber, $requirement, $operatorId) {
		$row = array(
			'user_id' => $userId,
			'plate_number' => $plateNumber,
			'requirement' => $requirement,
			'operator_id' => $operatorId,
			'status' => self::STATUS_INIT,
			'create_time' => date('Y-m-d H:i:s', time()),
		);

		$ret = $this->objDB->insert(self::TABLE, $row);	
		if ($ret == false) {
			$error = $this->objDB->error();
			$errno = $this->objDB->errno();
			return array('error' => $error, 'errno' => $errno);
		} else {
			return true;
		}
	}
}
