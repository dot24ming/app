<?php
/**
 * @name Service_Page_ServiceformAdd
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author ycx
 */
class Service_Page_ServicemenuSearch {
    //private $objServiceDataLogin;
    public function __construct(){
        $this->objServiceDataformAdd = new Service_Data_ServiceformAdd();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
    public function execute($arrInput){
        Bd_Log::debug('add_serviceform page service called');
        $arrResult = array();
		try{
			//if($intId <= 0){
				//参数错误的时候，从配置文件取消息
			//	$strData = Bd_Conf::getAppConf('sample/msg');
			//	$arrResult['data'] = $strData; 
			//}else if($this->objServiceDataLogin->isExist($intId)){
			//}else{
				//以下获取数据的方式提供3种示例，3选1
				//1. 调用本地DS
				$arrResult = $this->objServiceDataformAdd->searchServiceform($arrInput);
				//2. 子系统交互, 注意：请确保conf/saf.conf中的api_lib配置成Yunyunche_odp, 否则会出错
				//$strData = $this->objServiceDataLogin->callOtherApp($intId);
				//3. 调用本地库
				//$objUtil = new App2_Util();
				//$strData = $objUtil->getUtilMsg();
				//$arrResult['errno'] = 0;
		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}
		Bd_Log::debug('page_test'.json_encode($arrResult));
		return $arrResult;
    }
}
