<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_EmployeeApply extends Ap_Action_Abstract {

	public function execute() {
		$openid = Tool_WeiXin::getOpenid();
		$arrRequest = Saf_SmartMain::getCgi();
		$params = $arrRequest['request_param'];

		$storeId = $params['storeId'];
		if (empty($storeId)) {
			$storeId = 56;
		}

		$data = array();
	
		$data['openid'] = $openid;
		$data['storeName'] = $storeId;
		$data['userName'] = 'xinshisu';
		//setcookie('yunyunche_openid', $openid);
		
		$db_client  = new Dao_DBbase();
		$result_select = $db_client->select('store_info','*',' store_id='.$storeId,NULL,NULL);
		if($result_select!==false and count($result_select)>0)
		{
			$store_info = $result_select[0];
			$store_name = $store_info["name"];
			$data['storeName'] = $store_name;
		}


		Tool_Util::displayTpl($data, 'mobile/page/wx/employeeapply.tpl');

		exit;

	}

}
