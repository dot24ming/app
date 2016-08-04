<?php 
class Dao_GoodsInventory extends Dao_Base {
	static $table = 'model_goods_inventory';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getList($start, $end, &$total, $startDate, $endDate) {
		$append  = array('order by inventory_id desc');
		if ($start != $end && $end !== 0) {
			$count = $end - $start ;
			//$append = array("limit $start, $count");
			$append[] = "limit $start, $count";
		}

		$cond = array('is_del =' => 0);
		
		if (!empty($startDate) && !empty($endDate) && $startDate < $endDate) {
			$cond['time >'] = $startDate;
			$cond['time <'] = $endDate;
		}

		//$append[]  = 'order by inventory_id desc';
		$options = array('SQL_CALC_FOUND_ROWS');
        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 
		return $ret;
	}

	public function getInfo($inventoryId) {
		$cond = array(  
			'inventory_id = ' => $inventoryId,
			'is_del = ' => 0
        );    
        $ret = $this->objDB->select(self::$table, '*', $cond); 
        if (empty($ret)) {
            return array();
        }
        return $ret[0];
	} 

	public function update($inventoryId, $row) {
		$cond = array('inventory_id =' => $inventoryId);
        $this->objDB->update(self::$table, $row, $cond);
        $error = $this->objDB->getError();
        $errno = $this->objDB->getErrno();
        if (empty($error) && empty($errno)) {
            return true;
        } else {
            return false;
        }   
	}

	public function delete($inventoryId) {
		$cond = array('inventory_id = ' => $inventoryId);
		$row = array('is_del' => 1);
		$this->objDB->update(self::$table, $row, $cond);
		$error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();
		if (empty($error) && empty($errno)) {
			return true;
		} else {
			return false;
		}
	}
}
