<?php
/**
 * @name Dao_ServiceformAdd
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author ycx
 */
//Bd_Init::init();

class Dao_ServiceformAdd {

	private $db_client;

	const TABLE_FORMAT = "%s_%s";

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	
	public function getServiceCate($super_type_id){
		$param = array(
			'fields' => array('name'),
			'conds' => array(
				'super_type_id = ' => 0
			)
		);
		$return_select = $this->db_client->select(Tool_Util::getStoreTable('service_type'), $param['fields'],
		   	$param['conds'], $param['options'], $param['appends']);
        Bd_Log::debug('getServiceCate'.(json_encode($return_select)));
		return $return_select;
	}

	public function doServiceformAdd($username,$password){
		//return '1234';
		///*
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
	public function searchServiceform($car_no, $begin_time, $end_time){
		$maintenance_info = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, 'maintenance_info');
		Bd_Log::debug('dao'.$car_no.$begin_time.$end_time.$maintenance_info);
		$param = array(
			'fields' => array(
				'maintenance_id',
				'car_no',
				'all_charge',
				'remark',
				'create_time',
				'cashier'
			),
			'conds' => array(
				'create_time > ' => $begin_time,
				'create_time < ' => $end_time
				)
			);
		if ($car_no != ""){
			$param['conds']['car_no'] = $car_no;
		}
		Bd_Log::debug('dao'.json_encode($param));
		$return_info = $this->db_client->select($maintenance_info, $param['fields'],
		   	$param['conds'], $param['options'], $param['appends']);
		Bd_Log::debug('dao'.(json_encode($return_info)).$return_info);
		$maintenance_service = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, 'maintenance_service');
		$maintenance_ids = "";
		for($i=0; $i < count($return_info); $i++){
			$maintenance_ids = $return_info[$i]['maintenance_id'];
			Bd_Log::debug('maintenance_ids'.$maintenance_ids);
			$param = array(
				'fields' => array(
					'base',
					'category',
					'name',
					'baseName',
					'categoryName',
					'nameName',
					'price',
					'operator',
					'remark'
				),
				'conds' => array(
					'maintenance_id = ' => $maintenance_ids
				)
			);
			$result_service = $this->db_client->select($maintenance_service, $param['fields'],
				$param['conds'], $param['options'], $param['appends']);
			$return_info[$i]['project'] = array();
			$return_info[$i]['project'] = $result_service;
			Bd_Log::debug('service'.(json_encode($result_service)));
		}
		Bd_Log::debug('service'.(json_encode($return_info)));
		return $return_info;
	}
    public function getServiceformAddById($intId, $arrFields = null){
        return 'GoodBye World!';
    }

    public function addServiceformAdd($arrFields)
    {
		$param = array(
				'service_id_list ' => "001,002,003",
				'service_charge ' => 1300,
				'other_charge ' => 0,
				'car_num' => '粤B 58668',
				'operator_id' => '005',
				'create_time' => time(),
				'all_charge' => 2000
		);
		$result_select = $this->db_client->insert(Tool_Util::getStoreTable('maintenance_info'), $param);
        return true;
    }

	public function submitMaintan($arrFields)
	{
        Bd_Log::debug('add_serviceform dao service called');
		$maintenance_info = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, 'maintenance_info');
		Bd_Log::debug("table".$maintenance_info.json_encode($arrFields));
		$result_select = $this->db_client->insert($maintenance_info, $arrFields);
		$insert_id = $this->db_client->getInsertID();
		return $insert_id;
	}
	public function submitMaintanService($arrFields)
	{
		$maintenance_service = sprintf(self::TABLE_FORMAT, Tool_Const::$storeId, 'maintenance_service');
		$result_select = $this->db_client->insert($maintenance_service, $arrFields);
		return $result_select;
	}
    
    public function updateServiceformAddById($intId, $arrFields)
    {
        return true;
    }
    
    public function deleteServiceformAddById($intId)
    {
        return true;
    }
    
    public function getServiceformAddListByConds($arrConds, $arrFields, $intLimit, $intOffset, $arrOrderBy)
    {
        return true;
    }

}
