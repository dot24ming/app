<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_SelCustomer {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   


	public function doDelCar($arrInput){
		//update car info with plate_number
		$plate_number = $arrInput["plate_number"];
		
		$car_param = array(
				'valid' => 0
			);
		$update_result = $this->db_client->update(Tool_Util::getStoreTable("car_info"),$car_param,' plate_number="'.$plate_number.'"',NULL,NULL);

		$ret_result = $update_result;
		$ret_codeMsg = 'succ';
		$ret_code = 0;

		if ($update_result==false or $update_result==NULL)
		{
			$ret_result = NULL;
			$ret_codeMsg = 'check plate_number';
			$ret_code = 101;
		}

		$return_arr = array(
				'result' => $ret_result,
				'codeMsg' => $ret_codeMsg,
				'code' => $ret_code
			);
		return  $return_arr;
	}


	public function doModCar($arrInput){
		//update car info with plate_number
		//var_dump($arrInput);
		$plate_number = '';
		$brand = '';
		$series = '';
		$frame_number = '';
		$engine_number = '';
		$car_license_time = '';
		$series_id = '';
		
		$plate_number = $arrInput["plate_number"];
		$brand = $arrInput["brand"];
		$series = $arrInput["series"];
		$frame_number = $arrInput["frame_number"];
		$engine_number = $arrInput["engine_number"];
		$car_license_time = $arrInput["car_license_time"];
		$series_id = $arrInput["series_id"];
		//$car_reg_time = $arrInput["car_reg_time"];
		
		$car_param = array(
				'series' => $series_id,
				'frame_number' => $frame_number,
				'engine_number' => $engine_number,
				'car_license_time' => $car_license_time,
				'car_reg_time' => $car_reg_time
			);
		
		$update_result = $this->db_client->update(Tool_Util::getStoreTable("car_info"),$car_param,' plate_number="'.$plate_number.'"',NULL,NULL);

		$ret_result = $update_result;
		$ret_codeMsg = 'succ';
		$ret_code = 0;

		if ($update_result==false or $update_result==NULL)
		{
			$ret_result = NULL;
			$ret_codeMsg = 'check plate_number';
			$ret_code = 101;
		}

		$return_arr = array(
				'result' => $ret_result,
				'codeMsg' => $ret_codeMsg,
				'code' => $ret_code
			);

		return  $return_arr;
	}


	public function doModCar2($arrInput,$owner_id){
		$plate_number = $arrInput['plate_number'];
		$frame_number = $arrInput['frame_number'];
		if(empty($frame_number))
		{
			$frame_number='';
		}
		$engine_number = $arrInput['engine_number'];
		if(empty($engine_number))
		{
			$engine_number='';
		}
		$car_color = $arrInput['car_color'];
		if(empty($car_color))
		{
			$car_color='';
		}
		$car_reg_time = $arrInput['car_reg_time'];
		$car_license_time = $arrInput['car_reg_time'];
		if(empty($car_license_time))
		{
			$car_license_time='';
			$car_reg_time='';
		}
		$kilometers = $arrInput['kilometers'];
		if(empty($kilometers))
		{
			$kilometers='0';
		}


		$outsideColour = $arrInput['outside_colour'];
		if (empty($outsideColour)) {
			$outsideColour = "";
		}
		$insideColour = $arrInput['inside_colour'];
		if (empty($insideColour)){
			$insideColour = "";
		}
		$insuranceCompany = $arrInput['insurance_company'];
		if (empty($insuranceCompany)){
			$insuranceCompany = "";
		}

		$insuranceStart = $arrInput['insurance_start'];
		if (empty($insuranceStart)){
			$insuranceStart = "";
		}

		$insuranceEnd = $arrInput['insurance_end'];
		if (empty($insuranceEnd)){
			$insuranceEnd = "";
		}

		$carBuyTime = $arrInput['car_buy_time'];

		if (empty($carBuyTime)){
			$carBuyTime = "";
		}

		$valid = $arrInput['valid'];



				$brand = $arrInput['brand'];
				$series = $arrInput['series'];

				$param = array(
						'fields' => array('series_id'),
						'conds' => array(
								'brand = ' => $brand,
								'series = ' => $series	
							)
					);

				$result_select = $this->db_client->select('car_series',$param['fields'],
					$param['conds'],$param['options'],$param['appends']);
				$series_id = $result_select;


			$uniq_car_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('car_info'),
				' plate_number="'.$plate_number.'"',NULL,NULL);
			if ($uniq_car_select_count!=0)
			{
				$param = array(
						'kilometers' => $kilometers,
						'frame_number' => $frame_number,
						'engine_number' => $engine_number,
						'car_license_time' => $car_reg_time,
						'outside_colour' => $outsideColour,
						'inside_colour' => $insideColour,
						'insurance_company' => $insuranceCompany,
						'insurance_start' => $insuranceStart,
						'insurance_end' => $insuranceEnd,
						'car_buy_time' => $carBuyTime,
						'valid' => $valid,
					);
				if (count($series_id)>0){
					$param['series'] = $series_id[0]['series_id'];
				}else {
					$param['series'] = '';
				}
				$result_update = $this->db_client->update(Tool_Util::getStoreTable('car_info'),$param,' plate_number="'.$plate_number.'"',NULL,NULL);
				if ($result_update !== false){
					$return_arr = array(
							'result' => $result_update,
							'codeMsg' => 'succ',
							'code' => 0
						);
				}else{
					$return_arr = array(
						'result' => $result_update,
						'codeMsg' => 'fail insert',
						'code' => 201
					);
				}
			}
			else
			{
				$param = array(
						'kilometers' => $kilometers,
						'plate_number' => $plate_number,
						'frame_number' => $frame_number,
						'engine_number' => $engine_number,
						'car_license_time' => $car_reg_time,
						'owner_id' => $owner_id,
						'series' => $series_id[0]['series_id'],
						'outside_colour' => $outsideColour,
						'inside_colour' => $insideColour,
						'insurance_company' => $insuranceCompany,
						'insurance_start' => $insuranceStart,
						'insurance_end' => $insuranceEnd,
						'car_buy_time' => $carBuyTime,
						'car_reg_time' => date('Y-m-d H:i:s'),
						'valid' => $valid,
					);
				$result_select = $this->db_client->insert(Tool_Util::getStoreTable('car_info'),$param);
				if ($result_select != false){
					$return_arr = array(
							'result' => $result_select,
							'codeMsg' => 'succ',
							'code' => 0
						);
				}else{
					$return_arr = array(
						'result' => $result_select,
						'codeMsg' => 'fail insert',
						'code' => 201
					);
				}
			}
		return $return_arr;
	}




	public function doModCustomer($arrInput){
		//todo
		//安全性检查?????
		//update user name,phone with user_id

		$user_id = $arrInput["user_id"];
		//$series_id = $arrInput["series_id"];

		$name = $arrInput["name"];
		$phone_num = $arrInput["phone_num"];
		$car_license_num = $arrInput["car_license_num"];
		$car_license_valid_time = $arrInput["car_license_valid_time"];
		$wechat_nickname = $arrInput['wechat_nickname'];
		$gender = $arrInput['gender'];
		$email = $arrInput['email'];
		
		$user_param = array(
				'name' => $name,
				'phone_num' => $phone_num,
				'car_license_num' => $car_license_num,
				'car_license_valid_time' => $car_license_valid_time,
				'wechat_nickname' => $wechat_nickname,
				'gender' => $gender,
				'email' => $email,
			);
		$update_result = $this->db_client->update(Tool_Util::getStoreTable("user_info"),$user_param,' user_id='.$user_id,NULL,NULL);

		$ret_result = $update_result;
		$ret_codeMsg = 'succ';
		$ret_code = 0;

		if ($update_result===false or $update_result===NULL)
		{
			$ret_result = NULL;
			$ret_codeMsg = 'check plate_number';
			$ret_code = 101;

			$return_arr = array(
				'result' => $ret_result,
				'codeMsg' => $ret_codeMsg,
				'code' => $ret_code
			);
			return  $return_arr;
		}

		

		if(isset($arrInput['cars']))
		{
			//var_dump($arrInput['cars']);
			$car_list = json_decode($arrInput['cars'],true);
			//var_dump($car_list);
			foreach($car_list as $car_info)
			{
				$mod_car_return = $this->doModCar2($car_info,$user_id);
				//var_dump($car_info);
				//var_dump($mod_car_return);
				//var_dump($mod_car_return);
				if($mod_car_return['code']!==0)
				{
					$ret_result = $mod_car_return;
					$ret_codeMsg = "mod car error";
					$code = $mod_car_return['code'];
				}
			}
		}
		

		$return_arr = array(
				'result' => $ret_result,
				'codeMsg' => $ret_codeMsg,
				'code' => $ret_code
			);
		return  $return_arr;
	}
	public function doSelCustomer_l($arrInput){

		$name = $arrInput["user_name"];
		$phone_num = $arrInput["phone_number"];

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx ;
		$limit_str = 'limit '.$start_idx.','.$offset;


		$conds_array = array();
		if ($name!=NULL and $name!="")
		{
			array_push($conds_array,'name="'.$name.'"');
		}
		if ($phone_num!=NULL and $phone_num!="")
		{
			array_push($conds_array,'phone_num="'.$phone_num.'"');
		}
		$conds_str = implode('or',$conds_array);

		$result_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),'user_id,name,phone_num,addr_province,addr_city,addr_district,addr_road,car_license_num,car_license_valid_time, identity, birthday, ID_num, other_phone, QQ, email, remark',
			$conds_str,NULL,$limit_str);
		$result_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('user_info'),$conds_str,NULL,NULL);


		$return_arr = array(
				'result' => $result_select,
				'codeMsg' => "succ",
				'code' => 0,
				'count' => $result_select_count
			);
		return  $return_arr;
	}
	public function doSelCustomer($arrInput){

		$name = $arrInput['user_name'];
		$phone_num = $arrInput['phone_number'];
		$plate_number = $arrInput['plate_number'];

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx ;
		$limit_str = 'limit '.$start_idx.','.$offset;

		// select car with plate_number, get owner_id and car_info
		$car_series_id_dict = array();
		$car_dict = array();
		$car_conds_array = array();
		$owner_id_list = array();
		$owner_id_dict = array();
		if ($plate_number!=NULL and $plate_number!="")
		{
			$car_select_result = $this->db_client->select(Tool_Util::getStoreTable('car_info'),
				'*'," plate_number like '%".$plate_number."%' and valid=1",NULL,NULL);
				//'*'," binary plate_number like '%".$plate_number."%' and valid=1",NULL,NULL);
				//'plate_number,series,owner_id,frame_number,engine_number,car_license_time,car_reg_time, outside_colour, inside_colour, insurance_company, insurance_start, insurance_end, car_buy_time',' plate_number="'.$plate_number.'" and valid=1',NULL,NULL);
			foreach($car_select_result as $car_info){
				$plate_number = $car_info["plate_number"];
				$owner_id = $car_info["owner_id"];
				$series = $car_info["series"];
				array_push($owner_id_list,$owner_id);
				$car_series_id_dict[$series] = 0;
				if (!isset($owner_id_dict[$owner_id])){
					$owner_id_dict[$owner_id] = array();
				}
				$owner_id_dict[$owner_id][$plate_number] = $car_info;
				$car_dict[$plate_number] = $car_info;
			}
		}
		// select user with name,phone_num,owner_id, get user_info and new_car_id
		$user_conds_array = array();
		if ($name!=NULL and $name!="")
		{
			array_push($user_conds_array,' name like'."'%".$name."%'");
		}	
		if ($phone_num!=NULL and $phone_num!="")
		{
			array_push($user_conds_array," phone_num like '%".$phone_num."%'");
		}
		if (count($owner_id_list)>0)
		{
			$owner_id_str = implode(',',$owner_id_list);
			array_push($user_conds_array,' user_id in ('.$owner_id_str.')');
		}
		$user_dict = array();
		$user_count = 0;
		if (count($user_conds_array)>0)
		{

			$user_conds_str = implode('or',$user_conds_array);
			$user_select_result = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
				'*',$user_conds_str,NULL,'order by reg_time desc '.$limit_str);
				//'user_id,name,phone_num,car_license_num,car_license_valid_time,identity, birthday, ID_num, other_phone, QQ, email, remark',$user_conds_str,NULL,$limit_str);
			$user_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('user_info'),$user_conds_str,NULL,NULL);
			$user_count = $user_select_count;
			foreach($user_select_result as $user_info){
				$user_id = $user_info["user_id"];
				$user_dict[$user_id] = $user_info;
			}
		}
		else
		{
			//var_dump($plate_number);
			if (($name==NULL or $name=='') and ($phone_num==NULL or $phone_num=='') and ($plate_number==NULL or $plate_number==''))
			{
				// TODO select *
				$user_select_result = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
					'*',NULL,NULL,'order by reg_time desc '.$limit_str);
					//'user_id,name,phone_num,car_license_num,car_license_valid_time, identity, birthday, ID_num, other_phone, QQ, email, remark',NULL,NULL,$limit_str);
				$user_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('user_info'), NULL,NULL,NULL);
				$user_count = $user_select_count;
				foreach($user_select_result as $user_info){
					$user_id = $user_info["user_id"];
					$user_dict[$user_id] = $user_info;
				}
			}
		}

		//var_dump($user_conds_str);

		// select car with new_owner_id, get carinfo and series_id_list
		$user_id_list = array_keys($user_dict);
		$user_id_str = implode(',',$user_id_list);
		$car_select_result = $this->db_client->select(Tool_Util::getStoreTable('car_info'),
			'*',' owner_id in ('.$user_id_str.') and valid=1',NULL,NULL);
			// TODO car_info align IMPORTANT WU WEIJIA
			//'plate_number,series,owner_id,frame_number,engine_number,car_license_time,car_reg_time,  outside_colour, inside_colour, insurance_company, insurance_start, insurance_end, car_buy_time',' owner_id in ('.$user_id_str.') and valid=1',NULL,NULL);
		//var_dump($car_select_result);
		foreach($car_select_result as $car_info){
			$owner_id = $car_info["owner_id"];
			$plate_number = $car_info["plate_number"];
			$series = $car_info["series"];
			$car_series_id_dict[$series] = 0;
			if (!isset($owner_id_dict[$owner_id])){
				$owner_id_dict[$owner_id] = array();
			}
			$owner_id_dict[$owner_id][$plate_number] = $car_info;
			$car_dict[$plate_number] = $car_info;
		}
		//var_dump($owner_id_dict);

		// select series with series_id, get series_info
		$series_dict = array();
		if ($car_series_id_dict) {
			$car_series_id_str = implode(',', array_keys($car_series_id_dict) );
			$series_select_result = $this->db_client->select('car_series',
				'series_id, series, brand', ' series_id in ('.$car_series_id_str.')', NULL, NULL);
			foreach($series_select_result as $series_info){
				$series_id = $series_info["series_id"];
				$series_dict[$series_id] = $series_info;
			}
		}
		//var_dump($series_select_result);

		$ret_list = array();
		$user_id_list = array_keys($user_dict);
		foreach($user_select_result as $user_info){
			$user_id = $user_info["user_id"];
			//foreach($user_id_list as $user_id){
			$name = "";
			$phone_num ="";
			$car_license_num = "";
			$car_license_valid_time = "";

			$brand = "";
			$series = "";
			$plate_number="";
			$frame_number = "";
			$engine_number = "";
			$car_license_time = "";
			$car_reg_time = "";

			$user_info = $user_dict[$user_id];
			$name = $user_info["name"];
			$phone_num = $user_info["phone_num"];
			$car_license_num = $user_info["car_license_num"];
			$car_license_valid_time = $user_info["car_license_valid_time"];

			$ins_user = array();
			$ins_user = $user_info;
			/*
			$ins_user["p_uid"] = $user_id;
			$ins_user["p_name"] = $name;
			$ins_user["p_phone"] = $phone_num;
			$ins_user['p_wechat'] = $user_info['wechat_num'];
			$ins_user["p_license"] = $car_license_num;
			$ins_user["p_license_valid"] = $car_license_valid_time;
			//birthday, ID_num, other_phone, QQ, email, remark
			$ins_user["p_birthday"] = $user_info["birthday"];
			$ins_user["p_ID_num"] = $user_info["ID_num"];
			$ins_user["p_other_phone"] = $user_info["other_phone"];
			$ins_user["p_QQ"] = $user_info["QQ"];
			$ins_user["p_email"] = $user_info["email"];
			$ins_user["p_remark"] = $user_info["remark"];
			$ins_user['p_member_card_balance'] = $user_info['member_card_balance'];
			*/
			$ins_user["cars"] = array();
			//var_dump($owner_id_dict[$user_id]);
			if(isset($owner_id_dict[$user_id])){
				$plate_number_list = array_keys($owner_id_dict[$user_id]);
				foreach($plate_number_list as $tmp_plate_number)
				{
					$brand = "";
					$series = "";
					$plate_number="";
					$frame_number = "";
					$engine_number = "";
					$car_license_time = "";
					$car_reg_time = "";
					$series_id = "";
					//outside_colour, inside_colour, insurance_company, insurance_start, insurance_end, car_buy_time
					$outside_colour = "";
					$inside_colour = "";
					$insurance_company = "";
					$insurance_start = "";
					$insurance_end = "";
					$car_buy_time = "";
					if(isset($car_dict[$tmp_plate_number]))
					{
						$plate_number = $tmp_plate_number;
						$frame_number = $car_dict[$tmp_plate_number]["frame_number"];
						$engine_number = $car_dict[$tmp_plate_number]["engine_number"];
						$car_license_time = $car_dict[$tmp_plate_number]["car_license_time"];

						$car_reg_time = $car_dict[$tmp_plate_number]["car_reg_time"];
						$outside_colour = $car_dict[$tmp_plate_number]["outside_colour"];
						$inside_colour = $car_dict[$tmp_plate_number]["inside_colour"];
						$insurance_company = $car_dict[$tmp_plate_number]["insurance_company"];
						$insurance_start = $car_dict[$tmp_plate_number]["insurance_start"];
						$insurance_end = $car_dict[$tmp_plate_number]["insurance_end"];
						$car_buy_time = $car_dict[$tmp_plate_number]["car_buy_time"];

						$series_id =  $car_dict[$tmp_plate_number]["series"];
						if(isset($series_dict[$series_id])){
							$brand = $series_dict[$series_id]["brand"];
							$series = $series_dict[$series_id]["series"];
						}
					}
					$ins_item = array();
					$ins_item = $car_dict[$tmp_plate_number];
					$ins_item['series_id'] = $series_id;
					$ins_item['series'] = $series;
					$ins_item['brand'] = $brand;
					$ins_item['plate'] = $plate_number;


					/*
					//$ins_item["p_uid"] = $user_id;
					$ins_item["p_series_id"] = $series_id;
					$ins_item["p_plate"] = $plate_number;
					//$ins_item["p_name"] = $name;
					//$ins_item["p_phone"] = $phone_num;
					$ins_item["p_brand"] = $brand;
					$ins_item["p_series"] = $series;
					$ins_item["p_frame"] = $frame_number;
					$ins_item["p_engine"] = $engine_number;
					$ins_item["p_license"] = $car_license_time;
					$ins_item["p_reg"] = $car_reg_time;
					//outside_colour, inside_colour, insurance_company, insurance_start, insurance_end, car_buy_time
					$ins_item["p_outside_colour"] = $outside_colour;
					$ins_item["p_inside_colour"] = $inside_colour;
					$ins_item["p_insurance_start"] = $insurance_company;
					$ins_item["p_insurance_start"] = $insurance_start;
					$ins_item["p_insurance_end"] = $insurance_end;
					$ins_item["p_car_buy_time"] = $car_buy_time;
					*/

					//array_push($ret_list,$ins_item);
					array_push($ins_user["cars"],$ins_item);
				}
			}
			array_push($ret_list,$ins_user);
			
		}


		/*
		$conds_str = '';
		$name_conds_str = '';
		$phone_conds_str = '';
		$plate_conds_str = '';
		$conds_array = array();
		if ($name!=""){
			$name_conds_str = ' user_info.name = "'.$name.'" ';
			array_push($conds_array,$name_conds_str);
		}
		if ($phone_num!=""){
			$phone_conds_str = ' user_info.phone_num = "'.$phone_num.'" ';
			array_push($conds_array,$phone_conds_str);
		}
		if ($plate_number!=""){
			$plate_conds_str = ' car_info.plate_number = "'.$plate_number.'" ';
			array_push($conds_array,$plate_conds_str);
		}
		$conds_str = implode('or',$conds_array);

		$param = array(
			'fields' => array('user_info.name','user_info.phone_num','user_info.user_id','car_info.plate_number','car_info.frame_number','car_info.engine_number',
				'car_info.car_reg_time','car_series.brand','car_series.series'),
			'conds' => array(
					'('.$conds_str.') and user_info.user_id=car_info.owner_id and car_info.series=car_series.series_id'
				)
			);
		//var_dump($param);
		$result_select = $this->db_client->select(Tool_Util::getStoreTable('user_info').','.Tool_Util::getStoreTable('car_info').', car_series',$param['fields'],
			$param['conds'],$param['options'],$limit_str);
		*/

		/*
		foreach($result_select as $user_basic_info){
			$user_id = $user_basic_info['user_id'];
			$param = array(
					'fields' => array('plate_number','model','frame_number','engine_number','car_reg_time',''),
					'conds' => array(
						'owner_id = ' => $user_id
						)
				);
			$result_select = $this->db_client->select('car_info',$param['fields'],
				$param['conds'],$param['options'],$param['appends']);
		}
		*/

		////////////////////////

		$return_arr = array(
				'result' => $ret_list,
				'count' => $user_count,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return  $return_arr;

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
		*/
		/*
		try{

			return var_dump($result_select);
		}catch(Exception $e){
			return var_dump($e);
		}
		*/

		//return count($result_select);
		//return $username;
		//*/
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
