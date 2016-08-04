<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Authority extends Ap_Action_Abstract {

	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
        if (!$isAdmin) {
        	header('Location: /noauth');
        }
		$permissionInfoDao = new Dao_PermissionInfo();
		$permissionInfo = $permissionInfoDao->getAll();		
		if (!empty($permissionInfo) && is_array($permissionInfo)) {
			$permissions = array();
			foreach ($permissionInfo as $item) {
				$permissions[$item['permission_id'] / 1000][] = Tool_Util::keyFormat($item);
			}
		}

		$data = array('authorityList' => $permissions);
		Tool_Util::displayTpl($data, 'admin/page/authority.tpl');
	}
}

