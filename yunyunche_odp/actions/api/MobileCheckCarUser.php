<?php 
class Action_MobileCheckCarUser extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$phoneNum = $arrInput['phone_num'];
		$plateNum = $arrInput['plate_number'];
		$checkUserDao = new Dao_UserInfo();
		$checkCarDao = new Dao_CarInfo();
		//$plateNum = "粤B0M881";
		//$phoneNum = "18600467909";
		if (!empty($phoneNum)){
			$userInfo = $checkUserDao->getUserInfoByPhone($phoneNum);
			$userId = $userInfo['user_id'];
			$carInfo = $checkCarDao->getInfoByOwnerId($userId);
		}
		else {
			$carInfo = $checkCarDao->getInfoByPlateNumber($plateNum);
			$userId = $carInfo['owner_id'];
			$userInfo = $checkUserDao->getUserInfoByUserId($userId);
		}

		$res = array();
		if (empty($carInfo) && empty($userInfo)){
			return Tool_Util::returnJson("", 1);
		}
		$res['user_id'] = $userInfo['user_id'];
		$res['name'] = $userInfo['name'];
		$res['phone_num'] = $userInfo['phone_num'];
		$res['plate_number'] = $carInfo['plate_number'];
		$res['frame_number'] = $carInfo['frame_number'];
		$res['engine_number'] = $carInfo['engine_number'];
		//return Tool_Util::returnJson($res, 0);
		return Tool_Util::returnJson(array_merge($userInfo, $carInfo), 0);
	}
}
