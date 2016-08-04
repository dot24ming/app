<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_MatchSupplier {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doMatchSupplier($arrInput){

		$name = $arrInput["name"];

		$result_select = $this->db_client->select(Tool_Util::getStoreTable('supplier_info'),'*',
			' supplier_name like "'.$name.'%"',NULL,NULL);

		$return_arr = array(
				'result' => $result_select,
				'codeMsg' => "succ",
				'code' => 0
			);
		return  $return_arr;
	}
	public function doSelCustomer($arrInput){

		$name = $arrInput['user_name'];
		$phone_num = $arrInput['phone_number'];
		$plate_number = $arrInput['plate_number'];

		//////////////////////
		//select * from car_info,user_info where ( user_info.name="wuweijia" or user_info.phone_num="13033" or car_info.plate_number="123123") and user_info.user_id=car_info.owner_id;

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
				'car_info.car_reg_time','car_model.brand','car_model.series'),
			'conds' => array(
					'('.$conds_str.') and user_info.user_id=car_info.owner_id and car_info.model=car_model.model_id'
				)
			);
		$result_select = $this->db_client->select(
			Tool_Util::getStoreTable('user_info') . ', ' . Tool_Util::getStoreTable('car_info') . ', ' .Tool_Util::getStoreTable('car_model'), $param['fields'],
			$param['conds'],$param['options'],$param['appends']);

		////////////////////////

		$return_arr = array(
				'result' => $result_select,
				'codeMsg' => var_dump($result_select),
				'code' => 1
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
