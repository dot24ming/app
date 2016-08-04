<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileList extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
	    $arrRequest = Saf_SmartMain::getCgi();

		$userName = $_COOKIE['user_name'];
		$storeName = $_COOKIE['store_name'];

        $arrInput = $arrRequest['get'];
		$userId = $arrInput['user_id'];
		//$userId = 1;
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		

		//4. chage data to out format
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		$carInfoDao = new Dao_CarInfo();
		$carInfos = $carInfoDao->getInfoByOwnerId($userId);
		//return Tool_Util::returnJson(array('info' => $carInfos));

		$url = "http://115.29.104.45:8080/mobileinfo?user_id=$userId&";
		$redirectUrl = "http://115.29.104.45:8080/mobileaddcar?user_id=$userId";
		$plate_nums = array();
		for($i=0; $i < count($carInfos); $i++){
			$plateNum = $carInfos[$i]['plate_number'];
			$plate_nums[$plateNum] = $url."plate_number=$plateNum";
		}
		//return Tool_Util::returnJson(array('info' => $plate_nums));

		$address = $userInfo['addr_province'].$userInfo["addr_city"].$userInfo["addr_district"].$userInfo["addr_road"];
		$data = array("name"=>$userInfo['name'],
						"address"=>$address,
						"car_reg_time"=>$userInfo['car_license_valid_time'], 
						"phone_num" => $userInfo['phone_num'],
						"redirect_url"=>$redirectUrl,
						"plate_nums" => $plate_nums,
						"userName" => $userName, 
						"storeName" =>$storeName,
					);
		//return Tool_Util::returnJson(array('info' => $data));

		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
		Tool_Util::displayTpl($data, 'mobile/page/carowner/list.tpl');
	}

}
