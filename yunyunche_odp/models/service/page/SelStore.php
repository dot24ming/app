<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_SelStore {
    private $objServiceDataSelStore;
    public function __construct(){
        $this->objServiceDataSelStore = new Service_Data_SelStore();
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
			$strData = $this->objServiceDataSelStore->doSelStore($arrInput);
			$arrResult['data']['storeList'] = $strData['result'];
			$arrResult['data']['storeCount'] = $strData['count'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}

		return $arrResult;
    }
}
