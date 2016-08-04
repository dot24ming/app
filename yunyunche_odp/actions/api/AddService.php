<?php 
class Action_AddService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		
		$superId = intval($arrInput['superId']);
		$departmentId = intval($arrInput['departmentId']);
		$serviceName = strval($arrInput['serviceName']);
		$unit = Tool_Util::filter($arrInput['unit']);	
		$costPrice = $arrInput['costPrice'];
		$referencePrice = $arrInput['referencePrice'];
		$guaranteePeriod = $arrInput['guaranteePeriod'];
		$pilgrimageTime = $arrInput['pilgrimageTime'];

		if (empty($superId) || empty($departmentId) || empty($serviceName) || empty($unit) 
			|| empty($costPrice) || empty($referencePrice) ) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}
		$serviceInfoDao = new Dao_ServiceInfo(Tool_Const::$storeId);
		$ret = $serviceInfoDao->addService($superId, $departmentId, $serviceName, $unit, $costPrice, $referencePrice, $guaranteePeriod, $pilgrimageTime);	
		
		if ($ret === true) {
			return Tool_Util::returnJson(true);
		} else {
			return Tool_Util::returnJson('', $ret['errno'], $ret['error']);	
		}
	}
}
