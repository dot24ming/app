<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileUserRequirementInfo extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$userId = intval($arrInput['user_id']);
		$plateNumber = $arrInput['plate_number'];

		$data['user_id'] = $userId;
		$data['plate_number'] = $plateNumber;
		$userInfoDao = new Dao_UserInfo();
        $userInfo = $userInfoDao->getUserInfoByUserId($userId);
        $address = $userInfo['addr_province'].$userInfo["addr_city"].$userInfo["addr_district"].$userInfo["addr_road"];
        $userInfoEx = array();
        $userInfoEx['name'] = $userInfo['name'];
        $userInfoEx['address'] = $address;
        $userInfoEx['expired_time'] = $userInfo['car_license_valid_time'];
		$userInfoEx['phone_num'] = $userInfo['phone_num'];
		$userInfoEx['license_number'] = $userInfo['car_license_num'];

        $carInfoDao = new Dao_CarInfo();
        $carInfo = $carInfoDao->getInfoByPlateNumber($plateNumber);
		$plate_number = $carInfo['plate_number'];   
        $carInfoEx = array();
        $carInfoEx['plate_number'] = $carInfo['plate_number'];
        $carInfoEx['series'] = $carInfo['series'];
        $carInfoEx['frame_number'] = $carInfo['frame_number'];
        $carInfoEx['engine_number'] = $carInfo['engine_number'];
        $carInfoEx['car_reg_time'] = $carInfo['car_reg_time'];
        $carInfoEx['kilometers'] = $carInfo['kilometers'];

        //3. call PageService
        $data = array("user_info"=>$userInfoEx, "car_info"=>$carInfoEx);	
		Tool_Util::displayTpl($data, 'mobile/page/userrequirement/info.tpl');
	}
}
