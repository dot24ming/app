<?php 
class Action_CheckAdminName extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$username = Tool_Util::filter($arrInput['adminname']);
		
		$adminInfoDao = new Dao_AdminInfo();
		$ret = $adminInfoDao->getAdminByUsername($username);
		if ($ret) {
			return Tool_Util::returnJson(true);
		} else {
			return Tool_Util::returnJson(false);	
		}
	}
}
