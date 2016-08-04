<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_InsCustomer {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doCheckUser($user_id)
	{
		$result_select = $this->db_client->selectCount(Tool_Util::getStoreTable('user_info'),' user_id='.$user_id,NULL,NULL);
		return count($result_select);
	}
	public function doInsCarInfo($arrInput,$owner_id){
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



		if (isset($arrInput['series_id']))
		{
			$series_id_input = $arrInput['series_id'];
		}
		$series_id = array();
		if ($series_id_input!=NULL and $series_id_input!="")
		{
			$series_id_item = array();
			$series_id_item['series_id'] = $series_id_input;
			array_push($series_id, $series_id_item);
		}
		else if (isset($arrInput['brand']) && isset($arrInput['series']))
		{
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
		}

			$uniq_car_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('car_info'),
				' plate_number="'.$plate_number.'"',NULL,NULL);
			if ($uniq_car_select_count!=0)
			{
				$return_arr = array(
						'result' => $uniq_car_select_count,
						'codeMsg' => 'plate exist',
						'code' => 201
					);

			}
			else
			{
				$param = array(
						'plate_number' => $plate_number,
						'frame_number' => $frame_number,
						'engine_number' => $engine_number,
						'car_license_time' => $car_reg_time,
						'owner_id' => $owner_id,
						'outside_colour' => $outsideColour,
						'inside_colour' => $insideColour,
						'insurance_company' => $insuranceCompany,
						'insurance_start' => $insuranceStart,
						'insurance_end' => $insuranceEnd,
						'car_buy_time' => $carBuyTime,
						'car_reg_time' =>  date('Y-m-d H:i:s'),
					);
				if (count($series_id)>0){
					$param['series'] = $series_id[0]['series_id'];
				}
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

	public function doInsCustomer($arrInput){
		$user_name = $arrInput['name'];
		$phone_num = (int)$arrInput['phone_num'];
		$car_license_num = $arrInput['car_license_num'];
		$car_license_valid_time = $arrInput['car_license_valid_time'];
		$addr_province = $arrInput['addr_province'];
		$addr_city = $arrInput['addr_city'];
		$addr_district = $arrInput['addr_district'];
		$addr_road = $arrInput['addr_road'];

		$member_from = $arrInput['member_from'];
		if (empty($member_from)){
			$member_from = '';
		}

		$identity = $arrInput['identity'];
		if (empty($identity)){
			$identity = "";
		}
		$birthday = $arrInput['birthday'];
		if (empty($birthday)){
			$birthday = "";
		}
		$IDNum = $arrInput['ID_num'];
		if (empty($IDNum)){
			$IDNum = "";
		}
		$OtherPhone = $arrInput['other_phone'];
		if (empty($OtherPhone)){
			$OtherPhone = "";
		}
		$QQ = $arrInput['QQ'];
		if (empty($QQ)){
			$QQ = "";
		}
		$email = $arrInput['email'];
		if (empty($email)){
			$email = "";
		}
		$remark = $arrInput['remark'];
		if (empty($remark)){
			$remark = "";
		}

		//return '1234';

		
		$result_phone_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),'*',' phone_num = '.$phone_num,NULL,NULL);
		if($result_phone_select!==false and count($result_phone_select)>0)
		{
			$return_arr = array(
				'result' => $result_select,
				'user_id' => count($result_phone_select),
				'codeMsg' => '客户手机号已存在,请重新验证',
				'code' => 1
			);
			return $return_arr;
		}
		

		///*
		$param = array(
				'name' => $user_name,
				'phone_num' => $phone_num,
				'car_license_num' => $car_license_num,
				'car_license_valid_time' => $car_license_valid_time,
				'addr_province' => $addr_province,
				'addr_city' => $addr_city,
				'addr_district' => $addr_district,
				'addr_road' => $addr_road,
				'wechat_nickname' => $arrInput['wechat_nickname'],
				'gender' => $arrInput['gender'],
				'age' => $arrInput['age'],
				'reg_time' =>  date('Y-m-d H:i:s'),

				'member_from' => $member_from,
				'identity' => $identity,
				'birthday' => $birthday,
				'ID_num' => $IDNum,
				'other_phone' => $OtherPhone,
				'QQ' => $QQ,
				'email' => $email,
				'remark' => $remark,
				'member_valid_date' =>  date('Y-m-d H:i:s'),
			);
		$result_select = $this->db_client->insert(Tool_Util::getStoreTable('user_info'),$param);
		$result_insert = $this->db_client->getInsertID();
		$return_arr = array(
				'result' => $result_select,
				'user_id' => $result_insert,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;

		//return var_dump($this->db_client);
		/*
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
