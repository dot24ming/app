<?php 
class Dao_GoodsQuote extends Dao_Base {
	static $table = 'model_goods_quote';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getList($start, $end, &$total, $startDate, $endDate) {
		$append = array('order by quote_id desc');
		if ($start != $end && $end !== 0) {
			$count = $end - $start ;
			$append[] = "limit $start, $count";
		}
		$options = array('SQL_CALC_FOUND_ROWS');

		if (!empty($startDate) && !empty($endDate) && $startDate < $endDate) {
            $cond['time >'] = $startDate;
            $cond['time <'] = $endDate;
        } 

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 
		return $ret;
	}

	public function getInfos($quoteIds) {
		if (!empty($quoteIds) && is_array($quoteIds)) {
			foreach ($quoteIds as &$quoteId) {
				$quoteId = intval($quoteId);
			}   
			$quoteIdsStr = implode(',', $quoteIds);
		}  
		$cond = array("quote_id in ( $quoteIdsStr )");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}   

	public function getInfo($quoteId) {
		$cond = array('quote_id =' => $quoteId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
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
}
