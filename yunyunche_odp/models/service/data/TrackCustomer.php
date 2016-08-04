<?php
/**
 * @name Service_Data_Logcheck
 * @desc sample data service, 按主题组织数据, 提供细粒度数据接口
 * @author 吴伟佳 
 */
class Service_Data_TrackCustomer {
    private $objDaoTrackCustomer;
    public function __construct(){
        $this->objDaoTrackCustomer = new Dao_TrackCustomer();
    }
     //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }   

	public function isExist($intId){
		if($intId > 0 && $intId < 100){
			return true;
		}
		return false;
	}

	public function doTrackSale($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackSale($arrInput);
		return $strData;
	}
	public function doTrackPeccancy($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackPeccancy($arrInput);
		return $strData;
	}
	public function doTrackUserValid($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackUserValid($arrInput);
		return $strData;
	}
	public function doTrackCarValid($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackCarValid($arrInput);
		return $strData;
	}

	public function doTrackInsurance($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackInsurance($arrInput);
		return $strData;
	}
	public function doTrackCustomer($arrInput){
		$strData = $this->objDaoTrackCustomer->doTrackCustomer($arrInput);
		return $strData;
	}
    
    public function getSample($intId){
        Bd_Log::debug("sample data service getSample called");
        $strData =  $this->objDaoSample->getSampleById($intId);
        return $strData;
    }

    public function addSample($strData){
        Bd_Log::debug("sample data service submitSample called");
        $arrFields = array('data'=>$strData);
        return $this->objDaoSample->addSample($arrFields);
    }
    
    public function callOtherApp($intId){
    	//跨子系统调用,这里调用自己作为示例
        $arrRet = Saf_Api_Server::call('yunyunche_odp','getSample', array('id' => $intId), null, null);
        if(false === $arrRet) {//异常逻辑处理       
                $arrErrorCodes = Saf_Api_Server::getLastError();
                $arrErrNo = array_keys($arrErrorCodes);
                $intErrNo  = $arrErrNo[0];
                $strErrMsg = $arrErrorCodes[$intErrNo];
                if($intErrNo == Saf_Api_Server:: METHOD_FAILED){
                                $intErrNo = Saf_Api_Server:: getServiceError();
                }
                Bd_Log::warning($strErrMsg, $intErrNo, $arrParams);
                return false;
        }else{ //获取数据成功，正常逻辑处理           
                return $arrRet['data'];
        }
    }
}
