<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_InsQuote extends Ap_Action_Abstract {
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

		/*
        if(!isset($arrInput['plate_number'])){
				return Tool_Util::returnJson(NULL,101,'params error, check plate_number');
        }
		if(!isset($arrInput['user_name'])){
				return Tool_Util::returnJson(NULL,101,'params error, check user_name');
		}
		if(!isset($arrInput['phone_num'])){
				return Tool_Util::returnJson(NULL,101,'params error, check phone_num');
		}
		*/
		/*
		$arrInput['store_name'] = 'wustore2';
		$arrInput['contact_user'] = 'wuweijia';
		$arrInput['phone'] = 1234;
		$arrInput['email'] = 'wuweijia@123.com';
		$arrInput['address'] = 'sz';
		$arrInput['status'] = '1';
		$arrInput['admin_account'] = 'wuweijia_1';
		$arrInput['password'] = '1234';
		*/
		$form_status = Tool_Const::$Quote_status["reviewing"];
		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		$admin_store_id = $admin_info["store_id"];
		$arrInput['author'] = $admin_name;
		$arrInput['warehouse_id'] = $admin_store_id;
		$arrInput['form_status'] = $form_status;

		/*
		$arrInput['sum_count'] = 20;
		$arrInput['remarks'] = 'wu';
		$arrInput['quote_man'] = 'wuweijia';
		//$arrInput['censor'] = 'wuweijia1'

		$arrInput['quote_items'] = array();

		$arrInputItem = array();
		$arrInputItem['goods_id'] = 1;
		$arrInputItem['purchase_id'] = 12;
		$arrInputItem['count'] = 20;
		$arrInputItem['remarks'] = 'no';
		$arrInputItem['quote_details'] = array();

		$arrInputItemDetail = array();
		$arrInputItemDetail['supplier_id'] = 123;
		$arrInputItemDetail['guide_price'] = 20.3;

		array_push($arrInputItem['quote_details'],$arrInputItemDetail);


		array_push($arrInput['quote_items'],$arrInputItem);

		 */
		//return Tool_Util::returnJson($arrInput,'','');


	    //3. call PageService
		$objServicePageLogcheck = new Service_Page_StockMag();
		$arrPageInfo = $objServicePageLogcheck->execute_ins_quote($arrInput);
		$logCheckResult = $arrPageInfo['data'];

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
