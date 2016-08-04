<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileQrcode extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
	    $arrRequest = Saf_SmartMain::getCgi();
		$userName = $_COOKIE['user_name'];
		$storeName = $_COOKIE['store_name'];
		//return Tool_Util::returnJson(array('info' => $data));

		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
		Tool_Util::displayTpl(array('userName' => $userName, 'storeName'=>$storeName), 'mobile/page/user/qrcode.tpl');
	}

}
