<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_RelateMyCar extends Ap_Action_Abstract {

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

		$data = array();
	
		$openid = Tool_WeiXin::getOpenid();
		$data['openid'] = $openid;


		//var_dump($openid);
		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfo = $userInfoDao->getUserInfoByOpenId($openid);
		//var_dump($userInfo);
		//
		
		if (!empty($userInfo)) {
			header('location: /wx/mycar?returnUrl=/wx/relatemycar');
			exit();
		}
		
		//user has related one car
		//TODO

		//$tpl = Bd_TplFactory::getInstance();
		//$tpl->assign('data',$data);
		Tool_Util::displayTpl($data, 'mobile/page/wx/relatemycar.tpl');
		//$tpl->display('admin/page/instock.tpl');

		exit;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		//Bd_Log::addNotice('out', $arrOutput);

	}

}
