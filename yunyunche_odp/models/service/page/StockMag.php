<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_StockMag {
    private $objServiceDataStockMag;
    public function __construct(){
        $this->objServiceDataStockMag = new Service_Data_StockMag();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }

	public function execute_ins_instock($arrInput){
        Bd_Log::debug('ins instock page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsInstock($arrInput);
			$arrResult['form_id'] = $strData['form_id'];
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
			$arrResult['form_id'] = NULL;
		}
		return $arrResult;
	}


	public function execute_ins_outstock_quick($arrInput){
        Bd_Log::debug('ins outstock page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsOutstock_quick($arrInput);
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



	public function execute_ins_outstock($arrInput){
        Bd_Log::debug('ins outstock page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsOutstock($arrInput);
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
	public function execute_ins_transfer($arrInput){
        Bd_Log::debug('ins transfer page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsTransfer($arrInput);
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
	public function execute_ins_inventory($arrInput){
        Bd_Log::debug('ins inventory page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsInventory($arrInput);
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


	public function execute_ins_purchase($arrInput){
        Bd_Log::debug('ins purchase page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsPurchase($arrInput);
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


	public function execute_ins_quote($arrInput){
        Bd_Log::debug('ins quote page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
 				
		try{
			$strData = $this->objServiceDataStockMag->doInsQuote($arrInput);
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
