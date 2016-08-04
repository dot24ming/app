<?php 
class Action_DeleteServiceType extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$typeId = intval($arrInput['serviceTypeId']);
		if (empty($typeId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$serviceTypeDao = new Dao_ServiceType(Tool_Const::$storeId);
		$ret = $serviceTypeDao->deleteType($typeId);
	
		if ($ret === 1) {
			return Tool_Util::returnJson();			
		} else {
			return Tool_Util::returnJson('', 1, "删除失败");
		}
	}
}
