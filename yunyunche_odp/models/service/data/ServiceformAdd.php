<?php
/**
 * @name Service_Data_Logcheck
 * @desc sample data service, 按主题组织数据, 提供细粒度数据接口
 * @author yincunxang
 */
class Service_Data_ServiceformAdd {
    private $objDaoServiceformAdd;
    public function __construct(){
        $this->objDaoServiceformAdd = new Dao_ServiceformAdd();
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

	public function submitServiceform($arr){

		return false;
	}

	public function getServiceCate($super_type_id){
		$strData = $this->objDaoServiceformAdd->getServiceCate($super_type_id);
        Bd_Log::debug('getServiceCate'.(json_encode($strData)));
		$names = array();
		for($x=0;$x<count($strData);$x++){
			//$strData[$x] as $key => $value;
			array_push($names, $strData[$x]['name']);
		}
		return $names;
	}

	public function doServiceformAdd($username,$password){
		$strData = $this->objDaoServiceformAdd->doServiceformAdd($username,$password);
		return $strData;
	}
    
    public function getServiceformAdd($intId){
        Bd_Log::debug("sample data service getSample called");
        $strData =  $this->objDaoServiceformAdd->getSampleById($intId);
        return $strData;
    }

	public function getServiceformSubmit($strData){
		Bd_Log::debug('data'.$strData);
		$param = json_decode($strData, true);
		$cp_info = array();
		$cp_info['car_no'] = $param['car_no'];
		$cp_info['cashier'] = $param['cashier'];
		$cp_info['all_charge'] = $param['all_charge'];
		$cp_info['remark'] = $param['remark'];
		Bd_Log::debug('data'.json_encode($cp_info));
        $maintenance_id = $this->objDaoServiceformAdd->submitMaintan($cp_info);
		Bd_Log::debug('project'.json_encode($param['project']));
		for($i=0; $i < count($param['project']); $i++){
			Bd_Log::debug('for'.json_encode($param['project'][$i]['base']));
			$rec = array();
			$rec['maintenance_id'] = $maintenance_id;
			$rec['operator'] = $param['project'][$i]['operator'];
			$rec['price'] = (double)$param['project'][$i]['price'];
			$rec['remark'] = $param['project'][$i]['remark'];
			$rec['baseName'] = $param['project'][$i]['baseName'];
			$rec['categoryName'] = $param['project'][$i]['categoryName'];
			$rec['nameName'] = $param['project'][$i]['nameName'];
			$rec['base'] = $param['project'][$i]['base'];
			$rec['category'] = $param['project'][$i]['category'];
			$rec['name'] = $param['project'][$i]['name'];
			//category,name,price,remark,operator, baseName,categoryName,nameName
			Bd_Log::debug('foreach'.json_encode($rec));
        	$errno = $this->objDaoServiceformAdd->submitMaintanService($rec);
		}
		return true;
	}

    public function addServiceformAdd($strData){
        Bd_Log::debug("addServiceformAdd data service submitSample called");
		/*
        $arrFields = array('data'=>$strData);
		$param = array(
				'service_charge' => 1300,
				'other_charge' => 0,
				'car_no' => '粤B 58668',
				'user_id' => '张三',
				'cashier_id' => '李四',
				'create_time' => date('Y-m-d G:i:s'),
				'all_charge' => 2000,
				'remark' => 'text info',
				'project' => array(
					array(
						'service_id' => 0,
						'operator_id' => '005',
						'create_time' => date('Y-m-d G:i:s'),
						'remark' => 'text info'
					),
					array(
						'service_id' => 1,
						'operator_id' => '005',
						'create_time' => date('Y-m-d G:i:s'),
						'remark' => 'text info'
					)
				)
			);
		$cp_info = array();
		$cp_info['service_charge'] = $param['service_charge'];
		$cp_info['other_charge'] = $param['other_charge'];
		$cp_info['car_no'] = $param['car_no'];
		$cp_info['user_id'] = $param['user_id'];
		$cp_info['cashier_id'] = $param['cashier_id'];
		$cp_info['all_charge'] = $param['all_charge'];
		$cp_info['remark'] = $param['remark'];
        $maintenance_id = $this->objDaoServiceformAdd->submitMaintan($cp_info);
		for($i=0; $i < count($param['project']); $i++){
			$rec = array();
			$rec['maintenance_id'] = $maintenance_id;
			foreach($param['project'][$i] as $key=>$value){
				$rec[$key] = $value;
			}
        	$this->objDaoServiceformAdd->submitMaintanService($rec);
		}
		 */
		return true;
    }

	public function searchServiceform($inputStr){
		Bd_Log::debug('data_begin'.json_encode($inputStr));
		if (!$inputStr){
			return false;	
		}
		Bd_Log::debug('data'.$inputStr['car_no']);
		$res = $this->objDaoServiceformAdd->searchServiceform($inputStr['car_no'], $inputStr['time_start'], $inputStr['time_end']);
		Bd_Log::debug('data_test'.json_encode($res));
		return $res;
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
