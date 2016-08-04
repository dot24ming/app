<?php 
class Action_AddUserRequirement extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
		$input = $arrRequest['get'];

		$userId = Tool_Util::filter($input['userId']);
		$plateNumber = Tool_Util::filter($input['plateNumber']);
		$requirement = Tool_Util::filter($input['requirement']);

		$userRequirementDao = new Dao_UserRequirement();
		$ret = $userRequirementDao->add($userId, $plateNumber, $requirement, Tool_Const::$adminInfo['id']);
		if ($ret == true) {
			return Tool_Util::returnJson($ret);
		} else {
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
		}

	}
}
