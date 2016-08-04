<?php 
class Dao_GoodsInfo extends Dao_Base {
	static $table = 'model_goods_info';

	public function __construct() {
		parent::__construct();
		//self::$table = Tool_Util::getStoreTable(self::$table);
    }


	public function getInfo($goodsId){
		$cond = array("goods_id = " => $goodsId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getInfos($goodsIds) {
		if (!empty($goodsIds) && is_array($goodsIds)) {
			foreach ($goodsIds as &$goodsId) {
				$goodsId = "'$goodsId'";
			}
			$goodsIdsStr = implode(',', $goodsIds);
		} else {
			return array();
		}
		$cond = array("goods_id in ( $goodsIdsStr )");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}

	public function getInfosInName($goodsName, $num){
		$cond = array("name like '%$goodsName%'");
		$ret = $this->objDB->select(self::$table, '*', $cond);
		//return $ret;
		return array_slice($ret, 0, $num);
	}

	/**
	 * @param category cat1,cat2,cat3
	 * 
	 * 
	 */
	public function getList($category = null) {
		if (empty($category)) {
			$ret = $this->objDB->select(self::$table, '*');
		} else {
			$categoryArr = explode(',', $category);
			if (!is_array($categoryArr)) {
				return array();
			}
			foreach ($categoryArr as &$category) {
				$category = "'$category'";
			}
			$categoryStr = implode(',', $categoryArr);
			$cond = array('category in ('. $categoryStr .')');
			$ret = $this->objDB->select(self::$table, '*', $cond);
		}
		return $ret;
	}
	public function getListPart($category = null, $start, $end) {
		$goods_count = 0;
		$offset = $end - $start;
		$limit_str = 'limit '.$start.','.$offset;
		if (empty($category)) {
			$ret = $this->objDB->select(self::$table, '*',NULL,NULL,$limit_str);
			$goods_count = $this->objDB->selectCount(self::$table);
		} else {
			$categoryArr = explode(',', $category);
			if (!is_array($categoryArr)) {
				return array();
			}
			foreach ($categoryArr as &$category) {
				$category = "'$category'";
			}
			$categoryStr = implode(',', $categoryArr);
			$cond = array('category in ('. $categoryStr .')');

			//var_dump($limit_str);
			//$ret = $this->objDB->select(self::$table, '*', $cond);
			$ret = $this->objDB->select(self::$table, '*', $cond,NULL,$limit_str);
			$goods_count = $this->objDB->selectCount(self::$table, $cond);
		}
		$ret_data = array();
		$ret_data['size'] = $goods_count;
		$ret_data['list'] = $ret;
		return $ret_data;
	}

	public function addGoodsInfo($name, $category, $info, $spec, $unit, $serNum, $superId) {
		$cond = array("ser_num = " => $serNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);

		$row = array(
			'name' => $name, 
			'category' => $category, 
			'info' => $info,
			'spec' => $spec,
			'unit' => $unit,
			'ser_num' => $serNum,
			'super_id' => $superId,
		);
		$ret = $this->objDB->insert(self::$table, $row);
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


