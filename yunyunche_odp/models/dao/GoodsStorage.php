<?php 
class Dao_GoodsStorage extends Dao_Base {
	static $table = 'model_goods_storage';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getList($start, $end, &$total, $startDate, $endDate) {
		$append = array('order by storage_id desc');
		if ($start != $end && $end !== 0) {
			$count = $end - $start ;
			$append[] = "limit $start, $count";
		}
		$options = array('SQL_CALC_FOUND_ROWS');
		$cond = array('is_del =' => 0);

		if (!empty($startDate) && !empty($endDate) && $startDate < $endDate) {
            $cond['time >'] = $startDate;
            $cond['time <'] = $endDate;
        } 

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 

		//var_dump($ret);

		return $ret;
	}

	public function getListByTime($start, $end, &$total, $startDate, $endDate) {
		$append = array('order by storage_id desc');
		$options = array('SQL_CALC_FOUND_ROWS');
		$cond = array('is_del =' => 0);

		if (!empty($startDate) && !empty($endDate) && $startDate < $endDate) {
            $cond['time >'] = $startDate;
            $cond['time <'] = $endDate;
        } 

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 

		//var_dump($ret);

		return $ret;
	}

	public function getInfo($storageId) {
		$cond = array(  
            'storage_id = ' => $storageId,
			'is_del =' => 0,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
        if (empty($ret)) {
            return array();
        }       
        return $ret[0];	
	}

	public function delete($storageId) {
		$cond = array('storage_id =' => $storageId);
		$row = array('is_del' => 1);
		$ret = $this->objDB->update(self::$table, $row, $cond);
		return $ret;
	}

	public function update($storageId, $row) {
		$cond = array('storage_id =' => $storageId);
		$this->objDB->update(self::$table, $row, $cond);
		$error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();
		if (empty($error) && empty($errno)) {
			return true;
		} else {
			return false;
		}
	}

	public function getStorageByTime($startTime, $endTime){
		$cond = array("time >= " => $startTime,
			"time <=" => $endTime);
		$ret = $this->objDB->select(self::$table, "*", $cond);

		return $ret;
	}

	public function getStorageInfoBySettlement($settlement){
		$cond = array("shipment_type = " => $settlement);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}
}
