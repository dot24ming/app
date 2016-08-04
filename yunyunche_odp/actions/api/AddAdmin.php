<?php 
class Action_AddAdmin extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$username = Tool_Util::filter($arrInput['username']);
		$password = Tool_Util::filter($arrInput['password']);
		
		$permissionId = $arrInput['permissionId'];
		$nickname = $arrInput['nickname'];
		$department_id = $arrInput['department_id'];
		if(!isset($arrInput['department_id']) || $arrInput['department_id']="")
		{
			$department_id = 5;
		}

		//TODO
		//if (empty($permissionId) || 
		if (empty($username) || empty($password)) {
            return Tool_Util::returnJson('', 1, '参数错误'); 
        } 
				
		$adminInfoDao = new Dao_AdminInfo();
		$adminInfoDao->startTransaction();
		$ret = $adminInfoDao->addAdminDetail($username, $password, Tool_Const::$storeId,$nickname,$department_id);	
		if ($ret !== true) {
			if ($ret['errno'] == 1062) {
				$adminInfoDao->rollback();
				return Tool_Util::returnJson('', $ret['errno'], "用户名已存在");
			} else{
				$adminInfoDao->rollback();	
				return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
			}
		}

		$adminPermissionDao = new Dao_AdminPermission(Tool_Const::$storeId);
		//TODO
        //$ret = $adminPermissionDao->addBatch($username, $permissionId);
		$permissionIdList = array(
				'1001','1002','1003','1004','1005','1006','2001','2002','2003','3001','3002','3003','4001'
			);
        $ret = $adminPermissionDao->addBatch($username, $permissionIdList);
        if ($ret === true) { 
			$adminInfoDao->commit();
            return Tool_Util::returnJson();        
        } else {
			$adminInfoDao->rollback();
            return Tool_Util::returnJson('', $ret['errno'], $ret['error']);
        }
	}
}
