<?php
class Base {
	    private $_db = null;

		    /*
			 *     * 构造函数
			 *         *
			 *             * @return  成功：  _db连接对象
			 *                 *          失败：  返回false
			 *                     **/
		    public function __construct() {
				        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
						    }
		    /*
			 *      * 插入记录
			 *           *
			 *                * @return 成功： 影响行数  失败： 返回false
			 *                    **/
		    public function insert($tbl, $row, $options=NULL, $onDup=NULL) {
				        $ret = $this->_db->insert($tbl, $row, $options, $onDup);
						        return $ret;
						    }
		    /*
			 *      * 删除记录
			 *           *
			 *                * @return 成功： 影响行数
			 *                     * 失败： 返回false
			 *                         **/
		    public function delete($tbl, $conds=NULL, $options=NULL, $appends=NULL) {
				        $ret = $this->_db->delete($tbl, $conds, $options, $appends);
						        return $ret;
						    }
		    /*
			 *      * 更新记录
			 *           *
			 *                * @return 成功： 影响行数
			 *                     * 失败： 返回false
			 *                         **/
		    public function update($tbl, $row, $conds=NULL, $options=NULL, $appends=NULL) {
				        $ret = $this->_db->update($tbl, $row, $conds, $options, $appends);
						        return $ret;
						    }

		    /*
			 *      * 查询记录
			 *           *
			 *                * @return 成功：查询结果
			 *                     * 失败： 返回false
			 *                          **/
		    public function select($tbl, $fields, $conds=NULL, $options=NULL, $appends=NULL,
				                        $fetchType=Bd_DB::FETCH_ASSOC, $bolUseResult=false) {
											        $ret = $this->_db->select($tbl, $fields, $conds, $options, $appends, $fetchType, $bolUseResult);
													        return $ret;
													    }

		    /*
			 *      * 查询记录总数
			 *           *
			 *                * @return 成功： 记录总数 66
			 *                     * 失败： 返回false
			 *                          **/
		    public function selectCount($tbl, $conds=NULL, $options=NULL, $appends=NULL) {
				        $ret = $this->_db->selectCount($tbl, $conds, $options, $appends);
						        return $ret;
						    }

		    /*
			 *      * 获取刚插入记录id
			 *           *
			 *                * @return 成功： 新记录ID
			 *                     * 失败： 返回false
			 *                          **/

		    public function getInsertID() {
				        $ret = $this->_db->getInsertID();
						        return $ret;
						    }
}
//必须使用init组件的init()函数来初始化ODP环境，否则无法使用基础库及其他组件库。
Bd_Init::init(); 
$dbTest = new Base();

$param = array(
	'fields' => array('*',),
);
$result_select = $dbTest->select('admin_info',$param['fields'],
	$param['conds'],$param['options'],$param['appends']);
var_dump($result_select);

//echo "selectCount Function Examplen";
//$result_selectCount = $dbTest->selectCount('store_info');
//var_dump($result_selectCount);

/*
$user_array = array(
	    'user_account'    => 'chenyijie',
		    'user_name'       => 'CHENYIJIE',
		);
$result_insert = $dbTest->insert('user', $user_array);
echo "insert Function Examplen";
var_dump($result_insert);

$result_getInsertID = $dbTest->getInsertID();
echo "getInsertID Function Examplen";
var_dump($result_getInsertID);

$conds = array(
	    'user_id=' => '3',
	);
$update_arr = array(
	    'user_name'   => 'CCCCCCCCCCC',
	);
$result_update = $dbTest->update('user', $update_arr, $conds);
echo "update Function Examplen";
var_dump($result_update);


$conds = array(
	    'user_id=' => '3',
	);
$result_delete = $dbTest->delete('user', $conds);
echo "delete Function Examplen";
var_dump($result_delete);
*/




?>
