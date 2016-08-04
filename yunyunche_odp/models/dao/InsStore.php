<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_InsStore extends Dao_Base{

	private $db_client;

    public function __construct(){
		parent::__construct();
		$this->db_client = new Dao_DBbase();
    }   
	public function doInsStore($arrInput){

		$new_tables = array(
			'admin_info',
			'admin_permission',
			'after_sale_push',
			'car_illegal_info',
			'car_info',
			'department',
			'insurance_push',
			'licence_push',
			'maintenance_info',
			'maintenance_service',
			'page_authority',
			'push_history',
			'service_detail',
			'service_info',
			'service_type',
			'traffic_peccancy_push',
			'user_feedback',
			'user_info',
			'verification_push',
		);
		$address_model_table = "address_model";

		$store_id = Tool_Const::$storeId;

		$name = $arrInput['store_name'];
		$contact_user = $arrInput['contact_user'];
		$phone = $arrInput['phone'];
		$email = $arrInput['email'];
		$addr_road = $arrInput['address'];
		$status = $arrInput['status'];
		$admin_account = $arrInput['admin_account'];
		$password = $arrInput['password'];
		
		$param = array(
				'name' => $name,
				'contact_user' => $contact_user,
				'phone' => $phone,
				'email' => $email,
				'addr_road' => $addr_road,
				'status' => $status,
				'admin_account' => $admin_account,
				'password' => $password
			);
		$result_insert = $this->db_client->insert('store_info',$param);
		$result_insert_id = $this->db_client->getInsertID();
		
		// if add succ create table
		// TODO
		if($result_insert!=false && $result_insert_id!=false)
		{
			if($result_insert_id>0)
			{
				$store_id = $result_insert_id;
				foreach($new_tables as $table_name)
				{
					$sql = 'CREATE TABLE '.$store_id.'_'.$table_name.' LIKE '.$table_name.';';
					try {
						$ret = $this->query($sql);
					}catch(Exception $e){
						Bd_Log::warning($e->getMessage());
					}

				}
			}
			else
			{
			}
		}
		else
		{
		}

		$return_arr = array(
				'result' => $ret,
				'codeMsg' => 'succ',
				'code' => 0
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
