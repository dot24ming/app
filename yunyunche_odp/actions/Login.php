<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Login extends Ap_Action_Abstract {

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
		$objServicePageLogin = new Service_Page_Login();
		$arrPageInfo = $objServicePageLogin->execute($arrInput);
		

		//4. chage data to out format
		$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$tpl = Bd_TplFactory::getInstance();
	    $tpl->assign('arrOutput',$arrOutput['data']);
	    $tpl->display('admin/page/login.tpl');

	}

}
