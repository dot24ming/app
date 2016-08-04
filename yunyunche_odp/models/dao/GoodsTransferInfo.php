<?php 
class Dao_GoodsTransferInfo extends Dao_Base {
	static $table = 'model_goods_transfer_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo($transferId) {
		$cond = array(  
            'transfer_id = ' => $transferId,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
		if (empty($ret)) {
			return array();
		}
        return $ret;
	}

	
	public function deleteItems($transferId, $delete) {
        if (empty($delete) || !is_array($delete)) {
            return true;
        }   
        $condsStr = "(". implode(",", $delete) .")";
        $cond = array(
            "goods_id in $condsStr",
            "transfer_id =" => $transferId);
        $ret = $this->objDB->delete(self::$table, $cond);
        return $ret;
    }  



	public function updateItems($transferId, $update) {
        if (empty($update) || !is_array($update)) {
            return true;
        }
        foreach ($update as $key => $item) {
            $cond = array(
                'transfer_id =' => $transferId,
                'goods_id =' => $key
			);
			$row = array(
				'remarks' => $item['remarks'],
				'sum_count' => $item['sum_count'],
				'sum_price' => $item['sum_price'],
				'unit_price' => $item['unit_price'],
			);
			$ret = $this->objDB->update(self::$table, $row, $cond);
			$error = $this->objDB->getError();
			$errno = $this->objDB->getErrno();
            if (!empty($error) || !empty($errno)) {
                return false;
            }
        }
        return true;
    }
	
	public function addItems($storageId, $add) {
        if (empty($add) || !is_array($add)) {
            return true;
        }
        foreach ($add as $item) {
            $item['transfer_id'] = $transferId;
            $item['time'] = date('Y-m-d H:i:s', time());
            $ret = $this->objDB->insert(self::$table, $item);
            if (!$ret) {
                return false;
            }
        }
        return true;
    } 
}
