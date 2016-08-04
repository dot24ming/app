<?php 
class Dao_GoodsShipmentInfo extends Dao_Base {
	static $table = 'model_goods_shipment_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo($shipmentId) {
		$cond = array(  
            'shipment_id = ' => $shipmentId,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
		if (empty($ret)) {
			return array();
		}
        return $ret;
	}

	public function deleteItems($shipmentId, $delete) {
		if (empty($delete) || !is_array($delete)) {
			return true;
		}
		$condsStr = "(". implode(",", $delete) .")";
		$cond = array(
			"goods_id in $condsStr",
			"shipment_id =" => $shipmentId);
		$ret = $this->objDB->delete(self::$table, $cond);
		return $ret;
	}

	public function updateItems($shipmentId, $update) {
		if (empty($update) || !is_array($update)) {
			return true;
		}
		foreach ($update as $key => $item) {
			$cond = array(
				'shipment_id =' => $shipmentId,
				'goods_id =' => $key
			);
			$this->objDB->update(self::$table, $item, $cond);
			$error = $this->objDB->getError();
			$errno = $this->objDB->getErrno();
			if (!empty($error) || !empty($errno)) {
				return false;
			}
		}
		return true;
	}

	public function addItems($shipmentId, $add) {
		if (empty($add) || !is_array($add)) {
			return true;
		}
		foreach ($add as $item) {
			$item['shipment_id'] = $shipmentId;
			$item['time'] = date('Y-m-d H:i:s', time());
			$ret = $this->objDB->insert(self::$table, $item);
			if (!$ret) {
				return false;
			}
		}
		return true;
	}
	public function getShipmentInfoByTime($startTime, $endTime){
		$cond = array("time >=" => $startTime,
			"time <=" => $endTime);
		$ret = $this->objDB->select(self::$table, "*", $cond);
		return $ret;
	}
}
