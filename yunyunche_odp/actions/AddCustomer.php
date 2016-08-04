<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_AddCustomer extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
		
	    $arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$data = array();
		
		if(isset($arrInput['plate_number']))
		{
			$data["plate_number"] = $arrInput["plate_number"];
		}
		//var_dump($arrInput);
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		$objServicePageLogin = new Service_Page_AddCustomer();
		$arrPageInfo = $objServicePageLogin->execute($arrInput);
		

		Tool_Util::displayTpl($data, 'admin/page/addcustomer.tpl');
		
		exit;
		//4. chage data to out format
		$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$tpl = Bd_TplFactory::getInstance();
	    $tpl->assign('arrOutput',$arrOutput['data']);
	    $tpl->display('admin/page/addcustomer.tpl');
		
		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		Bd_Log::addNotice('out', $arrOutput);
		#Bd_log::debug($arrOutput);
		#var_dump($arrOutput);

	}

}