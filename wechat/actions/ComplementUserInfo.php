<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 */
class Action_ComplementUserInfo extends Ap_Action_Abstract {

	public function execute() {

		$openid = Tool_WeiXin::getOpenid();

		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByOpenId($openid);

		$userId = $userInfo['user_id'];
		$storeId = Tool_Const::$storeId;;
		
		$carInfoDao = new Dao_CarInfo($storeId);
		$carInfo = $carInfoDao->getInfoByOwnerId($userId);
		if (empty($carInfo)) {
			$data = array();
		} else {
			$data = array('plate_num' => $carInfo['plate_number']);
		}
		Tool_Util::displayTpl($data, 'mobile/page/wx/complement_user_info.tpl');

	}

}
