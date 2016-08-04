<?php 
class Dao_GoodsPurchaseInfo extends Dao_Base {
	static $table = 'model_goods_purchase_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo($purchaseId) {
		$cond = array(  
            'purchase_id = ' => $purchaseId,
        );      
		$ret = $this->objDB->select(self::$table, '*', $cond); 
		if (empty($ret)) {
			return array();
		}
        return $ret;
	}
}
