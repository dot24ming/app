<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_EmployeeApply extends Ap_Action_Abstract {

	public function execute() {
		$openid = Tool_WeiXin::getOpenid();
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		/*
		$arrInput["name"] = 'wuweijia';
		$arrInput["phone"] = '13800138000';
		$arrInput["store_id"] = 57;
		$arrInput["department_id"] = 4;
		$arrInput["wechat"] = 'wechat open id';
		$arrInput["wechat_nickname"] = "weijia";
		//$user_openid = Tool_WeiXin::getOpenid();
		*/

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
		
		if(!isset($arrInput["wechat"]) or $arrInput["wechat"]=="")
		{
			//$wechat = Tool_WeiXin::getOpenid();
			//return Tool_Util::returnJson($data,-1,'wechat not exist');
			$arrInput["wechat"] = Tool_WeiXin::getOpenid();
		}
		


		$param = array(
				'name' => $arrInput["name"],
				'phone' => $arrInput["phone"],
				'store_id' => $arrInput["store_id"],
				'department_id' => $arrInput["department_id"],
				'wechat' => $arrInput["wechat"],
				'wechat_nickname' => $arrInput["wechat_nickname"],
				'apply_time' => date('Y-m-d H:i:s',time())
			);

		//Tool_Const::$storeId
		$result_insert = $db_client->insert("model_mobile_employee_apply",$param);
		if ($result_insert!==false and count($result_insert)>0)
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
