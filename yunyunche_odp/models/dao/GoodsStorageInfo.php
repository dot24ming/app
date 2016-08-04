<?php 
class Dao_GoodsStorageInfo extends Dao_Base {
	static $table = 'model_goods_storage_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }
	
	public function getInfo($storageId) {
		$cond = array(  
            'storage_id = ' => $storageId,
        );      
        $ret = $this->objDB->select(self::$table, '*', $cond); 
		if (empty($ret)) {
			return array();
		}
        return $ret;
	}

	
	public function deleteItems($storageId, $delete) {
        if (empty($delete) || !is_array($delete)) {
            return true;
        }   
        $condsStr = "(". implode(",", $delete) .")";
        $cond = array(
            "goods_id in $condsStr",
            "storage_id =" => $storageId);
        $ret = $this->objDB->delete(self::$table, $cond);
        return $ret;
    }  



	public function updateItems($storageId, $update) {
        if (empty($update) || !is_array($update)) {
            return true;
        }
        foreach ($update as $key => $item) {
            $cond = array(
                'storage_id =' => $storageId,
                'goods_id =' => $key
            );
			$ret = $this->objDB->update(self::$table, $item, $cond);
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
            $item['storage_id'] = $storageId;
            $item['time'] = date('Y-m-d H:i:s', time());
            $ret = $this->objDB->insert(self::$table, $item);
			if (!$ret) {
				Bd_Log::notice("add $storageId:". json_encode($add));
				Bd_Log::notice($this->objDB->getError());
                return false;
            }
        }
        return true;
    } 

	public function getStorageInfoByTime($startTime, $endTime){
		$cond = array("time >=" => $startTime,
			"time <=" => $endTime);
		$ret = $this->objDB->select(self::$table, "*", $cond);
		//$ret = $this->objDB->select(self::$table, "*");
		return $ret;
	}

}
