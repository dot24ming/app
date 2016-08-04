<?php 
class Action_ModifyService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$serviceId = intval($arrInput['serviceId']);
		$superId = intval($arrInput['superId']);
		$departmentId = intval($arrInput['departmentId']);
		$serviceName = Tool_Util::filter($arrInput['serviceName']);
		$unit = Tool_Util::filter($arrInput['unit']);   
        $costPrice = Tool_Util::filter($arrInput['costPrice']);
        $referencePrice = Tool_Util::filter($arrInput['referencePrice']);
        $guaranteePeriod = Tool_Util::filter($arrInput['guaranteePeriod']);
        $pilgrimageTime = Tool_Util::filter($arrInput['pilgrimageTime']);

		if (empty($serviceId) || empty($superId) || empty($departmentId) 
				|| empty($serviceName) || empty($unit)) {
            return Tool_Util::returnJson('', 1, '参数错误'); 
        }   

		$serviceInfoDao = new Dao_ServiceInfo(Tool_Const::$storeId);
			
		$ret = $serviceInfoDao->updateService($serviceId, $superId, $departmentId, $serviceName, 
			$unit, $costPrice, $referencePrice, $guaranteePeriod, $pilgrimageTime);	
		if ($ret === true) {
			return Tool_Util::returnJson();
		} else {
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
		}
	}
}
