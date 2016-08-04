<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_RelateMyCar extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		/*
		if(!isset($arrInput["wechat_num"]) or $arrInput["wechat_num"]=="")
		{
			return Tool_Util::returnJson($data,-1,'wechat_num not exist');
		}
		if(!isset($arrInput["phone_num"]) or $arrInput["phone_num"]=="")
		{
			return Tool_Util::returnJson($data,-1,'phone_num not exist');
		}
		*/
		if(!isset($arrInput["plate_number"]) or $arrInput["plate_number"]=="")
		{
			return Tool_Util::returnJson($data,-1,'plate_num not exist');
		}
		$car_select_param = array();
		$car_select_param["plate_number="] = $arrInput["plate_number"];
		if(isset($arrInput["frame_number"]))
		{
			$frame_number = $arrInput["frame_number"];
		}
		if(isset($arrInput["engine_number"]))
		{
			$engine_number = $arrInput["engine_number"];
		}

		$car_select_result = $db_client->select(Tool_Util::getStoreTable("model_car_info"),'*',$car_select_param,NULL,NULL);
		$data = $car_select_result;


		/*
		$form_type = $arrInput["form_type"];
		$form_id = $arrInput["form_id"];
		$form_status = $arrInput["type"];
		$form_type_dict = Tool_Const::$Verify_form_type;
		
		//if (isset($form_type_dict[$form_type])
		if (array_key_exists($form_type,$form_type_dict))
		{
			$table_name = Tool_Const::$Verify_form_type[$form_type];	
			$update_param = array(
					Tool_Const::$Verify_status_const[$form_type] => $form_status,
					'review_time' => date('Y-m-d H:i:s'),
					'censor' => $admin_name,
				);
			$update_conds_param = array(
					Tool_Const::$Verify_id_const[$form_type].'=' => intval($form_id),
				);
			//var_dump($table_name);
			//var_dump($update_param);
			//var_dump($form_id);
			$result_select = $db_client->update($table_name,$update_param,$update_conds_param);

			return Tool_Util::returnJson($result_select,0,'succ');
		}
		else
		{
			return Tool_Util::returnJson($form_type,-1,'form_type not exists');
		}
		*/

		return Tool_Util::returnJson($data,0,'succ');

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
