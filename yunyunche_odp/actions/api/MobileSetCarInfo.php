<?php 
class Action_MobileSetCarInfo extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		//$arr = $arrRequest['post'];
		//return Tool_Util::returnJson(true);


		$arr = array(
				'series' => $arrInput['series'],
				'kilometers' => $arrInput['kilometers'],
				'car_reg_time' => $arrInput['car_reg_time'],
				'frame_number' => $arrInput['frame_number'],
				'engine_number' => $arrInput['engine_number'],
				'owner_id' => $arrInput['user_id'],
				);
		$plateNum = $arrInput['plate_number'];
		$userId = $arrInput['user_id'];
		//$arr = array(
		//		'plate_number' => '123',
		//		'series' => '1234',
		//		'kilometers' => '2211',
		//		'car_reg_time' => '1233',
		//		'frame_number' => '1111',
		//		'engine_number' => '2334',
		//		'owner_id' => '123'
		//		);
		$carInfoDao = new Dao_CarInfo();
		$carInfos = $carInfoDao->getInfoByPlateNumber($plateNum);
		//$user_id = $arr['owner_id'];
		if (empty($carInfos)){
			$arr['plate_number'] = $plateNum;
			$ret = $carInfoDao->setCarInfo($arr);
			if ($ret){
				return Tool_Util::returnJson(array("redirect_url" => "http://115.29.104.45:8080/mobilelist?user_id=$user_id"));
			}
			else{
				return Tool_Util::returnJson(0,1);
			}
		}
		else{
			$ret = $carInfoDao->updateCarInfo($arr, $plateNum);
			if ($ret){
				return Tool_Util::returnJson(array("redirect_url" => "http://115.29.104.45:8080/mobileinfo?plate_number=$plateNum&user_id="));
			}
			else{
				return Tool_Util::returnJson(0,1);
			}
		}
	}
}
