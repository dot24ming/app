<?php 
class Dao_PackageCardInfo extends Dao_Base {
	static $table = 'model_package_card_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }


	public function getPackageCardInfoByCardId($packageCardId){
		$cond = array("package_card_id = " => $packageCardId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getPackageInfoByIdName($packageCardId, $itemName){
		$cond = array("package_card_id = " => $packageCardId,
			"item_name = " => $itemName);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];

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

	public function addPackageCardInfo($packageInfo) {
		$ret = $this->objDB->insert(self::$table, $packageInfo);
		//if ($ret === false) {
		//	$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		//}
		//return $this->objDB->getInsertID();
		return $ret;
	}

	public function delPackageInfo($packageCode){
		$cond = array(
				'package_code = ' => $packageCode,
			);
		$ret = $this->objDB->delete(self::$table, $cond);
		if ($ret === false) {
			$ret = array('errno' => $this->objDB->getErrno(), 'error' => $this->objDB->getError());
		}
		return $ret;
	}

	public function setPackageInfoCounts($packageCardId, $serviceName, $itemLeftCounts){
		$cond = array(
				'package_card_id = ' => $packageCardId,
				'item_name = ' => $serviceName,
			);
		$fileds = array(
			'item_left_counts' => $itemLeftCounts,
			);
		$ret = $this->objDB->update(self::$table, $fileds, $cond);
		return $ret;
	}

}


