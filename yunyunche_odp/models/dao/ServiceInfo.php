<?php 
class Dao_ServiceInfo extends Dao_Base {
	const TABLE = 'service_info';
	static $table = '';

	public function __construct($storeId) {
		parent::__construct();		
		self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
	}
	
	public function addService($superId, $departmentId, $serviceName, $unit, $costPrice, $referencePrice, $guaranteePeriod, $pilgrimageTime) {
		$row = array(
			'name' => $serviceName,
			'unit' => $unit,
			'type' => $superId,
			'cost_price' => $costPrice,
			'reference_price' => $referencePrice,
			'guarantee_period' => $guaranteePeriod,
			'pilgrimage_time' => $pilgrimageTime,
			'department_id' => $departmentId,
		);

		$ret = $this->objDB->insert(self::$table, $row);	
		if ($ret == false) {
			$error = $this->objDB->error();
			$errno = $this->objDB->errno();
			return array('error' => $error, 'errno' => $errno);
		} else {
			return true;
		}
	}

	public function search($typeId, $departmentId, $name, $start, $end, &$total) {
		if ($typeId) {
			if (!is_array($typeId)) {
				$cond = array(
					'type =' => $typeId,
				);
			} else {
				foreach ($typeId as &$item) {
					$item = intval($item);
				}
				$typeStr = implode(',', $typeId);
				$cond = array("type in ($typeStr)");	
			}
		}
		if ($departmentId) {
			$cond['department_id ='] = $departmentId;
		}
		if ($name) {
			$cond[] = "name like '%$name%'";
		}  
			
        $options = array('SQL_CALC_FOUND_ROWS');
		if ($start != $end || $end !== 0) {
			$count = $end - $start + 1;	
            $append = array(
                'order by service_id desc',
                "limit {$start}, {$count}",
            );  
        } else {
            $append = array(
                'order by service_id desc',
            );
        }
        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total);	

		return $ret;
	}

	public function updateService($serviceId, $serviceTypeId, $departmentId, 
		$serviceName, $unit, $costPrice, $referencePrice, $guaranteePeriod, $pilgrimageTime) {
		$cond = array(
            'service_id = ' => intval($serviceId),
        );  
        $row = array();
        $row['type'] = intval($serviceTypeId);
		$row['department_id'] = intval($departmentId);
		$row['name'] = $serviceName;
		$row['unit'] = $unit;
		$row['cost_price'] = $costPrice;
		$row['reference_price'] = $referencePrice;
		$row['guarantee_period'] = $guaranteePeriod;
		$row['pilgrimage_time'] = $pilgrimageTime;

        $ret = $this->objDB->update(self::$table, $row, $cond);
		
        $error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();

        if (empty($error)) {
            return true;
        } else {
			return array('error' => $error, 'errno' => $errno);
        }   			
	}
	
	public function deleteService($serviceId) {
		$cond = array(
			'service_id =' => $serviceId,
		);
		$ret = $this->objDB->delete(self::$table, $cond);		
		return $ret;
	}
	
	public function getInfoById($serviceId) {
		$cond = array(	
			'service_id = ' => $serviceId,
		);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}
}
