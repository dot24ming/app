<?php 
class Action_CheckClient extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$phoneNum = $arrInput['phone'];
		$plateNum = $arrInput['plateNum'];
		if (empty($phoneNum) && empty($carNum)){
			return Tool_Util::returnJson();
		}
		$phoneNum = '123';
		if (!empty($phoneNum)){
			$checkClientDao = new Dao_UserInfo();
			$userInfo = $checkClientDao->getUserInfoByPhone($phoneNum);
			if (empty($userInfo)) {
				$userId = $userInfo['user_id'];
				$checkClientDao = new Dao_CarInfo();
				$carinfos = $checkClientDao->getInfoByOwnerId($userId);
				if (empty($carinfos)){
					return Tool_Util::returnJson();
				}
				if (count($carinfos) == 0){
					header('Location: /mobileadd');
				}
				else if (count($carinfos) == 1){
					header('Location: /mobileinfo');
				}
				else{
					header('Location: /mobilelist');
				}
			}
		}
		else {
			$checkClientDao = new Dao_CarInfo();
			$carinfo = $checkClientDao->getInfoByPlateNumber($carNum);
			if (empty($carinfo)) {
				return Tool_Util::returnJson();
			}
			$ownerId = $carinfo['owner_id'];

			$carinfos = $checkClientDao->getInfoByOwnerId($ownerId);
			if (empty($carinfos)){
				return Tool_Util::returnJson();
			}
			if (count($carinfos) == 0){
				header('Location: /mobileadd');
			}
			else if (count($carinfos) == 1){
				header('Location: /mobileinfo');
			}
			else{
				header('Location: /mobilelist');
			}
			
		}
	}
}
