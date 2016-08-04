<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_SelCustomer {
    private $objServiceDataSelCustomer;
    public function __construct(){
        $this->objServiceDataSelCustomer = new Service_Data_SelCustomer();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
	public function execute_l($arrInput){
        $arrResult = array();
		$arrResult['errno'] = 0;
		try{
			$strData = $this->objServiceDataSelCustomer->doSelCustomer_l($arrInput);
			$arrResult['data']['user'] = $strData['result'];
			$arrResult['data']['user_count'] = $strData['count'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];
		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		return $arrResult;
	}
    public function execute($arrInput){
        Bd_Log::debug('Logcheck page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
	   
 				
		try{
			$strData = $this->objServiceDataSelCustomer->doSelCustomer($arrInput);
			$arrResult['data']['customer'] = $strData['result'];
			$arrResult['data']['customer_count'] = $strData['count'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}

	

		//$arrResult['data2'] = "123";
		return $arrResult;
    }
}
