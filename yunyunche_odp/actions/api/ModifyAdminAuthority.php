<?php 
class Action_ModifyAdminAuthority extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('',1 , '非管理员，无权限');
		}
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		
		$username = Tool_Util::filter($arrInput['username']);
		$password = Tool_Util::filter($arrInput['password']);

		$permissionId = $arrInput['permissionId'];

		$adminInfoDao = new Dao_AdminInfo();

		$admin = $adminInfoDao->getAdminByUsername($username);
		if (empty($admin)) {
			return Tool_Util::returnJson('', 1, '用户不存在');
		}

		if (!is_array($permissionId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$adminPermissionDao = new Dao_AdminPermission(Tool_Const::$storeId);
		$adminPermissionDao->startTransaction();
		$ret = $adminPermissionDao->addBatch($username, $permissionId, 1);
		if ($ret === true) {
			$adminPermissionDao->commit();
			return Tool_Util::returnJson();				
		} else {
			$adminPermissionDao->rollback();
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
		}
	}
}
