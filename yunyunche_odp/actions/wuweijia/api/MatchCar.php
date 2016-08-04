<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MatchCar extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];

		//test
		/*
		$arrInput["form_id"] = 6;
		$arrInput["rate"] = 1;
		$arrInput["comment"] = "asd";
		*/
		if(!isset($arrInput["start"])){
			$arrInput['start'] = 0;
		}
		else{
			if ($arrInput['start']<0){
				return Tool_Util::returnJson(NULL,101,'params error');
			}
		}
		if(!isset($arrInput["end"])){
			$arrInput['end'] = 10;
		}
		$offset = $arrInput['end'] - $arrInput['start'];
		$limit_str = 'limit '.$arrInput['start'].','.$offset;
		
		if(!isset($arrInput["plate_number"]) or $arrInput["plate_number"]=="")
		{
			return Tool_Util::returnJson($data,0,'');
		}

		$select_result = $db_client->select(Tool_Util::getStoreTable('model_car_info'),'*',' plate_number like "%'.$arrInput["plate_number"].'%"',NULL,$limit_str);
		if ($select_result!=false){
			if (count($select_result)>0){
				return Tool_Util::returnJson($select_result,0,'succ');
			}else{
				return Tool_Util::returnJson($select_result,0,'');
			}
		}else{
			return Tool_Util::returnJson($select_result,0,'');
		}


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
