<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_EmployeeUpdate extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		/*
		$arrInput['id'] = 6;
		$arrInput["name"] = 'wuweijia';
		$arrInput["phone"] = '13800138000';
		$arrInput["store_id"] = 57;
		$arrInput["department_id"] = 4;
		$arrInput["role"] = 1;
		 */
		//$user_openid = Tool_WeiXin::getOpenid();

		if(!isset($arrInput["name"]) or $arrInput["name"]=="")
		{
			return Tool_Util::returnJson($data,-1,'name not exist');
		}
		if(!isset($arrInput["phone"]) or $arrInput["phone"]=="")
		{
			return Tool_Util::returnJson($data,-1,'phone not exist');
		}
		if(!isset($arrInput["store_id"]) or $arrInput["store_id"]=="")
		{
			return Tool_Util::returnJson($data,-1,'store_id not exist');
		}
		if(!isset($arrInput["department_id"]) or $arrInput["department_id"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'department_id not exist');
		}
		if(!isset($arrInput["role"]) or $arrInput["role"]=="")
		{
			return Tool_Util::returnJson($data,-1,'role not exist');
		}

		$param = array(
				'name' => $arrInput["name"],
				'phone' => $arrInput["phone"],
				'store_id' => $arrInput["store_id"],
				'department_id' => $arrInput["department_id"],
				'update_time' => date('Y-m-d H:i:s',time()),
				'role' => $arrInput['role']
			);

		//Tool_Const::$storeId
		$result_update = $db_client->update("model_mobile_employee_apply",$param,' id='.$arrInput['id'],NULL,NULL);
		if ($result_update!==false and count($result_update)>0)
		{
			return Tool_Util::returnJson($data,0,'succ');
		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'apply error');
		}

		return Tool_Util::returnJson($data,0,'succ');
	}

}
