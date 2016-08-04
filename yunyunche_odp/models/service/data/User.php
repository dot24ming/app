<?php 
class Service_Data_User {
	// 检查是否是管理员
	static public function isAdmin() {
		$admin = $_COOKIE['yunyunche_user'];
		$adminInfoDao = new Dao_AdminInfo(Tool_Const::$storeId);
		$info = $adminInfoDao->getAdmin($admin);
		$time = time();

		if ($time > $info['expire']) {
			$adminInfoDao->deleteCookie($admin);
			return false;
		}
		if ($admin == $info['cookie']) {
			Tool_Const::$username = $info['name'];
			Tool_Const::$adminInfo = $info;
			return true;
		} else {
			return false;
		}
	}

	// 设置cookie	
	public static function setCookie($username) {
		$adminInfoDao = new Dao_AdminInfo(Tool_Const::$storeId);
		$adminInfo = $adminInfoDao->getAdminByUsername($username);
		if ($adminInfo && $adminInfo['expire'] > time()) {
			$cookie = $adminInfo['cookie'];
		} else {
			$cookie = md5($username . time());	
		}
    	$expire = time() + 86400;
        setcookie('yunyunche_user', $cookie, $expire, '/');

        $adminInfoDao->updateCookie($username, $cookie, $expire);
	}

	public static function deleteCookie() {
        setcookie('yunyunche_user', '', time() - 1, '/');
	}

	static function getAdminId() {
		return 1;
	}
}
