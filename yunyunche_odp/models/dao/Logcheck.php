<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_Logcheck {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doLogcheck($username,$password){
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
