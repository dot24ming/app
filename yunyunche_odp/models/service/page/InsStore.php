<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_InsStore {
    private $objServiceDataInsStore;
    public function __construct(){
        $this->objServiceDataInsStore = new Service_Data_InsStore();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
    public function execute($arrInput){
        Bd_Log::debug('Logcheck page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
	   
 				
		try{
			$strData = $this->objServiceDataInsStore->doInsStore($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];
			$arrResult['info'] = $strData['codeMsg'];


		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['data'] = $e->getMessage();
			$arrResult['errno'] = $e->getCode();
			$arrResult['ret'] = $e->getCode();
			$arrResult['info'] = 'error';
		}

	

		return $arrResult;
    }
}
