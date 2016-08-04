<?php 
class Action_ServiceList extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}
		$adminId = Service_Data_User::getAdminId();

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		

		$department = new Dao_Department(Tool_Const::$storeId);
		$departments = $department->getDepartmentInfo();
		$serviceType = new Dao_ServiceType(Tool_Const::$storeId);
		$children = $serviceType->getAllType();
		if (empty($children)) {
			$parents = $serviceType->getParent();
			if (empty($parents)) {	
				return Tool_Util::returnJson(array('idList' => array(), 'departmentList' => $departments));
			} 
			foreach ($parents as $parent) {
				$idList[] = array(
					'id' => $parent['id'],
					'name' => $parent['name'],
					'subList' => array(),
				);
			}
			return Tool_Util::returnJson(array('idList' => $idList, 'departmentList' => $departments));
		} 
		
		foreach ($children as $child) {	
			$subList[$child['super_type_id']][] = array(
				'id' => $child['id'],
				'name' => $child['name']
			);
		}
		$parents = $serviceType->getParent();
		if (!empty($parents)) {
			foreach ($parents as $parent) {
				$idList[] = array(
					'id' => $parent['id'],
					'name' => $parent['name'],
					'subList' => $subList[$parent['id']],
				);
			}
		}
		return Tool_Util::returnJson(array('idList' => $idList, 'departmentList' => $departments));
	}
}
