<?php 
class Dao_AdminInfo extends Dao_Base {
	const TABLE = 'admin_info';

	public function __construct() {
		parent::__construct();	
	}
	

	public function addAdminDetail($username, $password, $storeId, $nickname, $department_id) {
		$row = array(
			'name' => $username,
			'passwd' => md5($password),
			'update_time' => date('Y-m-d H:i:s', time()),
			'store_id' => $storeId,
			'nickname' => $nickname,
			'department_id' => $department_id,
		);
		$ret = $this->objDB->insert(self::TABLE, $row);	
		if ($ret == false) {
			$error = $this->objDB->error();
			$errno = $this->objDB->errno();
			return array('error' => $error, 'errno' => $errno);
		} else {
			return true;
		}
	}

	
	public function addAdmin($username, $password, $storeId) {
		$row = array(
			'name' => $username,
			'passwd' => md5($password),
			'update_time' => date('Y-m-d H:i:s', time()),
			'store_id' => $storeId,
		);
		$ret = $this->objDB->insert(self::TABLE, $row);	
		if ($ret == false) {
			$error = $this->objDB->error();
			$errno = $this->objDB->errno();
			return array('error' => $error, 'errno' => $errno);
		} else {
			return true;
		}
	}

	// 根据用户名获取用户信息
	public function getAdminByUsername($username) {
		$cond = array("name =" => $username);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret[0];
	}	

	public function getAll($storeId) {
		$cond = array('store_id =' => $storeId);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret;
	}

	public function deleteAdmin($name) {
		$cond = array('name =' => $name);
		$ret = $this->objDB->delete(self::TABLE, $cond);

		$p_cond = array('admin_id =' => $name);
		$ret = $this->objDB->delete('56_admin_permission',$p_cond);
		return $ret;
	}

	public function getAdmin($cookie) {
		$cond = array('cookie =' => $cookie);
		$ret = $this->objDB->select(self::TABLE, '*', $cond);
		return $ret[0];
	}

	public function updateCookie($username, $cookie, $expire) {
		$cond = array('name =' => $username);
		$row = array(
			'expire' => $expire,
			'cookie' => $cookie,
		);
		$ret = $this->objDB->update(self::TABLE, $row, $cond);
		return $ret;
	}

	public function deleteCookie($username) {
		$cond = array('name =' => $username);
		$row = array(
			'expire' => '',
			'cookie' => '',
		);
		$ret = $this->objDB->update(self::TABLE, $row, $cond);
		return $ret;
		
	}
}
