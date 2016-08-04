<?php 
class Action_DeleteAdmin extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$username = strval($arrInput['username']);

		$adminInfoDao = new Dao_AdminInfo();
		$ret = $adminInfoDao->deleteAdmin($username);	
		return Tool_Util::returnJson();
	}
}
