<?php 
class Dao_GlobalUserInfo extends Dao_Base {
	const TABLE = 'global_user_info';

	public function __construct($storeId) {
		parent::__construct();
		//self::$table = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, self::TABLE);
	}

	public function getUserInfoByOpenid($openid){
		$cond = array("openid = " => $openid);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret[0];
	}

	public function addUserInfo($storeId, $openId, $userId){
		$row = array('store_id' => $storeId, 'openid' => $openId, 'user_id' => $userId);
		$ret = $this->objDB->insert(self::TABLE, $row);

		return $ret;
	}

	public function updateUserInfo($arr, $userId){
		$cond = array("user_id = " => $userId);
		$ret = $this->objDB->update('model_user_info', $arr, $cond);
		return $ret;
	}

}


