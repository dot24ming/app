<?php 
class Dao_MaintenanceInfo extends Dao_Base {
	const TABLE = 'maintenance_info';
	static $table = '';

	public function __construct($storeId) {
		parent::__construct();		
		self::$table = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, self::TABLE);
	}
	
	
	public function getInfoByMaintenanceIds($maintenanceIds, $startday, $endday, $start, $end, &$total) {
		if (!is_array($maintenanceIds) || empty($maintenanceIds)) {
			return array();
		}
		foreach ($maintenanceIds as $maintenanceId) {
			$maintenanceId = intval($maintenanceId);
		}
		$idStr = implode(',', $maintenanceIds);
		$cond = array("maintenance_id in (". $idStr .")");

		if ($startday) {
			$cond[] = 'create_time >= ' . $startday;
		}
		if ($endday) {
			$cond[] = 'create_time <=' . $endday;
		}

		$options = array('SQL_CALC_FOUND_ROWS');
        if ($start != $end || $end != 0) { 
			$count = $end - $start + 1;
            $append = array(
                'order by maintenance_id  desc',
                "limit {$start}, {$count}",
            );      
        } else {
            $append = array(
                'order by maintenance_id desc',
            );      
        }       

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total);

		return $ret;
	}

	public function getInfoByCharge($charge, $relation, $startday, $endday, $start, $end, &$total) {
		if ($relation == 'lt') {
			$cond = array("all_charge <= $charge");
		} 

		if ($relation == 'gt') {
			$cond = array("all_charge >= $charge");
		}

		if ($startday) {
			$cond[] = 'create_time >= ' . $startday;
		}
		if ($endday) {
			$cond[] = 'create_time <=' . $endday;
		}

		$options = array('SQL_CALC_FOUND_ROWS');
        if ($start != $end || $end != 0) { 
			$count = $end - $start + 1;
            $append = array(
                'order by maintenance_id  desc',
                "limit {$start}, {$count}",
            );      
        } else {
            $append = array(
                'order by maintenance_id desc',
            );      
		}

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total);
		return $ret;
	}
	
}
