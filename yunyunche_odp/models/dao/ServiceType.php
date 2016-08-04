<?php 
class Dao_ServiceType extends Dao_Base {
	const TABLE = 'service_type';
	static $table = '';

	public function __construct() {
		parent::__construct();		
		self::$table = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, self::TABLE);
	}
	
	public function getParent() {
		$cond = array('super_type_id = ' => 0);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getChild($superId, $start, $end, &$total) {
		$cond = array('super_type_id = ' => $superId);
		$count = $end - $start;
		$options = array('SQL_CALC_FOUND_ROWS');
		
        if ($start != $end || $end != 0) {
            $append = array(
                "limit {$start}, {$count}",
            );      
        }

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 
	
		return $ret;
	}
	
	public function getAllType() {
		$cond = array('super_type_id != ' => 0);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}	

	public function addMap($name, $superId) {
		$row = array(
			'name' => $name,
			'super_type_id' => $superId,
		);
		$ret = $this->objDB->insert(self::$table, $row);	
		if ($ret === false) {
            $error = $this->objDB->error();
            $errno = $this->objDB->errno();
            return array('error' => $error, 'errno' => $errno);
        } else {
            return true;
        }  		
	}

	public function getTypeInfo($typeIds) {
		foreach ($typeIds as &$typeId) {
			$typeId = intval($typeId);
		}
		$strTypeIds = implode(',', $typeIds);
		
		$cond = array('id in (' . $strTypeIds . ')');
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getInfoById($typeId) {
		if ($typeId) {
			$cond = array('id =' => $typeId);
		}
		$info = $this->objDB->select(self::$table, '*', $cond);
		return $info[0];
	}

	// 删除服务类型
	public function deleteType($typeId) {
		$cond = array(
			'id = ' => $typeId,
			'super_type_id =' => 0
		);
		$ret = $this->objDB->delete(self::$table, $cond);
		return $ret;
	}

	public function addSuper($name) {
		$row = array(
			'name' => $name,
			'super_type_id' => 0);
		$ret = $this->objDB->insert(self::$table, $row);
		if (!$ret) {
            $error = $this->objDB->error();
            $errno = $this->objDB->errno();
            return array('error' => $error, 'errno' => $errno);
		}
		return $ret;
	}		
}  
