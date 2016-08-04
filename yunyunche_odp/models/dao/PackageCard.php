<?php 
class Dao_PackageCard extends Dao_Base {
	static $table = "model_package_card";

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }

	public function getPackageCardByUid($userId){
		$cond = array("user_id = " => $userId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageCardByTime($timeStart, $timeEnd){
		$cond = array("active_date >=" => $timeStart,
			"active_date <=" => $timeEnd);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageCardBySettlement($settlement){
		$cond = array("settlement = " => $settlement);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageCardById($packageCardId){
		$cond = array("package_card_id = " => $packageCardId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getPackageCardByUCode($userId, $packageCode){
		$cond = array("user_id = " => $userId,
			"package_code = " => $packageCode);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackage($packageCode){
		$cond = array("package_code = " => $packageCode);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getPackages($packageCodes) {
		if (!empty($packageCodes) && is_array($packageCodes)) {
			foreach ($packageCodes as &$packageCode) {
				$packageCode = "'$packageCode'";
			}
			$packageCodesStr = implode(',', $packageCodes);
		} else {
			return array();
		}
		$cond = array("package_code in ( $packageCodesStr )");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackagesInName($packageName, $num){
		$cond = array("package_name like '%$packageName%'");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return array_slice($ret, 0, $num);
	}

	/**
	 * @param category cat1,cat2,cat3
	 * 
	 * 
	 */

	public function addPackageCard($packageCard) {
		$ret = $this->objDB->insert(self::$table, $packageCard);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		return $this->objDB->getInsertID();
	}

	public function delPackageCard($packageCardId){
		$cond = array(
				'package_card_id' => $packageCardId
			);
		$ret = $this->objDB->delete(self::table, $cond);
		if ($ret === false) {
			$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		}
		return $ret;
	}

	public function updatePackageCardById($package, $packageCodeId){
		$cond = array(
				"package_card_id = " => $packageCodeId
			);
		$ret = $this->objDB->update(self::$table, $package, $cond);
		if ($ret === false) {
			$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		}
		return $ret;
	}

	public function getInfoBySerNum($serNum) {
		$cond = array("ser_num = " => $serNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}
}


