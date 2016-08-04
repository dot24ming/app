<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_ModStore {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doModStatus($arrInput)
	{
		$store_id = $arrInput["store_id"];

		$select_status_result = $this->db_client->select('store_info','status',' store_id='.$store_id,NULL,NULL);

		if($select_status_result!=false and count($select_status_result)>0)
		{
			$store_status = $select_status_result[0]['status'];
			$new_status = ($store_status+1)%2;
			$update_param = array(
					'status' => $new_status
				);
			$update_status_result = $this->db_client->update('store_info',$update_param,' store_id='.$store_id,NULL,NULL);	
		}

		$return_arr = array(
				'result' => $new_status,
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
