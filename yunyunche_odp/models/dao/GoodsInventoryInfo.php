<?php 
class Dao_GoodsInventoryInfo extends Dao_Base {
	static $table = 'model_goods_inventory_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo($inventoryId) {
		$cond = array(  
            'inventory_id = ' => $inventoryId,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
		if (empty($ret)) {	
			return array();
		}
        return $ret;
	}

	public function deleteItems($inventoryId, $delete) {
        if (empty($delete) || !is_array($delete)) {
            return true;
        }   
        $condsStr = "(". implode(",", $delete) .")";
        $cond = array(
            "goods_id in $condsStr",
            "inventory_id =" => $inventoryId);
        $ret = $this->objDB->delete(self::$table, $cond);
        return $ret;
    }


	public function updateItems($inventoryId, $update) {
        if (empty($update) || !is_array($update)) {
            return true;
        }
        foreach ($update as $key => $item) {
            $cond = array(
                'inventory_id =' => $inventoryId,
                'goods_id =' => $key
            );
			$row = array(
				'due_num' => $item['due_num'] ,
				'sum_count' => $item['sum_count'],
				'sum_price' => $item['sum_price']
			);
            $ret = $this->objDB->update(self::$table, $row, $cond);
            $error = $this->objDB->getError();
            $errno = $this->objDB->getErrno();
            if (!empty($error) || !empty($errno)) {
                Bd_Log::notice($this->objDB->getError());
                return false;
            }
        }
        return true;
    }

	public function addItems($inventoryId, $add) {
        if (empty($add) || !is_array($add)) {
            return true;
        }
        foreach ($add as $item) {
            $item['inventory_id'] = $inventoryId;
            $item['time'] = date('Y-m-d H:i:s', time());
            $ret = $this->objDB->insert(self::$table, $item);
            if (!$ret) {
                Bd_Log::notice($this->objDB->getError());
                return false;
            }
        }
        return true;
    }
}
