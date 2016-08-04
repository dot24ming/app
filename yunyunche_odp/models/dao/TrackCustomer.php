<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_TrackCustomer {

	private $db_client;

	const wx_push_str = "已微信通知";
	const msg_push_str = "已短信通知";
	const none_push_str = "待通知";
	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function getUserAndCarInfo($plate_num_str){
		$car_dict = array();
		$user_id_list = array();

		if ($plate_num_str==None or $plate_num_str=="")
		{
			$return_arr = array(
				'car' => $car_dict,
				'user' => $user_dict
			);
			return $return_arr;
		}

		$car_info_select = $this->db_client->select(Tool_Util::getStoreTable("car_info"),
			'plate_number, owner_id, car_reg_time','plate_number in ('.$plate_num_str.')',NULL,NULL);
		foreach($car_info_select as $car_info){
			$plate_num = $car_info["plate_number"];
			$owner_id = $car_info["owner_id"];
			$user_id_list[$owner_id] = 0;
			$car_dict[$plate_num] = $car_info;
		}
		$user_id_str = implode(',',array_keys($user_id_list));

		$user_dict = array();
		$user_info_select = $this->db_client->select(Tool_Util::getStoreTable("user_info"),
			'name, phone_num, wechat_num, user_id','user_id in ('.$user_id_str.')',NULL,NULL);
		foreach($user_info_select as $user_info){
			$name = $user_info["name"];
			$phone_num = $user_info["phone_num"];
			$wechat_num = $user_info["wechat_num"];
			$user_id = $user_info["user_id"];
			$user_dict[$user_id] = $user_info;
		}
		$return_arr = array(
				'car' => $car_dict,
				'user' => $user_dict
			);
		return $return_arr;
	}
	public function doTrackSale($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$after_sale_select = $this->db_client->select('after_sale_push',
			'*','service_time>="'.$curr_time.'"',NULL,$limit_str);
		$after_sale_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('after_sale_push'),
			'service_time>="'.$curr_time.'"',NULL,NULL);
		$after_sale_count = $after_sale_select_count;

		$plate_num_list = array();
		foreach($after_sale_select as $sale_info){
			$plate_num = $sale_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$userAndCarInfo = $this->getUserAndCarInfo($plate_num_str);
		$user_dict = $userAndCarInfo["user"];
		$car_dict = $userAndCarInfo["car"];

		$push_data = array();
		$push_data["after_sale_count"] = $after_sale_count;
		$push_data["after_sale_push"] = array();
		foreach($after_sale_select as $sale_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$service_time = "";
			$service_info = "";
			//$txt_status = "";
			//$wx_status = "";

			$service_time = $sale_info["service_time"];
			$service_info = $sale_info["service_info"];
			$plate_num = $sale_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_uid"] = $user_id;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $service_time;
			$status_list = array();
			if ($wx_status>=1)
			{
				array_push($status_list,self::wx_push_str);
			}
			if ($txt_status>=1)
			{
				array_push($status_list, self::msg_push_str);
			}
			if (count($status_list) == 0)
			{
				array_push($status_list,self::none_push_str);
			}
			$ins_item["p_status"] = implode(', ',$status_list);
			$ins_item["p_sinfo"] = $service_info;
			array_push($push_data["after_sale_push"],$ins_item);
		}
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 0
			);
		return $return_arr;
	}
	public function doTrackPeccancy($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$traffic_peccancy_select = $this->db_client->select(Tool_Util::getStoreTable('traffic_peccancy_push'),
			'*','peccancy_time>="'.$peccancy_time.'"',NULL,$limit_str);
		$traffic_peccancy_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('traffic_peccancy_push'),
			'peccancy_time>="'.$peccancy_time.'"',NULL,NULL);
		$peccancy_count = $traffic_peccancy_select_count;

		$plate_num_list = array();
		foreach($traffic_peccancy_select as $peccancy_info){
			$plate_num = $peccancy_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$userAndCarInfo = $this->getUserAndCarInfo($plate_num_str);
		$user_dict = $userAndCarInfo["user"];
		$car_dict = $userAndCarInfo["car"];

		$push_data = array();
		$push_data["traffic_peccancy_count"] = $peccancy_count;
		$push_data["traffic_peccancy_push"] = array();
		foreach($traffic_peccancy_select as $peccancy_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$peccancy_time = "";
			$peccancy_addr = "";
			$peccancy_type = "";
			$peccancy_fine = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $peccancy_info["txt_status"];
			$wx_status = $peccancy_info["wx_status"];
			$peccancy_time = $peccancy_info["peccancy_time"];
			$peccancy_addr = $peccancy_info["peccancy_addr"];
			$peccancy_type = $peccancy_info["peccancy_type"];
			$peccancy_fine = $peccancy_info["peccancy_fine"];
			$plate_num = $peccancy_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_uid"] = $user_id;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_ptime"] = $peccancy_time;
			$ins_item["p_paddr"] = $peccancy_addr;
			$ins_item["p_ptype"] = $peccancy_type;
			$ins_item["p_pfine"] = $peccancy_fine;
			$status_list = array();
			if ($wx_status>=1)
			{
				array_push($status_list,self::wx_push_str);
			}
			if ($txt_status>=1)
			{
				array_push($status_list, self::msg_push_str);
			}
			if (count($status_list) == 0)
			{
				array_push($status_list,self::none_push_str);
			}
			$ins_item["p_status"] = implode(', ',$status_list);
			//$ins_item["p_wx_s"] = $wx_status;
			//$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["traffic_peccancy_push"],$ins_item);
		}
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 0
			);
		return $return_arr;
	}
	public function doTrackUserValid($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$licence_select = $this->db_client->select(Tool_Util::getStoreTable('licence_push'),
			'*','licence_expiration_time<="'.$exp_time.'" and licence_expiration_time>="'.$curr_time.'"',NULL,$limit_str);
		$licence_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('licence_push'),
			'licence_expiration_time<="'.$exp_time.'" and licence_expiration_time>="'.$curr_time.'"',NULL,NULL);
		$licence_count = $licence_select_count;

		$plate_num_list = array();
		foreach($licence_select as $licence_info){
			$plate_num = $licence_info["licence_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$userAndCarInfo = $this->getUserAndCarInfo($plate_num_str);
		$user_dict = $userAndCarInfo["user"];
		$car_dict = $userAndCarInfo["car"];

		$push_data = array();
		$push_data["licence_count"] = $licence_count;
		$push_data["licence_push"] = array();
		foreach($licence_select as $licence_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$licence_expiration_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $licence_info["txt_status"];
			$wx_status = $licence_info["wx_status"];
			$licence_expiration_time = $licence_info["licence_expiration_time"];
			$plate_num = $licence_info["licence_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_licence"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_uid"] = $user_id;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $licence_expiration_time;
			$status_list = array();
			if ($wx_status>=1)
			{
				array_push($status_list,self::wx_push_str);
			}
			if ($txt_status>=1)
			{
				array_push($status_list, self::msg_push_str);
			}
			if (count($status_list) == 0)
			{
				array_push($status_list,self::none_push_str);
			}
			$ins_item["p_status"] = implode(', ',$status_list);
			//$ins_item["p_wx_s"] = $wx_status;
			//$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["licence_push"],$ins_item);
		}
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 0
			);
		return $return_arr;
	}
	public function doTrackCarValid($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$verification_select = $this->db_client->select(Tool_Util::getStoreTable('verification_push'),
			'*','annual_verification_time<="'.$exp_time.'" and annual_verification_time>="'.$curr_time.'"',NULL,$limit_str);
		$verification_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('verification_push'),
			'annual_verification_time<="'.$exp_time.'" and annual_verification_time>="'.$curr_time.'"',NULL,$limit_str);
		$verification_count = $verification_select_count;

		$plate_num_list = array();
		foreach($verification_select as $verification_info){
			$plate_num = $verification_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$userAndCarInfo = $this->getUserAndCarInfo($plate_num_str);
		$user_dict = $userAndCarInfo["user"];
		$car_dict = $userAndCarInfo["car"];

		$push_data = array();
		$push_data["verification_count"] = $verification_count;
		$push_data["verification_push"]= array();
		foreach($verification_select as $verification_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$annual_verification_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $verification_info["txt_status"];
			$wx_status = $verification_info["wx_status"];
			$annual_verification_time = $verification_info["annual_verification_time"];
			$plate_num = $verification_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_uid"] = $user_id;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $annual_verification_time;
			$status_list = array();
			if ($wx_status>=1)
			{
				array_push($status_list,self::wx_push_str);
			}
			if ($txt_status>=1)
			{
				array_push($status_list, self::msg_push_str);
			}
			if (count($status_list) == 0)
			{
				array_push($status_list,self::none_push_str);
			}
			$ins_item["p_status"] = implode(', ',$status_list);
			//$ins_item["p_wx_s"] = $wx_status;
			//$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["verification_push"],$ins_item);
		}
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 0
			);
		return $return_arr;
	}
	public function doTrackInsurance($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$insurance_select = $this->db_client->select(Tool_Util::getStoreTable('insurance_push'),
			'*','insurance_expiration_time<="'.$exp_time.'" and insurance_expiration_time>="'.$curr_time.'"',NULL,$limit_str);
		$insurance_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('insurance_push'),
			'insurance_expiration_time<="'.$exp_time.'" and insurance_expiration_time>="'.$curr_time.'"',NULL,NULL);
		$insurance_count = $insurance_select_count;

		$plate_num_list = array();
		foreach($insurance_select as $insurance_info){
			$plate_num = $insurance_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$userAndCarInfo = $this->getUserAndCarInfo($plate_num_str);
		$user_dict = $userAndCarInfo["user"];
		$car_dict = $userAndCarInfo["car"];

		$push_data = array();
		$push_data["insurance_count"] = $insurance_count;
		$push_data["insurance_push"] = array();
		foreach($insurance_select as $insurance_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$insurance_expiration_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $insurance_info["txt_status"];
			$wx_status = $insurance_info["wx_status"];
			$insurance_expiration_time = $insurance_info["insurance_expiration_time"];
			$plate_num = $insurance_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_uid"] = $user_id;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $insurance_expiration_time;
			$status_list = array();
			if ($wx_status>=1)
			{
				array_push($status_list,self::wx_push_str);
			}
			if ($txt_status>=1)
			{
				array_push($status_list, self::msg_push_str);
			}
			if (count($status_list) == 0)
			{
				array_push($status_list,self::none_push_str);
			}
			$ins_item["p_status"] = implode(', ',$status_list);
			//$ins_item["p_wx_s"] = $wx_status;
			//$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["insurance_push"],$ins_item);
		}
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 0
			);
		return $return_arr;
	}
	public function doTrackCustomer($arrInput){
		$curr_time = date("Y-m-d H:i:s",time());
		$exp_time = date("Y-m-d 00:00:01", strtotime("+2 months",time()));
		$peccancy_time = date("Y-m-d 00:00:01", strtotime("-7 days",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$insurance_select = $this->db_client->select(Tool_Util::getStoreTable('insurance_push'),
			'*','insurance_expiration_time<="'.$exp_time.'" and insurance_expiration_time>="'.$curr_time.'"',NULL,$limit_str);
		$verification_select = $this->db_client->select(Tool_Util::getStoreTable('verification_push'),
			'*','annual_verification_time<="'.$exp_time.'" and annual_verification_time>="'.$curr_time.'"',NULL,$limit_str);
		$licence_select = $this->db_client->select(Tool_Util::getStoreTable('licence_push'),
			'*','licence_expiration_time<="'.$exp_time.'" and licence_expiration_time>="'.$curr_time.'"',NULL,$limit_str);
		$traffic_peccancy_select = $this->db_client->select(Tool_Util::getStoreTable('traffic_peccancy_push'),
			'*','peccancy_time>="'.$peccancy_time.'"',NULL,$limit_str);
		$after_sale_select = $this->db_client->select(Tool_Util::getStoreTable('after_sale_push'),
			'*','service_time>="'.$curr_time.'"',NULL,$limit_str);

		//var_dump($traffic_peccancy_select);
		//var_dump($after_sale_select);
		//var_dump($insurance_select);
		//var_dump($verification_select);
		//var_dump($licence_select);

		
		$plate_num_list = array();
		foreach($insurance_select as $insurance_info){
			$plate_num = $insurance_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		foreach($verification_select as $verification_info){
			$plate_num = $verification_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		foreach($licence_select as $licence_info){
			$plate_num = $licence_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		foreach($traffic_peccancy_select as $peccancy_info){
			$plate_num = $peccancy_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		foreach($after_sale_select as $sale_info){
			$plate_num = $sale_info["plate_num"];
			$plate_num_list[$plate_num] = 0;
		}
		$plate_num_str = implode(',',array_keys($plate_num_list));

		$car_dict = array();
		//$car_2_user = array();
		$user_id_list = array();
		$car_info_select = $this->db_client->select(Tool_Util::getStoreTable("car_info"),
			'plate_number, owner_id, car_reg_time','plate_number in ('.$plate_num_str.')',NULL,NULL);
		foreach($car_info_select as $car_info){
			$plate_num = $car_info["plate_number"];
			$owner_id = $car_info["owner_id"];
			$user_id_list[$owner_id] = 0;
			$car_dict[$plate_num] = $car_info;
			//$car_2_user[$plate_num] = $owner_id;
		}
		$user_id_str = implode(',',array_keys($user_id_list));

		$user_dict = array();
		$user_info_select = $this->db_client->select(Tool_Util::getStoreTable("user_info"),
			'name, phone_num, wechat_num, user_id','user_id in ('.$user_id_str.')',NULL,NULL);
		foreach($user_info_select as $user_info){
			$name = $user_info["name"];
			$phone_num = $user_info["phone_num"];
			$wechat_num = $user_info["wechat_num"];
			$user_id = $user_info["user_id"];
			$user_dict[$user_id] = $user_info;
		}

		$push_data = array();
		$push_data["insurance_push"] = array();
		$push_data["verification_push"]= array();
		$push_data["licence_push"] = array();
		$push_data["traffic_peccancy_push"] = array();
		$push_data["after_sale_push"] = array();
		foreach($insurance_select as $insurance_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$insurance_expiration_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $insurance_info["txt_status"];
			$wx_status = $insurance_info["wx_status"];
			$insurance_expiration_time = $insurance_info["insurance_expiration_time"];
			$plate_num = $insurance_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $insurance_expiration_time;
			$ins_item["p_wx_s"] = $wx_status;
			$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["insurance_push"],$ins_item);
		}
		foreach($verification_select as $verification_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$annual_verification_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $verification_info["txt_status"];
			$wx_status = $verification_info["wx_status"];
			$annual_verification_time = $verification_info["annual_verification_time"];
			$plate_num = $verification_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $annual_verification_time;
			$ins_item["p_wx_s"] = $wx_status;
			$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["verification_push"],$ins_item);
		}
		foreach($licence_select as $licence_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$licence_expiration_time = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $licence_info["txt_status"];
			$wx_status = $licence_info["wx_status"];
			$licence_expiration_time = $licence_info["licence_expiration_time"];
			$plate_num = $licence_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $licence_expiration_time;
			$ins_item["p_wx_s"] = $wx_status;
			$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["licence_push"],$ins_item);
		}
		foreach($traffic_peccancy_select as $peccancy_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$peccancy_time = "";
			$peccancy_addr = "";
			$peccancy_type = "";
			$peccancy_fine = "";
			$txt_status = "";
			$wx_status = "";

			$txt_status = $peccancy_info["txt_status"];
			$wx_status = $peccancy_info["wx_status"];
			$peccancy_time = $peccancy_info["peccancy_time"];
			$peccancy_addr = $peccancy_info["peccancy_addr"];
			$peccancy_type = $peccancy_info["peccancy_type"];
			$peccancy_fine = $peccancy_info["peccancy_fine"];
			$plate_num = $peccancy_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_ptime"] = $peccancy_time;
			$ins_item["p_paddr"] = $peccancy_addr;
			$ins_item["p_ptype"] = $peccancy_type;
			$ins_item["p_pfine"] = $peccancy_fine;
			$ins_item["p_wx_s"] = $wx_status;
			$ins_item["p_txt_s"] = $txt_status;
			array_push($push_data["traffic_peccancy_push"],$ins_item);
		}
		foreach($after_sale_select as $sale_info){
			$name = "";
			$plate_num = "";
			$phone_num = "";
			$wechat_num = "";
			$service_time = "";
			$service_info = "";
			//$txt_status = "";
			//$wx_status = "";

			$service_time = $sale_info["service_time"];
			$service_info = $sale_info["service_info"];
			$plate_num = $sale_info["plate_num"];
			if(isset($car_dict[$plate_num])){
				$user_id = $car_dict[$plate_num]["owner_id"];
				if(isset($user_dict[$user_id]))
				{
					$name = $user_dict[$user_id]["name"];
					$phone_num = $user_dict[$user_id]["phone_num"];
					$wechat_num = $user_dict[$user_id]["wechat_num"];
				}
			}
			$ins_item = array();
			$ins_item["p_plate"] = $plate_num;
			$ins_item["p_name"] = $name;
			$ins_item["p_phone"] = $phone_num;
			$ins_item["p_wx"] = $wechat_num;
			$ins_item["p_time"] = $service_time;
			$ins_item["p_sinfo"] = $service_info;
			array_push($push_data["after_sale_push"],$ins_item);
		}


		//var_dump($push_data);

		/*
		$user_info_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
			'name, phone_num, wechat_num, user_id',NULL,NULL,NULL);
		$car_info_select = $this->db_client->select(Tool_Util::getStoreTable('car_info'),
			'plate_num, owner_id, car_reg_time',NULL,NULL,NULL);
		*/
		$return_arr = array(
				'result' => $push_data,
				'codeMsg' => "succ",
				'code' => 1
			);
		return $return_arr;
	}



	public function doTrackCustomer_test($arrInput){

		$feedback_result_select = $this->db_client->select(Tool_Util::getStoreTable('user_feedback'),
			'feedback_id, time, rate, maintenance_id, user_id','time>="2015-03-20 20:00:00"',NULL,NULL
			);
		$user_id_list = array();
		$maintenance_id_list = array();
		foreach($feedback_result_select as $feedback_info){
			$user_id = $feedback_info["user_id"];
			array_push($user_id_list,$user_id);
			$maintenance_id = $feedback_info["maintenance_id"];
			array_push($maintenance_id_list,$maintenance_id);
		}
		$user_id_list_str = implode(',',$user_id_list);
		$maintenance_id_list_str = implode(',',$maintenance_id_list);
		//var_dump($feedback_result_select);
		//var_dump($user_id_list_str);



		$user_info_dict = array();
		$user_result_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
			'user_id, name, phone_num','user_id in ('.$user_id_list_str.')',NULL,NULL
		);
		foreach($user_result_select as $user_info){
			$user_id = $user_info["user_id"];
			$user_info_dict[$user_id] = $user_info;
		}
		//var_dump($user_result_select);
		//print_r($user_result_select);



		/*
		$maintenance_info_dict = array();
		$maintenance_result_select = $this->db_client->select(Tool_Util::getStoreTable('maintenance_info'),
			'maintenance_id, create_time','maintenance_id in ('.$maintenance_id_list_str.')',NULL,NULL
		);
		foreach($maintenance_result_select as $maintenance_info){
			$maintenance_id = $maintenance_info["maintenance_id"];
			$maintenance_info_dict[$maintenance_id] = $maintenance_info;
		}
		*/



		$maintenance_service_dict = array();
		$service_id_list = array();
		$maintenance_service_result_select = $this->db_client->select(Tool_Util::getStoreTable('maintenance_service'),
			'maintenance_id, service_id, create_time','maintenance_id in ('.$maintenance_id_list_str.')',NULL,NULL
		);
		foreach($maintenance_service_result_select as $maintenance_service_info){
			array_push($service_id_list,$maintenance_service_info["service_id"]);
			$maintenance_id = $maintenance_service_info["maintenance_id"];
			if (!isset($maintenance_service_dict[$maintenance_id])){
				$maintenance_service_dict[$maintenance_id] = array();
			}
			array_push( $maintenance_service_dict[$maintenance_id] , $maintenance_service_info );
		}
		$service_id_list_str = implode(',',$service_id_list);



		$service_info_dict = array();
		$service_result_select = $this->db_client->select(Tool_Util::getStoreTable('service_info'),
			'service_id, name','service_id in ('.$service_id_list_str.')',NULL,NULL
		);
		foreach($service_result_select as $service_info){
			$service_id = $service_info["service_id"];
			$service_info_dict[$service_id] = $service_info;
		}



		$feedback_data = array();
		foreach($feedback_result_select as $feedback_info){
			$feedback_id = $feedback_info["feedback_id"];
			$user_id = $feedback_info["user_id"];
			$maintenance_id = $feedback_info["maintenance_id"];
			$feedback_time = $feedback_info["time"];
			$feedback_rate = $feedback_info["rate"];
			$name = "";
			$phone_num ="";
			$maintenance_poj = "";
			$service_time = "";
			if (isset($user_info_dict[$user_id]))
			{
				$name = $user_info_dict[$user_id]["name"];
				$phone_num = $user_info_dict[$user_id]["phone_num"];
			}
			if (isset($maintenance_service_dict[$maintenance_id]))
			{
				$service_item_list = array();
				foreach($maintenance_service_dict[$maintenance_id] as $service_info){
					$service_time = $service_info["create_time"];
					$service_id = $service_info["service_id"];
					if (isset($service_info_dict[$service_id]))
					{
						array_push($service_item_list,$service_info_dict[$service_id]["name"]);
					}
				}
				$service_time_tmp = $maintenance_service_dict[$maintenance_id]["create_time"];
			}
			$service_time = $service_time_tmp;
			$service_item_name = implode(',',$service_item_list);

			$feedback_data_item = array();
			$feedback_data_item["f_time"] = $feedback_time;
			$feedback_data_item["f_name"] = $name;
			$feedback_data_item["f_phone"] = $phone_num;
			$feedback_data_item["f_item"] = $service_item_name;
			$feedback_data_item["f_s_time"] = $service_time;
			$feedback_data_item["f_rate"] = $feedback_rate;
			array_push($feedback_data,$feedback_data_item);
		}
		//var_dump($feedback_data);


			//'feedback_id, time, rate, maintenance_id','where time>="2015-08-20 20:00:00"',NULL,NULL
		//$result_select = $this->db_client->select(Tool_Util::getStoreTable('user_feedback'),
		//	'*',NULL,NULL,NULL);
		$return_arr = array(
				'result' => $feedback_data,
				'codeMsg' => "succ",
				'code' => 1
			);

		/*
		 * select user_feedback.time,user_feedback.rate,user_info.name,user_info.phone_num,maintenance_info.create_time,maintenance_info.service_id_list from user_feedback,user_info,maintenance_info where user_feedback.user_id=user_info.user_id and maintenance_info.maintenance_id= user_feedback.maintenance_id;
		 */
		/*
		$param = array(
			'fields' => array('name'),
			'conds' => array(
				'admin_id = ' => $username,
				'passwd = ' => $password
			)
		);
		//return var_dump($this->db_client);
		$result_select = $this->db_client->select('admin_info',$param['fields'],
			$param['conds'],$param['options'],$param['appends']);
		return count($result_select);

		try{

			return var_dump($result_select);
		}catch(Exception $e){
			return var_dump($e);
		}
		 */
		return $return_arr;

	}
    public function getSampleById($intId, $arrFields = null){
        return 'GoodBye World!';
    }

    public function addSample($arrFields)
    {
        return true;
    }
    
    public function updateSampleById($intId, $arrFields)
    {
        return true;
    }
    
    public function deleteSampleById($intId)
    {
        return true;
    }
    
    public function getSampleListByConds($arrConds, $arrFields, $intLimit, $intOffset, $arrOrderBy)
    {
        return true;
    }
}
