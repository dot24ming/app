<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileSubmitService extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		$userId = $arrInput['user_id'];
		$plateNum = $arrInput['plate_number'];
		if (empty($userId)){
			Tool_Util::displayTpl("", 1);
		}
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		$userInfoEx = array();
		$userInfoEx['name'] = $userInfo['name'];
		$userInfoEx['phone_num'] = $userInfo['phone_num'];
		
		if (empty($plateNum)){
			Tool_Util::displayTpl("", 1);
		}
		$carInfoDao = new Dao_CarInfo();
		$carInfos = $carInfoDao->getInfoByPlateNumber($plateNum);
		$userInfoEx['plate_number'] = $carInfos['plate_number'];
		$userInfoEx['series'] = $carInfos['series'];
		$userInfoEx['frame_number'] = $carInfos['frame_number'];
		$userInfoEx['engine_number'] = $carInfos['engine_number'];
		$userInfoEx['edit_url'] = "http://115.29.104.45:8080/mobileserviceselect?service_id=$id";
		Tool_Util::displayTpl($userInfoEx, 'mobile/page/carowner/service_submit.tpl');
	}

}
