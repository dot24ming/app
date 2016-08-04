<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_EditMyCar extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		if( !isset($arrInput["user_id"]) or $arrInput["user_id"]=="" )
		{
			return Tool_Util::returnJson($data,-1,'user_id error');
		}
		if( !isset($arrInput["plate_number"]) or $arrInput["plate_number"]=="" )
		{
			return Tool_Util::returnJson($data,-1,'plate_number error');
		}

		$plate_number = $arrInput["plate_number"];
		$user_id = $arrInput["user_id"];
		//$select_result = $db_client->select(Tool_Const::$storeId.'_car_info','owner_id','plate_number='.$plate_number,NULL,NULL);
		//$select_result = $db_client->select('56_car_info','owner_id','plate_number="'.$plate_number.'"',NULL,NULL);
		$select_result = $db_client->select('model_car_info','owner_id','plate_number="'.$plate_number.'"',NULL,NULL);
		if ($select_result!=false)
		{
			if(count($select_result)>0)
			{
				$owner_id = $select_result[0]['owner_id'];
				if($owner_id==$user_id)
				{
					if( isset($arrInput["phone_num"]) and $arrInput["phone_num"]!="" )
					{
						$update_param = array();
						$phone_num =  $arrInput["phone_num"];
						$update_param['phone_num'] = $phone_num;
						//$update_result = $db_client->update(Tool_Const::$storeId.'_user_info',$update_param,' user_id='.$user_id,NULL,NULL);
						//$update_result = $db_client->update('56_user_info',$update_param,' user_id='.$user_id,NULL,NULL);
						$update_result = $db_client->update('model_user_info',$update_param,' user_id='.$user_id,NULL,NULL);

						if ($update_result!==false and $update_result>=0)
						{
							return Tool_Util::returnJson($data,0,'succ');
						}
						return Tool_Util::returnJson($data,-1,'phone_num update error');
					}
					if( isset($arrInput["engine_number"]) and $arrInput["engine_number"]!="" )
					{
						$update_param = array();
						$engine_number = $arrInput["engine_number"];
						$update_param['engine_number'] = $engine_number;
						//$update_result = $db_client->update(Tool_Const::$storeId.'_car_info',$update_param,' engine_number='.$engine_number,NULL,NULL);
						//$update_result = $db_client->update('56_car_info',$update_param,' plate_number="'.$plate_number.'"',NULL,NULL);
						$update_result = $db_client->update('model_car_info',$update_param,' plate_number="'.$plate_number.'"',NULL,NULL);

						if ($update_result!==false and $update_result>=0)
						{
							return Tool_Util::returnJson($data,0,'succ');
						}
						return Tool_Util::returnJson($update_result,-1,'engine_number update error');
					}
					if( isset($arrInput["frame_number"]) and $arrInput["frame_number"]!="" )
					{
						$update_param = array();
						$frame_number = $arrInput["frame_number"];
						$update_param['frame_number'] = $frame_number;
						//$update_result = $db_client->update(Tool_Const::$storeId.'_car_info',$update_param,' frame_number='.$frame_number,NULL,NULL);
						//$update_result = $db_client->update('56_car_info',$update_param,' plate_number="'.$plate_number.'"',NULL,NULL);
						$update_result = $db_client->update('model_car_info',$update_param,' plate_number="'.$plate_number.'"',NULL,NULL);
						if ($update_result!==false and  $update_result>=0 )
						{	
							return Tool_Util::returnJson($data,0,'succ');
						}
						return Tool_Util::returnJson($data,-1,'frame_number update error');
					}
				}
			}
		}
		





		/*
		$form_comment_update_param = array();
		if(isset($arrInput["rate"]))
		{
			$form_comment_update_param['rate'] = $arrInput["rate"];
		}
		if(isset($arrInput["comment"]))
		{
			$form_comment_update_param['comment'] = $arrInput['comment'];
		}
		*/

		/*
		if(!isset($arrInput["rate"]) or $arrInput["rate"]=="")
		{
			return Tool_Util::returnJson($data,-1,'rate not exist');
		}
		if(!isset($arrInput["plate_number"]) or $arrInput["plate_number"]=="")
		{
			return Tool_Util::returnJson($data,-1,'plate_num not exist');
		}
		*/

		//$form_comment_update_param = array(
		//		'rate' => $arrInput["rate"],
		//		'comment' => $arrInput["comment"]
		//	);

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
