<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_EmployeeDelete extends Ap_Action_Abstract {

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

		if(!isset($arrInput['id']) or $arrInput['id']=="")
		{
			return Tool_Util::returnJson($data,-1,'id not exist');
		}

		$param = array(
				'valid' => 0,
			);

		$result_update = $db_client->update("model_mobile_employee",$param, ' id = '.$arrInput["id"],NULL,NULL);

		if ($result_update!==false and count($result_update)>0)
		{
			return Tool_Util::returnJson($data,0,'succ');
		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'update error');
		}

		return Tool_Util::returnJson($data,0,'succ');
	}

}
