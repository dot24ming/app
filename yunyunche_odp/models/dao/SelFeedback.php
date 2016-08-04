<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_SelFeedback {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doSelFeedback($arrInput){
		// sel feedback
		// sel smsid with push_history
		// sel maintenance_id with maintenance_info
		
		$last_month_time = date("Y-m-d 00:00:01", strtotime("-1 months",time()));

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;

		$limit_str = 'limit '.$start_idx.','.$offset;

		$feedback_result_select = $this->db_client->select(Tool_Util::getStoreTable('user_feedback'),
			'feedback_id, time, rate, sms_id, user_id','time>="'.$last_month_time.'"',NULL,$limit_str);
		
		$feedback_result_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('user_feedback'),
			'time>="'.$last_month_time.'"',NULL,NULL);
		$record_count = $feedback_result_select_count;
		
		/*
		if ($feedback_result_select==false){
			$return_arr = array(
				'result' => "feedback select fail"
				'codeMsg' => "fail",
				'code' => 201
			);
			return $return_arr;
		}
		*/
		/*
		if ($record_count!=false and $record_count>0)
		{
			//
		}
		*/

		$user_id_list = array();
		$sms_id_list = array();
		foreach($feedback_result_select as $feedback_info){
			$user_id = $feedback_info["user_id"];
			array_push($user_id_list,$user_id);
			$sms_id = $feedback_info["sms_id"];
			array_push($sms_id_list,$sms_id);
		}
		$user_id_list_str = implode(',',$user_id_list);
		$sms_id_list_str = implode(',',$sms_id_list);
		//var_dump($feedback_result_select);
		//var_dump($user_id_list_str);
		

		$user_info_dict = array();
		$user_result_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
			'user_id, name, phone_num','user_id in ('.$user_id_list_str.')',NULL,NULL
		);
		if($user_result_select==false){
			//todo
		}
		foreach($user_result_select as $user_info){
			$user_id = $user_info["user_id"];
			$user_info_dict[$user_id] = $user_info;
		}
		//var_dump($user_result_select);
		//print_r($user_result_select);


		$sms_2_maintenance = array();
		$maintenance_id_list = array();
		$sms_result_select = $this->db_client->select(Tool_Util::getStoreTable('push_history'),
			'maintenance_id,sms_id',' sms_id in ('.$sms_id_list_str.')',NULL,NULL);
		foreach($sms_result_select as $sms_info)
		{
			$sms_id = $sms_info["sms_id"];
			$maintenance_id = $sms_info["maintenance_id"];
			array_push($maintenance_id_list,$maintenance_id);
			if (!isset($sms_2_maintenance[$sms_id])){
				$sms_2_maintenance[$sms_id] = array();
			}
			$sms_2_maintenance[$sms_id][$maintenance_id] = 0;
		}
		$maintenance_id_list_str = implode(',',$maintenance_id_list);
		//var_dump($maintenance_id_list_str);

		// todo name = service_id
		$maintenance_service_dict = array();
		$service_id_list = array();
		$maintenance_service_result_select = $this->db_client->select(Tool_Util::getStoreTable('maintenance_service'),
			'maintenance_id, name, create_time','maintenance_id in ('.$maintenance_id_list_str.')',NULL,NULL
		);
		if($maintenance_service_result_select==false){
			//todo
		}
		foreach($maintenance_service_result_select as $maintenance_service_info){
			array_push($service_id_list,$maintenance_service_info["name"]);
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

		//var_dump($maintenance_service_dict);
		//var_dump($service_result_select);


		$feedback_data = array();
		foreach($feedback_result_select as $feedback_info){
			$feedback_id = $feedback_info["feedback_id"];
			$user_id = $feedback_info["user_id"];
			$sms_id = $feedback_info["sms_id"];
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
			if (isset($sms_2_maintenance[$sms_id]))
			{
				$maintenance_id_list = array_keys($sms_2_maintenance[$sms_id]);
				foreach($maintenance_id_list as $maintenance_id)
				{
					//var_dump($maintenance_id);
					//var_dump($maintenance_service_dict);
					if (isset($maintenance_service_dict[$maintenance_id]))
					{
						$service_item_list = array();
						foreach($maintenance_service_dict[$maintenance_id] as $service_info){
							//var_dump($service_info);
							$service_time = $service_info["create_time"];
							//todo name=service_id
							$service_id = $service_info["name"];
							if (isset($service_info_dict[$service_id]))
							{
								array_push($service_item_list,$service_info_dict[$service_id]["name"]);
							}
						}
						//$service_time_tmp = $maintenance_service_dict[$maintenance_id]["create_time"];
					}
					//$service_time = $service_time_tmp;
					$service_item_name = implode(',',$service_item_list);

					$feedback_data_item = array();
					$feedback_data_item["f_time"] = $feedback_time;
					$feedback_data_item["f_name"] = $name;
					$feedback_data_item["f_phone"] = $phone_num;
					$feedback_data_item["f_item"] = $service_item_name;
					$feedback_data_item["f_s_time"] = $service_time;
					if ($feedback_rate>=3){
						$feedback_rate_str = "好评";
					}
					else{
						$feedback_rate_str = "差评";
					}
					$feedback_data_item["f_rate"] = $feedback_rate_str;
					array_push($feedback_data,$feedback_data_item);

				}
			}

		}
		//var_dump($feedback_data);

		$return_arr = array(
				'result' => $feedback_data,
				'codeMsg' => "succ",
				'code' => 0,
				'count' => $record_count
			);
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
