<?php
/**
 * @name Service_Page_Login
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_TrackCustomer {
    private $objServiceDataTrackCustomer;
    public function __construct(){
        $this->objServiceDataTrackCustomer = new Service_Data_TrackCustomer();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
   	public function trackSale($arrInput) {
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackSale($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
	}
   	public function trackPeccancy($arrInput) {
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackPeccancy($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
	}
   	public function trackUserValid($arrInput) {
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackUserValid($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
	}
   	public function trackCarValid($arrInput) {
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackCarValid($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
	}
   	public function trackInsurance($arrInput) {
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackInsurance($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];
			$arrResult['errno'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
	}
    public function execute($arrInput){
        Bd_Log::debug('SearchCustomer page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;

		try{
			$strData = $this->objServiceDataTrackCustomer->doTrackCustomer($arrInput);
			$arrResult['data'] = $strData['result'];
			$arrResult['ret'] = $strData['code'];

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		
		return $arrResult;
    }
}
