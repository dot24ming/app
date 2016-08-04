<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Services extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
		/*
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		*/
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		/*
		$objServicePageLogin = new Service_Page_AddCustomer();
		$arrPageInfo = $objServicePageLogin->execute($arrInput);
		*/

		$openid = Tool_WeiXin::getOpenid_X();

		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfo = $userInfoDao->getUserInfoByOpenId($openid);

		$userId = $userInfo['user_id'];
		$storeId = Tool_Const::$storeId;
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$carInfo = $carInfoDao->getInfoByOwnerId($userId);

		if (empty($carInfo)) {
			header('location: /wx/relatemycar?returnUrl=/wx/services');
			exit();
		}

		$data = array();
		$db_client = new Dao_DBbase();

		$user_openid = Tool_WeiXin::getOpenid_X();
		$user_openid = $userId;

		//$user_select_result = $db_client->select(Tool_Const::$storeId.'_user_info','*',' wechat_num="'.$user_openid.'"',NULL,NULL);
		$user_select_result = $db_client->select(Tool_Const::$storeId.'_user_info','*',' user_id="'.$user_openid.'"',NULL,NULL);
		if($user_select_result!=false and count($user_select_result)>0)
		{
			$user_info = $user_select_result[0];
			$user_id = $user_info['user_id'];
			$car_select_result = $db_client->select(Tool_Const::$storeId.'_car_info','*',' owner_id='.$user_id,NULL,NULL);

			if($car_select_result!=false and count($car_select_result)>0)
			{
				$car_info = $car_select_result[0];
				$plate_number = $car_info['plate_number'];
				$service_select = $db_client->select(Tool_Const::$storeId.'_mobile_service_form','*',' plate_number="'.$plate_number.'"',NULL,NULL);
				#var_dump($service_select);
				$data['plate_number'] = $plate_number;
				$data['service_total_num'] = 0;
				$data['service_total_cost'] = 0;
				if ($service_select!=false)
				{
					$cost = 0.0;
					foreach($service_select as $service_info)
					{
						$cost += $service_info["price"];
					}
					$data['service_total_num'] = count($service_select);
					$data['service_total_cost'] = $cost;
				}
			}
		}

		//$tpl = Bd_TplFactory::getInstance();
		//$tpl->assign('data',$data);
		//$return_data = array(
		//		'data' => $data,
		//	);
		//var_dump($return_data);
		//var_dump($data);
		Tool_Util::displayTpl($data, 'mobile/page/wx/services.tpl');
		//$tpl->display('admin/page/instock.tpl');

		exit;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		//Bd_Log::addNotice('out', $arrOutput);

	}

}
