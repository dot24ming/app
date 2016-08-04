<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_SelUser extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		if(!isset($arrInput["start_idx"])){
			$arrInput['start_idx'] = 0;
		}
		else{
			if ($arrInput['start_idx']<0){
				return Tool_Util::returnJson(NULL,101,'params error');
			}
		}
		if(!isset($arrInput["end_idx"])){
			$arrInput['end_idx'] = 5;
		}
		else{
			if ($arrInput['start_idx']>100){
				return Tool_Util::returnJson(NULL,101,'params error');
			}
		}

		//return var_dump($arrRequest);


	    //3. call PageService
		$objServicePageLogcheck = new Service_Page_SelCustomer();
		//$arrPageInfo = $objServicePageLogcheck->execute($arrInput);
		$arrPageInfo = $objServicePageLogcheck->execute_l($arrInput);
		return Tool_Util::returnJson($arrPageInfo['data'],$arrPageInfo["errno"],'');
		$logCheckResult = $arrPageInfo['data'];
		//return var_dump($arrPageInfo);	
		//return $logCheckResult;

		//return var_dump(1);
		return var_dump($arrPageInfo);


		///*
		if ($logCheckResult == 1){
			echo "true";
			return true;
		}else{
			echo "false";
			return false;
		}
		//*/
		//echo $logCheckResult;
		//return $logCheckResult;





		//4. chage data to out format
		//$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
	    //$tpl->assign('arrOutput',$arrOutput['data']);
	    //$tpl->display('yunyunche_odp/index.tpl');
		
		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		//Bd_Log::addNotice('out', $arrOutput);

		//return $strOut; 

	}

}
