<?php 
class Dao_PushHistory extends Dao_Base {
	const TABLE = 'push_history';
	static $table = '';

	public function __construct($storeId) {
		parent::__construct();		
		self::$table = Tool_Util::getStoreTable(self::TABLE);
	}

	public function addRecord($number, $content, $type) {
		$row = array(
            'phone_num' => $number,
            'content' => $content,
            'time' => date('Y-m-d H:i:s', time()),
			'type' => $type,
        );      
        $ret = $this->objDB->insert(self::$table, $row);  
        if ($ret == false) {
            $error = $this->objDB->error();
            $errno = $this->objDB->errno();
            return array('error' => $error, 'errno' => $errno);
        } else {
            return true;
        }  		
	}
}
