<?php
/**
 * @name Dao_ServiceformAdd
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author ycx
 */
//Bd_Init::init();

class Dao_PhoneMsg {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	
	public function phoneMsgSubmit($mobile, $data, $type){

		$param = array(
			'phone_num' => $mobile,
			'content' => $data,
			'time' => date(),
			'type' => $type
			);
		$result_select = $this->db_client->insert(Tool_Util::getStoreTable('push_history'), $param);
		return result_select;
	}

}
