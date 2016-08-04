<?php 
class Action_AddDepartment extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$name = htmlspecialchars($arrInput['departmentName']);
		if (empty($name)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$departmentDao = new Dao_Department(Tool_Const::$storeId);
		$ret = $departmentDao->addDepartment($name);

		if ($ret === true) {
			return Tool_Util::returnJson();			
		} else {
			if ($ret['errno'] == 1062) {
				return Tool_Util::returnJson('', $ret['errno'], "名称已存在");
			} else{
				return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
			}
		}
	}
}
