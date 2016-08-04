<?php 
class Dao_UserInfo extends Dao_Base {
	const TABLE = 'user_info';
	static $table = 'model_user_info';

	public function __construct($storeId = null) {
		parent::__construct();
		if ($storeId) {
			//self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
		}
    }

	public function getUserInfoByUserIds($userIds, $start, $end, &$total) {
		if (!is_array($userIds) || empty($userIds))	{
			return array();
		}
		foreach ($userIds as &$userId) {
			$userId = intval($userId);
		}

		$userIdsStr = implode(',', $userIds);	
		$cond = array("user_id in (" . $userIdsStr . ")");
        $options = array('SQL_CALC_FOUND_ROWS');
		if ($start == $end && $end === 0) {
            $append = array(
                'order by user_id desc',
                "limit {$start}, {$count}",
            ); 
        } else {
            $append = array(
                'order by user_id desc',
            );
        }

        $sql = $this->objSQLAssember->getSelect(self::$table, '*', $cond, $options, $append);
		$ret = $this->queryWithCount($sql, $total);
		return $ret;
	}

	public function getAll() {
		$ret = $this->objDB->select(self::$table, '*');	
		return $ret;
	}

	public function getUserInfoByPhone($phoneNum){
		$cond = array("phone_num = " => $phoneNum);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getUserInfoByUserId($userId){
		$cond = array("user_id = " => $userId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function getUserInfoByOpenId($openId){
		$cond = array("wechat_num = " => $openId);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret[0];
	}

	public function setUserInfo($arr){
		$ret = $this->objDB->insert(self::$table, $arr);
		return $this->objDB->getInsertID();
	}

	public function updateUserInfo($arr, $userId){
		$cond = array("user_id = " => $userId);
		$ret = $this->objDB->update(self::$table, $arr, $cond);
		return $ret;
	}

	public function updateOpenId($arr, $userId){
		$cond = array("user_id = " => $userId);
		$ret = $this->objDB->update(self::$table, $arr, $cond);
		return $ret;
	}

	public function updateUserInfoByOpenId($arr, $openId){
		$cond = array("wechat_num = " => $openId);
		$ret = $this->objDB->update(self::$table, $arr, $cond);
		return $ret;
	}

}


