<?php 
class Dao_GoodsQuoteDetail extends Dao_Base {
	const TABLE = 'model_goods_quote_detail';

	public function __construct() {
    	parent::__construct();
    }
	
	public function getList($start, $end, &$total) {
		$append = array('order by quote_id desc');
		if ($start != $end && $end !== 0) {
			$count = $end - $start ;
			$append[] = "limit $start, $count";
		}
		$options = array('SQL_CALC_FOUND_ROWS');
        $sql = $this->objSQLAssember->getSelect(self::TABLE, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 
		return $ret;
	}


	public function getInfo($goodsId, $quoteId) {
		$cond = array(  
            'goods_id =' => $goodsId,
			'quote_id =' => $quoteId,
        );      
        $ret = $this->objDB->select(self::TABLE, '*', $cond); 
        if (empty($ret)) {
            return array();
        }       
        return $ret;	
	}

	public function getInfos($goodsIds, $quoteId) {
		if (!is_array($goodsIds)) {
			return array();
		}
		foreach ($goodsIds as &$goodsId) {
			$goodsId = intval($goodsId);
		}
		$goodsIdsStr = implode(",", $goodsIds);
		$cond = array(
			'goods_id in ('. $goodsIdsStr .')',
			'quote_id =' => $quoteId
		);
		$quotes = $this->objDB->select(self::TABLE, '*', $cond);
		return $quotes;
	}

	public function delete($storageId) {
		$cond = array('storage_id =' => $storageId);
		$row = array('is_del' => 1);
		$ret = $this->objDB->update(self::TABLE, $row, $cond);
		return $ret;
	}

	public function update($storageId, $row) {
		$cond = array('storage_id =' => $storageId);
		$this->objDB->update(self::TABLE, $row, $cond);
		$error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();
		if (empty($error) && empty($errno)) {
			return true;
		} else {
			return false;
		}
	}
}
