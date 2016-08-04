<?php 
class Action_UpdateService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$serviceId = intval($arrInput['serviceId']);
		$serviceTypeId = intval($arrInput['serviceTypeId']);
		$departmentid = intval($arrInput['departmentId']);
		$serviceName = $arrInput['serviceName'];

		if (empty($serviceId) || empty($serviceTypeId) || empty($departmentId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}		

		$serviceInfoDao = new Dao_ServiceInfo();
			
		$ret = $serviceInfoDao->updateService($serviceId, $serviceTypeId, $departmentId, $serviceName);	
		if ($ret === true) {
			return Tool_Util::returnJson();
		} else {
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
		}
	}
}
