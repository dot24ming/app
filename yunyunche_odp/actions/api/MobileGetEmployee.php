<?php 
class Action_MobileGetEmployee extends Ap_Action_Abstract {
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
		$MobileEmployeeDao = new Dao_MobileEmployee();
		$ret = $MobileEmployeeDao->getEmployee();
		return Tool_Util::returnJson($ret);
	}
}
