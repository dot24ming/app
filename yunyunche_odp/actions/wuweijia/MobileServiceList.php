<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileServiceList extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
	    $arrRequest = Saf_SmartMain::getCgi();
		$userName = $_COOKIE['user_name'];
		$storeName = $_COOKIE['store_name'];
		/*
        $arrInput = $arrRequest['get'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		$userId = $arrInput['user_id'];
		$plateNum = $arrInput['plate_number'];
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		

		//4. chage data to out format


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
		//$carInfoEx['car_reg_time'] = $carInfos['car_reg_time'];
		//$carInfoEx['kilometers'] = $carInfos['kilometers'];
		$redirectUrl = "http://115.29.104.45:8080/mobileinfo?user_id=$userId";	
		$serviceUrl = array();
		$serviceUrl['mr_url'] = '';
		$serviceUrl['qx_url'] = '';
		$serviceUrl['lt_url'] = '';
		$serviceUrl['bp_url'] = '';
		$serviceUrl['db_url'] = '';
		$serviceUrl['xs_url'] = '';
		$info = array();
		$info['user_info'] = $userInfoEx;
		$info['service_url'] = $serviceUrl;
*/

		//return Tool_Util::returnJson(array('info' => $carInfoEx));
		//$data=array("redirect_url"=>$redirectUrl, "");
		//5
		//. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
	    //$tpl->display('mobile/page/carowner/add_car.tpl');
		//Tool_Util::displayTpl($data, 'admin/page/searchcustomer.tpl');
		Tool_Util::displayTpl(array('userName' => $userName, 'storeName'=>$storeName), 'mobile/page/service/list.tpl');
	}

}
