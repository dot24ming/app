<?php 
class Dao_GoodsTransfer extends Dao_Base {
	static $table = 'model_goods_transfer';

	public function __construct() {
    	parent::__construct();
    }
	
	public function getList($start, $end, &$total) {
		$append = array('order by transfer_id desc');
		if ($start != $end && $end !== 0) {
			$count = $end - $start ;
			$append[] = "limit $start, $count";
		}
		$options = array('SQL_CALC_FOUND_ROWS');
        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 
		return $ret;
	}


	public function getInfo($transferId) {
		$cond = array(  
            'transfer_id = ' => $transferId,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
        if (empty($ret)) {
            return array();
        }       
        return $ret[0];	
	}

	public function delete($transferId) {
		$cond = array('transfer_id =' => $transferId);
		$row = array('is_del' => 1);
		$ret = $this->objDB->update(self::$table, $row, $cond);
		return $ret;
	}

	public function update($transferId, $row) {
		$cond = array('transfer_id =' => $transferId);
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
