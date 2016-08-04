<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_OutStock extends Ap_Action_Abstract {

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
		//$form_id = ""; //TODO
		//$form_time = date('Y-m-d H:i:s');
		// init instock_status
		$form_status = Tool_Const::$Outstock_status["reviewing"];
		// get current login user
		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		$admin_store_id = $admin_info["store_id"];

		$data = array();
		$data["author"] = $admin_name;
		$data["outstock_status"] = $form_status;
		$data["warehouse_id"] = $admin_store_id;


		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('data',$data);
		Tool_Util::displayTpl($data, 'admin/page/outstock.tpl');
	}

}
