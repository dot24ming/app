<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_FeedbackStat {

	private $db_client;

	//const TABLE = 'tblSample';
	
    public function __construct(){
		$this->db_client = new Dao_DBbase();
    }   
	public function doFeedbackStat($gt_score,$lt_score){
		$last_month_time = date("Y-m-d 00:00:01", strtotime("-1 months",time()));

		$param = array(
			'conds' => array(
				'rate >= ' => $gt_score,
				'time >=' => $last_month_time
			)
		);
		$result_select_gt = $this->db_client->selectCount(Tool_Util::getStoreTable('user_feedback'),
			$param['conds'],$param['options'],$param['appends']);

		$param = array(
			'conds' => array(
				'rate <= ' => $lt_score,
				'time >=' => $last_month_time
			)
		);
		$result_select_lt = $this->db_client->selectCount(Tool_Util::getStoreTable('user_feedback'),
			$param['conds'],$param['options'],$param['appends']);

		if($result_select_gt==0 and $result_select_lt==0)
		{
			$return_arr = array(
				'result' => array(0,0),
				'codeMsg' => 'false',
				'code' => 0
			);
		}
		else if($result_select_gt==false or $result_select_lt==false)
		{
			$return_arr = array(
				'result' => array(0,0),
				'codeMsg' => 'false',
				'code' => 201
			);
		}else{
			$return_arr = array(
				'result' => array($result_select_gt,$result_select_lt),
				'codeMsg' => 'succ',
				'code' => 0
			);
		}

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
