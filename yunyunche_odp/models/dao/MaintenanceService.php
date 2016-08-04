<?php 
class Dao_MaintenanceService extends Dao_Base {
	const TABLE = 'maintenance_service';
	static $table = '';

   	public function __construct() {
    	parent::__construct();
		self::$table = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, self::TABLE);
    }

	public function getMaintenanceIdByServiceId($serviceId) {
		$cond = array('service_id = ' => intval($serviceId));
		$list = $this->objDB->select(self::$table, '*', $cond);
		if (!is_array($list) || empty($list)) {
			return array();
		}
	
		$maintenanceIds = array();
		foreach ($list as $item) {
			$maintenanceIds[] = $item['maintenance_id'];
		}
		return $maintenanceIds;
	}	
}




