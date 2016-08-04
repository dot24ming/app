<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_OutstockDemandVerify extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		//$arrInput['form_id'] = '74';
		//$user_openid = Tool_WeiXin::getOpenid();

		if(!isset($arrInput["form_id"]) or $arrInput["form_id"]=="")
		{
			return Tool_Util::returnJson($data,-1,'form_id not exist');
		}


		$param = array(
				'verify_status' => 1,
				'verify_time' => date('Y-m-d H:i:s',time())
			);

		//Tool_Const::$storeId
		//$result_update = $db_client->update("model_mobile_service_outstock_demand",$param,' form_id='.$arrInput['form_id'],NULL,NULL);
		$result_update = $db_client->update("model_mobile_service_form",$param,' form_id='.$arrInput['form_id'],NULL,NULL);
		if ($result_update!==false and count($result_update)>0)
		{
			return Tool_Util::returnJson($data,0,'succ');
			// add outstock
			/*
			$result_select = $db_client->select("model_mobile_employee_apply","*",' id='.$arrInput['id'],NULL,NULL);
			if ($result_select!==false and count($result_select)>0)
			{
				$employee = $result_select[0];
				$param = array(
					'name' => $employee["name"],
					'phone' => $employee["phone"],
					'store_id' => $employee["store_id"],
					'department_id' => $employee["department_id"],
					'wechat' => $employee["wechat"],
					'wechat_nickname' => $employee["wechat_nickname"],
					'update_time' => date('Y-m-d H:i:s',time())
				);
				$result_insert = $db_client->insert("model_mobile_employee",$param);
				if ($result_insert!==false and count($result_insert)>0)
				{
					return Tool_Util::returnJson($data,0,'succ');
				}
				else
				{
					$param = array(
						'apply_status' => 0,
						'update_time' => date('Y-m-d H:i:s',time())
					);
					$result_update = $db_client->update("model_mobile_employee_apply",$param,' id='.$arrInput['id'],NULL,NULL);
					return Tool_Util::returnJson($data, -1, 'apply error');
				}
			}
			else
			{
					$param = array(
						'apply_status' => 0,
						'update_time' => date('Y-m-d H:i:s',time())
					);
					$result_update = $db_client->update("model_mobile_employee_apply",$param,' id='.$arrInput['id'],NULL,NULL);
					return Tool_Util::returnJson($data, -1, 'apply error');
			}
			*/
		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'verify error');
		}

		return Tool_Util::returnJson($data,0,'succ');
	}

}
