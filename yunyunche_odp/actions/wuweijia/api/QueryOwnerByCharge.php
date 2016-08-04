<?php 
class Action_QueryOwnerByCharge extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
	
		$charge = $arrInput['charge'];
		$relation = $arrInput['relation'];
		$startday = str_replace($arrInput['startday'], '-', '');
		$endday = str_replace($arrInput['endday'], '-', '');
		$start = isset($arrInput['start']) ? intval($arrInput['start']) : 0;
		$end = isset($arrInput['end']) ? intval($arrInput['end']) : 0;

		$maintenanceInfoDao = new Dao_MaintenanceInfo();
		$maintenanceInfos = $maintenanceInfoDao->
			getInfoByCharge($charge, $relation, $startday, $endday, $start, $end, $total);

		if (empty($maintenanceInfos) || !is_array($maintenanceInfos)) {
			return Tool_Util::returnJson();
		}

		$userIds = array();
		foreach ($maintenanceInfos as &$info) {
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
		foreach ($maintenanceInfos as $info) {
			$ret[] = array(
				'userId' => $info['userId'],
				'name' => $users[$info['userId']]['name'],
				'phoneNum' => $users[$info['userId']]['phoneNum'],
				'wechatNum' => $users[$info['userId']]['wechatNum'],
				'carLicenseNum' => $users[$info['userId']]['carLicenseNum'],
				'createTime' => $info['createTime'],
				'totalCharge' => $info['allCharge'],
			);
		}
		return Tool_Util::returnJson(array('total' => $total, 'ownerList' => $ret));
	}
}
