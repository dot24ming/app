<?php 
class Action_MobileSetUserInfo extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		/*
		$arrInput=array(
			"user_id" => "",
			"phone_num"=> '123556614',
			"name"=>"rrr",
			"plate_number"=>"1234",
			"series"=>"1616",
			"frame_number"=>"1414",
			"engine_number"=>"1414",
			"wechat_num"=>"5536",
			"car_license_num"=>"1234",
			"reg_time"=>"2016",
			"addr_province"=>"gd" ,
			"addr_city"=>"sz",
			"addr_district"=>"ee" ,
			"addr_road"=>"ss");
		 */
		$arr=array(
			"phone_num"=> $arrInput['phone_num'],
			"name"=>$arrInput['name'],
			"wechat_num"=>$arrInput['wechat_num'],
			//"gender"=>$arrInput['gender'],
			//"car_license_num"=>$arrInput['car_license_num'],
			//"reg_time"=>$arrInput['reg_time'],
			//"addr_province"=>$arrInput['addr_province'],
			//"addr_city"=>$arrInput['addr_city'],
			//"addr_district"=>$arrInput['addr_district'],
			//"addr_road"=>$arrInput['addr_road']
		);
		$userInfoDao = new Dao_UserInfo();
		$UserId = $userInfoDao->setUserInfo($arr);
		if (!$UserId){
			return Tool_Util::returnJson(0,0);
		}
		$arr = array(
				'plate_number'=>$arrInput['plate_number'],
				'series' => $arrInput['series'],
				//'kilometers' => $arrInput['kilometers'],
				//'car_reg_time' => $arrInput['car_reg_time'],
				'frame_number' => $arrInput['frame_number'],
				'engine_number' => $arrInput['engine_number'],
				'owner_id' => $UserId,
				);
		$carInfoDao = new Dao_CarInfo();
		$ret = $carInfoDao->setCarInfo($arr);
		if ($ret) {
			return Tool_Util::returnJson(0,0);
		}
		else {
			return Tool_Util::returnJson(0,1);
		}
	}
}
