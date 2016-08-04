<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_AddSupplier extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		if(!isset($arrInput["supplier_name"]) or $arrInput["supplier_name"]==""){
			return Tool_Util::returnJson($data,1,'error');
		}
		else{
			$supplier_name = $arrInput["supplier_name"];
			$info = $arrInput["info"];
			$address = $arrInput["address"];
			$phone = $arrInput["phone"];
			$linkman = $arrInput["linkman"];

			$result_select = $db_client->select(Tool_Util::getStoreTable('supplier_info'),'*','supplier_name="'.$supplier_name.'"',NULL,NULL);
			if ($result_select!=false and count($result_select)>0){
				$data = false;
				return Tool_Util::returnJson($data,-1,'supplier name exit');
			}
			else{
				$item_param = array(
						'supplier_name' => $supplier_name,
						'info' => $info,
						'address' => $address,
						'phone' => $phone,
						'linkman' => $linkman
					);
				$result_insert = $db_client->insert(Tool_Util::getStoreTable('supplier_info'), $item_param);
				if ($result_insert!=false and count($result_insert)>0){
					$data = true;
					return Tool_Util::returnJson($data,0,'succ');
				}
				else{
					$data = false;
					return Tool_Util::returnJson($data,-1,'insert error');
				}
			}
		}



		return Tool_Util::returnJson($data,0,'succ');
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
