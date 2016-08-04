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
		$arrInput = $arrRequest['post'];

		$user_openid = Tool_WeiXin::getOpenid();
		//Bd_Log::warning("openid:".$user_openid);
		//

		//var_dump($user_openid);


		if(!isset($arrInput["plate_number"]) or $arrInput["plate_number"]=="")
		{
			return Tool_Util::returnJson($data,-1,'plate_num not exist');
		}
		if(!isset($arrInput["phone_num"]) or $arrInput["phone_num"]=="")
		{
			return Tool_Util::returnJson($data,-1,'phone_num not exist');
		}


		/*
		$user_select_result = $db_client->select('model_user_info','*',' phone_num='.$arrInput["phone_num"],NULL,NULL);
		if($user_select_result!==false and count($user_select_result)>0 )
		{
			return Tool_Util::returnJson($data,-1,'phone_num exists');
		}
		$car_select_result = $db_client->select('model_car_info','*',' plate_number='.$arrInput["plate_number"],NULL,NULL);
		if($car_select_result!==false and count($car_select_result)>0 )
		{
			return Tool_Util::returnJson($data,-1,'plate_number exists');
		}
		*/



		$car_select_param = array();
		$car_select_param["plate_number="] = $arrInput["plate_number"];
		//$car_select_result = $db_client->select(Tool_Const::$storeId."_car_info",'*',$car_select_param,NULL,NULL);
		$car_select_result = $db_client->select("model_car_info",'*',$car_select_param,NULL,NULL);
		if($car_select_result!==false and count($car_select_result)>0)
		{
			$car_info = $car_select_result[0];
			$owner_id = $car_info['owner_id'];
			//$owner_select_result = $db_client->select(Tool_Const::$storeId."_user_info","*",' user_id='.$owner_id,NULL,NULL);
			$owner_select_result = $db_client->select("model_user_info","*",' user_id='.$owner_id,NULL,NULL);
			if($owner_select_result!==false and count($owner_select_result)>0)
			{
				$owner_info = $owner_select_result[0];
				$phone_num = $owner_info['phone_num'];
				if($phone_num==$arrInput["phone_num"])
				{
					$wx_update_param = array(
							'wechat_num' => $user_openid,
						);
					//$owner_update_result = $db_client->update(Tool_Const::$storeId."_user_info", $wx_update_param, ' user_id='.$owner_id,NULL,NULL);
					$owner_update_result = $db_client->update("model_user_info", $wx_update_param, ' user_id='.$owner_id,NULL,NULL);
					if($owner_update_result!==false or $owner_update_result==0)
					{
						// TODO need transaction
						#$globalUserDao = new Dao_GlobalUserInfo();
						#$ret = $globalUserDao->addUserInfo(Tool_Const::$storeId, $user_openid, $owner_id);
						#Bd_Log::addNotice("add global_user_info[$user_openid, $owner_id]ret[$ret]");
						//
						return Tool_Util::returnJson($data,0,'succ');
					}
					else
					{
						return Tool_Util::returnJson($owner_update_result,-1,'update error');
					}

				}
				else
				{
					return Tool_Util::returnJson($data,-1,'phone_num error');
				}
			}
			else
			{
				return Tool_Util::returnJson($data,-2,'car error'.$owner_id);
			}
		}
		else
		{
			$select_result = $db_client->select("model_user_info","*",' phone_num='.$arrInput["phone_num"],NULL,NULL);
			if($select_result!==false and count($select_result)>0)
			{
				$user_info = $select_result[0];
				$user_id = $user_info['user_id'];

				$params = array(
						'wechat_num' => $user_openid
					);
				$update_result = $db_client->update('model_user_info',$params,' user_id='.$user_id,NULL,NULL);
				if($update_result===false)
				{
					return Tool_Util::returnJson($data,-2,'wechat update error');
				}
				//$wechat_num = $user_info['wechat_num'];
				
				$params = array(
					'plate_number' => $arrInput["plate_number"],
					'owner_id' => $user_id,
					'car_reg_time' => date('Y-m-d H:i:s'),
				);

				$insert_result = $db_client->insert('model_car_info',$params);
				if($insert_result===false)
				{
					return Tool_Util::returnJson($data,-1,'insert car false');
				}
				return Tool_Util::returnJson($data,0,'succ');
			}
			else
			{
				$params = array(
						'phone_num' => $arrInput["phone_num"],
						'wechat_num' => $user_openid ,
						'reg_time' => date('Y-m-d H:i:s'),
						'member_from' => '微信C端',
					);
				$insert_result = $db_client->insert('model_user_info',$params);
				if ($insert_result==false)
				{
					return Tool_Util::returnJson($data,-1,'insert user error');
				}
				$user_id = $db_client->getInsertID();

				$params = array(
					'plate_number' => $arrInput["plate_number"],
					'owner_id' => $user_id,
					'car_reg_time' => date('Y-m-d H:i:s'),
				);

				$insert_result = $db_client->insert('model_car_info',$params);
				if ($insert_result==false)
				{
					return Tool_Util::returnJson($data,-1,'insert car error');
				}
				/*
				if($insert_result!==false and count($insert_result)>0)
				{
	
				}
				else
				{
					return Tool_Util::returnJson($data,2,'insert car error');
				}
				*/
				return Tool_Util::returnJson($data,0,'succ');
			}

			return Tool_Util::returnJson($data,2,'car error'.$owner_id);
		}


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
