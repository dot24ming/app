<?php 
class Dao_Package extends Dao_Base {
	static $table = "model_package";

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }

	public function getPackage(){
		$cond = array("package_type = " => 0);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageByCode($packageCode){
		$cond = array("package_code = " => $packageCode);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageByName($packageName){
		$cond = array("package_name = " => $packageName);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
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

	public function addPackage($package) {
		$ret = $this->objDB->insert(self::$table, $package);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		return $ret;
	}

	public function delPackage($packageCode){
		$cond = array(
				'package_code' => $packageCode
			);
		$ret = $this->objDB->delete(self::table, $cond);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		return $ret;
	}

	public function updatePackage($package, $packageCode){
		$cond = array(
				"package_code = " => $packageCode
			);
		$ret = $this->objDB->update(self::$table, $package, $cond);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		return $ret;
	}

	public function getInfoBySerNum($serNum) {
		$cond = array("ser_num = " => $serNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}
}


