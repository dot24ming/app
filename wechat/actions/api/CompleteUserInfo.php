<?php
/**
 * @name Action_Sample
 * @desc sample api
 * @author 
 */
class Action_CompleteUserInfo extends Saf_Api_Base_Action {

    public function __execute(){
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$plateNum = Tool_Util::filter($arrInput['plate_num']);
		$frameNumber = Tool_Util::filter($arrInput['frame_number']);
		$engineNumber = Tool_Util::filter($arrInput['engine_number']);

		$openid = Tool_Const::$openid;
		$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
		$userInfo = $userInfoDao->getUserInfoByOpenId($openid);

		$userId = $userInfo['user_id'];
		$storeId = Tool_Const::$storeId;

		$carInfoDao = new Dao_CarInfo($storeId);	
		$ret = $carInfoDao->updateCarInfoByPlateNumber($plateNum, $frameNumber, $engineNumber);
		if (!$ret) {
			return Tool_Util::returnJson('', 1, '更新失败');
		} else {
			return Tool_Util::returnJson();
		}
	}
}
