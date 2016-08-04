<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_DelRelateMyCar extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		$openId = Tool_WeiXin::getOpenid();

		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$arr = array("wechat_num" => "");
		$ret = $userInfoDao->updateUserInfoByOpenId($arr, $openId);
		//if (!$ret){
		//	return Tool_Util::returnJson('',1,'failed');
		//}
		return Tool_Util::returnJson('',0,'succ');
	}

}
