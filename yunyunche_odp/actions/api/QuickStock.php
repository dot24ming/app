<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_QuickStock extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
    	
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$form_status = Tool_Const::$Instock_status["reviewed"];
		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		$admin_store_id = $admin_info["store_id"];
		$arrInput['author'] = $admin_name;
		$arrInput['warehouse_id'] = $admin_store_id;
		$arrInput['form_status'] = $form_status;


		$arrInput['demand_detail'] = '{"a":1}';
		if(!isset($arrInput['demand_detail']))
		{
			return Tool_Util::returnJson(NULL,101,'params error');
		}
		$demand_detail = json_decode($arrInput['demand_detail'],true);
		var_dump($demand_detail);


		//3. call PageService
		/*
		$objServicePageLogcheck = new Service_Page_StockMag();
		$arrPageInfo = $objServicePageLogcheck->execute_ins_instock($arrInput);
		$arrPageInfo = $objServicePageLogcheck->execute_ins_outstock($arrInput);
		$logCheckResult = $arrPageInfo['data'];
		*/
		//return var_dump($arrInput);	
		//return var_dump($arrPageInfo);

		return Tool_Util::returnJson($arrPageInfo['data'],$arrPageInfo["errno"],$arrPageInfo["info"]);
		//return var_dump($logCheckResult);
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
