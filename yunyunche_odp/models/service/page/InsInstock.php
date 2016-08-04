<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_InsInstock {
    private $objServiceDataInsInstock;
    public function __construct(){
        $this->objServiceDataInsInstock = new Service_Data_InsInstock();
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
			$strData = $this->objServiceDataInsInstock->doInsInstock($arrInput);
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
