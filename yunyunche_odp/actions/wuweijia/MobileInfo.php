<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileInfo extends Ap_Action_Abstract {

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

		//$userId = 1;
		//$plateNum = "粤B123666";
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		$address = $userInfo['addr_province'].$userInfo["addr_city"].$userInfo["addr_district"].$userInfo["addr_road"];
		$userInfoEx = array();
		$userInfoEx['name'] = $userInfo['name'];
		$userInfoEx['address'] = $address;
		$userInfoEx['car_reg_time'] = $userInfo['car_license_valid_time'];
		$userInfoEx['car_license_num'] = $userInfo['car_license_num'];
		$userInfoEx['phone_num'] = $userInfo['phone_num'];
		$userInfoEx["redirect_url"] = "http://115.29.104.45:8080/mobileadd?user_id=$userId";

		$carInfoDao = new Dao_CarInfo();
		$carInfo = $carInfoDao->getInfoByPlateNumber($plateNum);
		$plate_number = $carInfo['plate_number'];	
		$carInfoEx = array();
		$carInfoEx['plate_number'] = $carInfo['plate_number'];
		$carInfoEx['series'] = $carInfo['series'];
		$carInfoEx['frame_number'] = $carInfo['frame_number'];
		$carInfoEx['engine_number'] = $carInfo['engine_number'];
		$carInfoEx['car_reg_time'] = $carInfo['car_reg_time'];
		$carInfoEx['kilometers'] = $carInfo['kilometers'];
		$carInfoEx["redirect_url"] = "http://115.29.104.45:8080/mobileaddcar?user_id=$userId&plate_number=$plate_number";
        //Bd_Log::debug('request input', 0, $arrInput);


	    //3. call PageService
		$data = array("user_info"=>$userInfoEx, "car_info"=>$carInfoEx);
		
		
		//return Tool_Util::returnJson(array('info' => $data));

		//4. chage data to out format
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		Tool_Util::displayTpl($data, 'mobile/page/carowner/info.tpl');

	}

}
