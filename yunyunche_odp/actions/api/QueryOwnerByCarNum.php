<?php 
class Action_QueryOwnerByCarNum extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		
		$carNum = strval($arrInput['carnum']);

		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$carInfo = $carInfoDao->getInfoByPlateNumber($carNum);
		if (empty($carInfo) || !is_array($carInfo)) {
			return Tool_Util::returnJson();
		}

		$userId = $carInfo['owner_id'];
		$userIds = array($userId);

		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfos = $userInfoDao->getUserInfoByUserIds($userIds);
		if (empty($userInfos) || !is_array($userInfos)) {
			return Tool_Util::returnJson();
		}
		
		$seriesId = $carInfo['series'];
		$series = new Dao_CarSeries();
        $seriesInfo = $series->getInfoBySeries($seriesId);

		$ret = array(
			'userId' => $userInfos[0]['user_id'],
			'name' => $userInfos[0]['name'],
			'phoneNum' => $userInfos[0]['phone_num'],
			'wechatNum' => $userInfos[0]['wechat_num'],
			'carLicenseNum' => $userInfos[0]['car_license_num'],
			'plateNumber' => $carInfo['plate_number'],
			'brandName' => $seriesInfo['brand'],
			'seriesName' => $seriesInfo['series'],
			'member_card_balance' => $userInfos[0]['member_card_balance'],
			'active' => $userInfos[0]['active'],
		);
		return Tool_Util::returnJson($ret);
	}
}
