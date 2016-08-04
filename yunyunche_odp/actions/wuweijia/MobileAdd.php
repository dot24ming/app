<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileAdd extends Ap_Action_Abstract {

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
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		

		//4. chage data to out format
		$arr = array();
		if (empty($userId)){
			$redirectUrl = "http://115.29.104.45:8080/mobileaddcar";
		}
		else{
			$redirectUrl = "http://115.29.104.45:8080/mobilelist?user_id=$userId";	
			//$redirectUrl = "http://115.29.104.45:8080/mobilelist?user_id=";	
			$userInfoDao = new Dao_UserInfo();
			$userInfo = $userInfoDao->getUserInfoByUserId($userId);
			$arr["phone_num"] = $userInfo['phone_num'];
			$arr["name"] = $userInfo['name'];
			$arr["gender"] = $userInfo['gender'];
			$arr["car_license_num"] = $userInfo['car_license_num'];
			$arr["reg_time"] = $userInfo['reg_time'];
			$arr["addr_province"] = $userInfo['addr_province'];
			$arr["addr_city"] = $userInfo['addr_city'];
			$arr["addr_district"] = $userInfo['addr_district'];
			$arr["addr_road"] = $userInfo['addr_road'];
		}
		$arr["redirect_url"] = $redirectUrl;
		$arr["user_id"] = $userId;
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
	    //$tpl->display('mobile/page/carowner/add.tpl');
		Tool_Util::displayTpl($arr, 'mobile/page/carowner/add.tpl');

	}

}
