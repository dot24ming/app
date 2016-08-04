<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_InStock extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		if(!isset($arrInput["form_id"]) or $arrInput["form_id"]==""){
			$time = date('Y-m-d H:i:s');
			$form_status = Tool_Const::$Instock_status["reviewing"];
		}
		else{
			$select_result = $db_client->select('goods_storage','storage_status,time','storage_id='.$arrInput["form_id"],NULL,NULL);
			if ($select_result!=false and count($select_result)>0 )
			{
				$form_status = $select_result[0]['storage_status'];
				$time = $select_result[0]['time'];
			}
			else
			{
				$form_status = "该表单不存在";
				$time = date('Y-m-d H:i:s');
			}
		}

		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		$admin_store_id = $admin_info["store_id"];

		$data = array();
		$data["author"] = $admin_name;
		$data["status"] = $form_status;
		$data["warehouse_id"] = $admin_store_id;
		$data["time"] = $time;



		$select_result = $db_client->select('store_info','name',' store_id='.$admin_store_id,NULL,NULL);
		$data["warehouse_name"] = $select_result[0]["name"];


		return Tool_Util::returnJson($data,0,'');
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
