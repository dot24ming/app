<?php 
class Dao_PackageInfo extends Dao_Base {
	static $table = 'model_package_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }


	public function getPackageInfo($packageCode){
		$cond = array("package_code = " => $packageCode);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageInfos($packageCodes) {
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

	public function addPackageInfo($packageInfo) {
		$ret = $this->objDB->insert(self::$table, $packageInfo);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		return $ret;
	}

	public function delPackageInfo($packageCode){
		$cond = array(
				'package_code' => $packageCode
			);
		$ret = $this->objDB->delete(self::table, $cond);
		if ($ret === false) {
			$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		}
		return $ret;
	}

}


