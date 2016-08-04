<?php 
class Action_AddSuper extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$superName = strval($arrInput['superName']);

		if (empty($superName)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$serviceTypeDao = new Dao_ServiceType();
		$ret = $serviceTypeDao->addSuper($superName);	
		if ($ret === 1) {
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
