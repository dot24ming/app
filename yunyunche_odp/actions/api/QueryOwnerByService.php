<?php 
class Action_QueryOwnerByService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		
		$serviceId = intval($arrInput['serviceId']);

		$serviceInfoDao = new Dao_ServiceInfo();
		$serviceInfo = $serviceInfoDao->getInfoById($serviceId);
		$serviceName = $serviceInfo['name'];

		$start = isset($arrInput['start']) ? intval($arrInput['start']) : 0;
		$end = isset($arrInput['end']) ? intval($arrInput['end']) : 0;
		$startday = $arrInput['startday'];
		$endday = $arrInput['endday'];
		
		$maintenanceServiceDao = new Dao_MaintenanceService();
		$maintenanceIds = $maintenanceServiceDao->getMaintenanceIdByServiceId($serviceId);

		$maintenanceInfoDao = new Dao_MaintenanceInfo(Tool_Const::$storeId);
		$infos = $maintenanceInfoDao->
			getInfoByMaintenanceIds($maintenanceIds, $startday, $endday, $start, $end, $total);
		if (empty($infos) || !is_array($infos)) {
			return Tool_Util::returnJson();
		}

		$userIds = array();
		foreach ($infos as &$info) {
			$info = Tool_Util::keyFormat($info);
			$userIds[] = $info['userId'];
		}
		$userIds = array_unique($userIds);		
		$userInfoDao = new Dao_UserInfo();
		$userInfos = $userInfoDao->getUserInfoByUserIds($userIds);

		if (empty($userInfos) || !is_array($userInfos)) {
			return Tool_Util::returnJson();
		}

		$users = array();
		foreach ($userInfos as $userInfo) {
			$users[$userInfo['user_id']] = Tool_Util::keyFormat($userInfo);
		}

		$ret = array();
		foreach ($infos as $info) {
			$ret[] = array(
				'userId' => $info['userId'],
				'name' => $users[$info['userId']]['name'],
				'phoneNum' => $users[$info['userId']]['phoneNum'],
				'wechatNum' => $users[$info['userId']]['wechatNum'],
				'carLicenseNum' => $users[$info['userId']]['carLicenseNum'],
				'serviceName' => $serviceName,
				'createTime' => $info['createTime'],
			);
		}
		return Tool_Util::returnJson(array('total' => $total, 'ownerList' => $ret));
	}
}
