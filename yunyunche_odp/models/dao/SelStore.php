<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_SelStore {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doSelStore($arrInput){

		$start_idx = $arrInput["start_idx"];
		$end_idx = $arrInput["end_idx"];
		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;

		$store_select = $this->db_client->select('store_info',
			'*',NULL,NULL,$limit_str);
		$store_select_count = $this->db_client->selectCount('store_info',
			NULL,NULL,NULL);

		//var_dump($store_select);
		//var_dump($store_select_count);
		$store_ret_data = array();
		//$store_ret_data["storeList"] = array();
		//$store_ret_data["storeCount"] = $store_select_count;
		foreach($store_select as $store_info)
		{
			$name = $store_info["name"];
			$contact_user = $store_info["contact_user"];
			$phone = $store_info["phone"];
			$email = $store_info["email"];
			$addr_road = $store_info["addr_road"];
			$admin_account = $store_info["admin_account"];
			$status = $store_info["status"];
			$store_id = $store_info["store_id"];

			$ins_item = array();
			$ins_item["store_name"] = $name;
			$ins_item["name"] = $contact_user;
			$ins_item["telphone"] = $phone;
			$ins_item["email"] = $email;
			$ins_item["address"] = $addr_road;
			$ins_item["admin_name"] = $admin_account;
			$ins_item["status"] = $status;
			$ins_item["store_id"] = $store_id;

			array_push($store_ret_data,$ins_item);
		}

		$return_arr = array(
				'result' => $store_ret_data,
				'count' => $store_select_count,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return  $return_arr;

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
