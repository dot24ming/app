<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileServiceForm extends Ap_Action_Abstract {

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
		$userName = $_COOKIE['user_name'];
		$storeName = $_COOKIE['store_name'];
		/*
		$userId = $arrInput['user_id'];
		$plateNum = $arrInput['plate_number'];
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		

		//4. chage data to out format

		$carInfoEx = array();
		
		if (!empty($plateNum)){
			$carInfoDao = new Dao_CarInfo();
			$carInfos = $carInfoDao->getInfoByPlateNumber($plateNum);
			$carInfoEx['plate_number'] = $carInfos['plate_number'];
			$carInfoEx['series'] = $carInfos['series'];
			$carInfoEx['frame_number'] = $carInfos['frame_number'];
			$carInfoEx['engine_number'] = $carInfos['engine_number'];
			$carInfoEx['car_reg_time'] = $carInfos['car_reg_time'];
			$carInfoEx['kilometers'] = $carInfos['kilometers'];
		}
		$redirectUrl = "http://115.29.104.45:8080/mobileinfo?user_id=$userId";	
		$carInfoEx["redirect_url"] = $redirectUrl;
		$carInfoEx["user_id"] = $userId;
		*/
		//return Tool_Util::returnJson(array('info' => $carInfoEx));
		//$data=array("redirect_url"=>$redirectUrl, "");
		//5
		//. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
	    //$tpl->display('mobile/page/carowner/add_car.tpl');
		//Tool_Util::returnJson($userName);
		Tool_Util::displayTpl(array('userName' => $userName, 'storeName'=>$storeName), 'mobile/page/service/form.tpl');
	}

}
