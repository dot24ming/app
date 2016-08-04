<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_GetCategoryList extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		//test
		/*
		$arrInput["form_id"] = 6;
		$arrInput["rate"] = 1;
		$arrInput["comment"] = "asd";
		*/
		
		$select_result = $db_client->select(Tool_Util::getStoreTable('model_goods_info'),'distinct(category)',NULL,NULL,NULL);
		return Tool_Util::returnJson($select_result,0,'succ');

		/*
		$update_result = $db_client->update(Tool_Util::getStoreTable('model_mobile_service_form'),$form_comment_update_param,' form_id='.$arrInput["form_id"],NULL,NULL);
		if ($update_result!=false){
			if(count($update_result)>0){
				return Tool_Util::returnJson($data,0,'succ');
			}
			else{
				return Tool_Util::returnJson($data,1,'no match form');
			}
		}
		else{
			return Tool_Util::returnJson($data,0,'comment rep or false');
		}
		*/

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
