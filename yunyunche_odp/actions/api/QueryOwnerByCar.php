<?php 
class Action_QueryOwnerByCar extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		
		$seriesId = intval($arrInput['seriesId']);
		$start = isset($arrInput['start']) ? intval($arrInput['start']) : 0;
		$end = isset($arrInput['end']) ? intval($arrInput['end']) : 0;
		
		$series = new Dao_CarSeries();
		$seriesInfo = $series->getInfoBySeries($seriesId);
		
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$carInfos = $carInfoDao->getInfoBySeriesId($seriesId, $start, $end, $total);
		if (!is_array($carInfos) || empty($carInfos)) {
			return Tool_Util::returnJson();
		}	
		$userIds = array();	
		foreach ($carInfos as &$info) {
			$info = Tool_Util::keyFormat($info);
			$userIds[] = $info['ownerId'];
		}
		$userIds = array_unique($userIds);

		$userInfo = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfos = $userInfo->getUserInfoByUserIds($userIds);
		$users = array();
		foreach ($userInfos as &$userInfo) {
			$userInfo = Tool_Util::keyFormat($userInfo);
			$users[$userInfo['userId']] = $userInfo;
		}
		
		$ret = array();
		foreach ($carInfos as $item) {
			$ret[] = array(
				'userId' => $item['ownerId'],
				'name' => $users[$item['ownerId']]['name'],
				'phoneNum' => $users[$item['ownerId']]['phoneNum'],
				'wechatNum' => $users[$item['ownerId']]['wechatNum'],
				'carLicenseNum' => $users[$item['ownerId']]['carLicenseNum'],
				'plateNumber' => $item['plateNumber'],
				'brandName' => $seriesInfo['brand'],
				'seriesName' => $seriesInfo['series'],
			);
		}
		return Tool_Util::returnJson(array('total' => $total, 'ownerList' => $ret));
	}
}
