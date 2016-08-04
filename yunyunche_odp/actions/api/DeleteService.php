<?php 
class Action_DeleteService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$serviceId = intval($arrInput['serviceId']);
		
		if (empty($serviceId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$serviceInfoDao = new Dao_ServiceInfo(Tool_Const::$storeId);
		$ret = $serviceInfoDao->deleteService($serviceId);	
		if ($ret === 1) {
			return Tool_Util::returnJson();			
		} else {
			return Tool_Util::returnJson('', 1, '无此记录');
		}
	}
}
