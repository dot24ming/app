<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_SelMessage {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doSelMessage($arrInput){

		$phone_num = "";
		$from_time = "";
		$to_time = "";

		$phone_num = $arrInput["phone_number"];
		$from_time = $arrInput["from_time"];
		$to_time = $arrInput["to_time"];

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;


		$conds_array = array();
		$phone_num_conds = "";
		if ($phone_num!=NULL and $phone_num!="")
		{
			$phone_num_conds = ' phone_num="'.$phone_num.'" ';
			array_push($conds_array,$phone_num_conds);
		}
		$time_conds = "";
		if ($from_time!="" and $to_time!="")
		{
			$time_conds = ' (time>="'.$from_time.'" and time<="'.$to_time.'")';
			array_push($conds_array,$time_conds);
		}
		$conds_str = implode('or',$conds_array);

		//var_dump($conds_str);

		
		$result_select = $this->db_client->select(Tool_Util::getStoreTable('push_history'),
			'*',$conds_str,NULL,$limit_str);
		$result_select_count = $this->db_client->selectCount(Tool_Util::getStoreTable('push_history'),
			$conds_str,NULL,NULL);
		$push_history_count = $result_select_count;


		$phone_num_array = array();
		foreach($result_select as $push_item)
		{
			$phone_num = $push_item["phone_num"];
			array_push($phone_num_array,$phone_num);
		}
		$phone_num_conds = implode(',',$phone_num_array);
		$user_result_select = $this->db_client->select(Tool_Util::getStoreTable('user_info'),
			'phone_num,name','phone_num in ('.$phone_num_conds.')',NULL,NULL);
		$phone_2_name = array();
		foreach($user_result_select as $user_info)
		{
			$phone_num = $user_info["phone_num"];
			$user_name = $user_info["name"];
			$phone_2_name[$phone_num] = $user_name;
		}

		foreach($result_select as &$push_item)
		{
			$push_item["name"] = $phone_2_name[$push_item["phone_num"]];
		}


		$return_arr = array(
				'result' => $result_select,
				'codeMsg' => "succ",
				'code' => 0,
				'count' => $push_history_count
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
