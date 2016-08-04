<?php 
class Action_AddServiceType extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$superId = intval($arrInput['superId']);
		$types = explode(",", strval($arrInput['type']));
		if (empty($superId) || empty($types)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$serviceTypeDao = new Dao_ServiceType();
		if (!empty($types) && is_array($types))	 {
			foreach ($types as $type) {
				if (empty($type)) {
					continue;
				}
				$ret = $serviceTypeDao->addMap($type, $superId);	
				if ($ret !== true) {
					if ($ret['errno'] == 1062) {
						return Tool_Util::returnJson('', $ret['errno'], "项目名已存在");
					} else{
						return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
					}
				}
			}
			return Tool_Util::returnJson(true);
		}
		return Tool_Util::returnJson();
	}
}
