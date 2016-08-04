<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_Logcheck {
    private $objServiceDataLogcheck;
    public function __construct(){
        $this->objServiceDataLogcheck = new Service_Data_Logcheck();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
    public function execute($arrInput){
        Bd_Log::debug('Logcheck page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
	   
		try{
			$userName = $arrInput['username'];
			$password = $arrInput['password'];

			$strData = $this->objServiceDataLogcheck->doLogcheck($userName,$password);
			return $strData;
		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}

		return $arrResult;
    }
}
