<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_ApplyList extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		//$arrInput["store_id"] = 57;

		//$user_openid = Tool_WeiXin::getOpenid();

		if(!isset($arrInput["name"]) or $arrInput["name"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'name not exist');
		}

		//Tool_Const::$storeId
		//'ORDER BY apply_status, apply_time DESC '
		$result_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_employee_apply'),'*',NULL,NULL,'ORDER BY apply_status, apply_time DESC ');
		if ($result_select!==false and count($result_select)>0)
		{
			$data = $result_select;
			return Tool_Util::returnJson($data,0,'succ');
		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'apply error');
		}

		return Tool_Util::returnJson($data,0,'succ');
	}

}
