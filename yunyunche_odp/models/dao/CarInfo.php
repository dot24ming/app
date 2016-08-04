<?php 
class Dao_CarInfo extends Dao_Base {
	const TABLE = 'car_info';
	static $table = 'model_car_info';

	public function __construct($storeId = null) {
		parent::__construct();		
		if ($storeId) {	
			//self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
	}
	
	public function getInfoBySeriesId($seriesId, $start, $end, &$total) {
		$cond = array(
			'series = ' => $seriesId,
		);

        $options = array('SQL_CALC_FOUND_ROWS');

        if ($start != $end || $end !== 0) {
			$count = $end - $start + 1; 
            $append = array(
                "limit {$start}, {$count}",
            );      
        }

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
        $ret = $this->queryWithCount($sql, $total); 

		return $ret;
	}
	
	public function getInfoByPlateNumber($plateNum) {
		$cond = array("plate_number = " => $plateNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
		
	}

	public function getInfoByOwnerId($ownerId) {
		$cond = array("owner_id = " => $ownerId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function setCarInfo($carInfo){
		$ret = $this->objDB->insert(self::$table, $carInfo);
		return $ret;
	}

	public function addCarInfo($userId, $plateNum, $frameNumber, $engineNumber) {
		$row = array(
			'owner_id' => $userId,
			'plate_number' => $plateNum,
			'frame_number' => $frameNumber,
			'engine_number' => $engineNumber,
		);
		$ret = $this->objDB->insert(self::$table, $row);
		return $ret;

	}
	
	public function updateCarInfo($carInfo, $plateNum){
		$cond = array("plate_number = " => $plateNum);
		$ret = $this->objDB->update(self::$table, $carInfo, $cond);
		return $ret;
	}

	public function updateCarInfoByPlateNumber($plateNum, $frameNumber, $engineNumber) {
		$cond = array('plate_number =' => $plateNum);
		$row = array(
			'plate_number' => $plateNum,
			'frame_number' => $frameNumber,
			'engine_number' => $engineNumber,
		);
		$ret = $this->objDB->update(self::$table, $row, $cond);

		$error = $this->objDB->getError();
		$errno = $this->objDB->getErrno();
		if (empty($error) && empty($errno)) {
			return true;
		} else {
			return false;
		}
	}
}
