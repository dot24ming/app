<?php 
class Action_QueryServiceType extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$superId = intval($arrInput['superId']);	
		$start = intval($arrInput['start']);	
		$end = intval($arrInput['end']);
		
		if (empty($superId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}		

		$serviceTypeDao = new Dao_ServiceType();

		$parent = $serviceTypeDao->getInfoById($superId);
		if (empty($parent)) {
			return Tool_Util::returnJson();
		}

		$children = $serviceTypeDao->getChild($superId, $start, $end, &$total);	
		if (empty($children)) {
			return Tool_Util::ReturnJson();
		}

		$serviceTypeList = array();
		foreach ($children as $child) {
			$serviceType = array(
				'serviceTypeName' => $child['name'],
				'serviceTypeId' => $child['id'],
				'superName' => $parent['name'],
			);
			$serviceTypeList[] = $serviceType;
		}		
		return Tool_Util::returnJson(array('total' => $total, 'serviceTypeList' => $serviceTypeList));	
	}
}
