<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileBuilding extends Ap_Action_Abstract {

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
        
	    //3. call PageService
		

		//4. chage data to out format
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$userName = '王小二';
		$tpl = Bd_TplFactory::getInstance();
	    //$tpl->display('mobile/page/index/home.tpl');
		Tool_Util::displayTpl("", 'mobile/page/index/building.tpl');
	    #$tpl->display('mobile/page/index/home.tpl');
	    #$tpl->display('mobile/page/carowner/add.tpl');

	}

}
