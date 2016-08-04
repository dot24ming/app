<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_InStock extends Ap_Action_Abstract {

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
		$form_status = Tool_Const::$Instock_status["reviewing"];
		// get current login user
		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		$admin_store_id = $admin_info["store_id"];

		$data = array();
		//$data["instock_id"] = $form_id;
		$data["author"] = $admin_name;
		//$data["datetime"] = $form_time;
		$data["instock_status"] = $form_status;
		$data["warehouse_id"] = $admin_store_id;

		//var_dump($data);
		//exit;

		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('data',$data);
		Tool_Util::displayTpl($data, 'admin/page/instock.tpl');
		//$tpl->display('admin/page/instock.tpl');

		#Tool_Util::displayTpl($data, 'admin/page/instock.tpl');

		exit;

		//4. chage data to out format
		//$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		/*
		$tpl = Bd_TplFactory::getInstance();
	    $tpl->assign('arrOutput',$arrOutput['data']);
	    $tpl->display('admin/page/addcustomer.tpl');
		*/

		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		//Bd_Log::addNotice('out', $arrOutput);
		#Bd_log::debug($arrOutput);
		#var_dump($arrOutput);

	}

}
