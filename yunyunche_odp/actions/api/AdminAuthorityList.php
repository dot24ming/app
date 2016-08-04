<?php 
class Action_AdminAuthorityList extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$adminPermissionDao = new Dao_AdminPermission(Tool_Const::$storeId);
		$infos = $adminPermissionDao->getAll();	
		
		$permissionInfoDao = new Dao_PermissionInfo();
		$permissionInfos = $permissionInfoDao->getAll();
		$permissions = array();
		if (!empty($permissionInfos) && is_array($permissionInfos)) {
			foreach ($permissionInfos as $permission) {
				$permissions[$permission['permission_id']] = Tool_Util::keyFormat($permission);
			}
		}	
		$list = array();	
		foreach ($infos as $info) {
			$list[$info['admin_id']][] = $permissions[$info['permission_id']];
		}	
		$userInfoDao = new Dao_AdminInfo();
		$userInfos = $userInfoDao->getAll(Tool_Const::$storeId);
		$adminList = array();
		foreach ($userInfos as $userInfo) {
			$adminList[] = array(
				'name' => $userInfo['name'],
				'date' => $userInfo['update_time'],
				'nickname' => $userInfo['nickname'],
				'department_id' => $userInfo['department_id'],
				'authorities' => $list[$userInfo['name']],
			);
		}
		return Tool_Util::returnJson(array('adminList' => $adminList));
	}
}
