<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_Feedback {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doFeedback($arrInput){

		$result_select = $this->db_client->select('user_feedback',
			'*',NULL,NULL,NULL);
		$return_arr = array(
				'result' => $result_select,
				'codeMsg' => "succ",
				'code' => 1
			);
		/*
		$param = array(
			'conds' => array(
				'rate >= ' => $gt_score,
			)
		);
		$result_select_gt = $this->db_client->selectCount('user_feedback',
			$param['conds'],$param['options'],$param['appends']);

		$param = array(
			'conds' => array(
				'rate <= ' => $lt_score,
			)
		);
		$result_select_lt = $this->db_client->selectCount('user_feedback',
			$param['conds'],$param['options'],$param['appends']);

		$return_arr = array(
				'result' => array($result_select_gt,$result_select_lt),
				'codeMsg' => 'succ',
				'code' => 1
			);
		*/
		return $return_arr;

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
