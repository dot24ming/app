<?php 
class Dao_AdminPermission extends Dao_Base {
	const TABLE = 'admin_permission';
	static $table = '';

	public function __construct($storeId) {
		parent::__construct();
		self::$table = sprintf(self::TABLE_FORMAT, $storeId, self::TABLE);
	}
	public function clearUserAuthority($username) {
	}

	public function addBatch($username, $permissionIds, $isClear = 0) {
		$row = array('admin_id' => $username);
		
		if ($isClear == 1) {
			$cond = array('admin_id = ' => $username);
			$ret = $this->objDB->delete(self::$table, $cond);
			/*if ($ret == false) {
				return array('error' => '修改失败', 'errno' => 1);
			}*/
		}
		foreach ($permissionIds as $permissionId) {
			$row['permission_id'] = $permissionId;
			$ret = $this->objDB->insert(self::$table, $row);	
			if (!$ret) {
				$error = $this->objDB->error();
				$errno = $this->objDB->errno();
				return array('error' => $error, 'errno' => $errno);
			}
		}
		return true;
	}

	public function getAll() {
		$ret = $this->objDB->select(self::$table, '*');	
		return $ret;
	}

	public function getByName($username) {
		$cond = array('admin_id =' => $username);
		$ret = $this->objDB->select(self::$table, '*', $cond);
		return $ret;
	}
}
