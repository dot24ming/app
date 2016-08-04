<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileHome extends Ap_Action_Abstract {

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
		$userName = $arrInput['user_name'];
        //Bd_Log::debug('request input', 0, $arrInput);
        
		$openid = Tool_Util::getOpenid();
		$mobileEmployeeDao = new Dao_MobileEmployee();
		$employeeInfo = $mobileEmployeeDao->getEmployeeByOpenId($openid);
		if (!$employeeInfo){
			header('HTTP/1.0 403 Forbidden');
			echo 'You are forbidden!';
			return;
		}
	    //3. call PageService
		

		//4. chage data to out format
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$mobileEmployeeApplyDao = new Dao_MobileEmployeeApply();
		$employeeApplyInfo = $mobileEmployeeApplyDao->getEmployeeApplyInfoByOpenId($openid);
		$userName = $employeeApplyInfo['name'];
		$storeId = $employeeApplyInfo['store_id'];
		$storeInfoDao = new Dao_StoreInfo();
		$storeInfo = $storeInfoDao->getStoreInfoById($storeId);
		$storeName = $storeInfo['name'];
		Service_Data_User::setCookie("xinshisu");
		setcookie('user_name', $userName);
		setcookie('store_name', $storeName);
		$data = array('userName'=>$userName, 'storeName'=>'新时速');
		$tpl = Bd_TplFactory::getInstance();
	    //$tpl->display('mobile/page/index/home.tpl');
		//Tool_Util::returnJson($data);
		Tool_Util::displayTpl($data, 'mobile/page/index/home.tpl');

	}

}
