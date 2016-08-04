<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Carillegalinfo extends Ap_Action_Abstract {

	public function execute() {
		$openid = Tool_WeiXin::getOpenid_X();

		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfo = $userInfoDao->getUserInfoByOpenId($openid);

		$userId = $userInfo['user_id'];
		$storeId = Tool_Const::$storeId;
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
		$carInfo = $carInfoDao->getInfoByOwnerId($userId);
		/*
		if (empty($carInfo['frame_number']) || empty($carInfo['engine_number'])) {
			header('location: /wx/relatemycar?returnUrl=/wx/carillegalinfo');
			exit();
		} else {
		 */
			$data = array('plate_num' => $carInfo['plate_number']);

		//}
		Tool_Util::displayTpl($data, 'mobile/page/wx/car_illega_info.tpl');

	}

}
