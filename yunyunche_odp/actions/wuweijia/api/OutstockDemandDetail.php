<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_OutstockDemandDetail extends Ap_Action_Abstract {

	public function execute() {

		//return Tool_Util::returnJson($data,-1,'form_type not exist');
		//$admin_info = Tool_Const::$adminInfo;
		//$admin_name = $admin_info["name"];


		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$store_id = $arrRequest['request_param']['storeId'];
		/*
		if ($store_id!='36')
		{
			return Tool_Util::returnJson($data,-1,'mobile table');
		}
		 */
		$arrInput = $arrRequest['get'];

		#$arrInput["form_id"] = 61;

		if( !isset($arrInput["form_id"]) or $arrInput['form_id']=='')
		{
			return Tool_Util::returnJson($data,-1,'form_id not exist');
		}

		//$result_select = $db_client->select('model_mobile_service_form_info','*',' form_id='.$arrInput["form_id"],NULL,NULL);
		$result_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_service_form_info'),'*',' form_id='.$arrInput["form_id"].' and type=1',NULL,NULL);
		if ($result_select!==false and count($result_select)>0)
		{
			$return_arr = array(
				'result' => $result_select,
				'count' => count($result_select),
				'codeMsg' => 'succ',
				'code' => 0
			);
			$data['result'] = $return_arr['result'];
			$data['result_count'] = $return_arr['count'];
			$data['ret'] = $return_arr['code'];
			$data['errno'] = $return_arr['code'];

			return Tool_Util::returnJson($data,0,'succ');
		}

		/*
		$arrInput["form_id"] = '61';

		if(!isset($arrInput["form_id"]) or $arrInput["form_id"]=="")
		{
			return Tool_Util::returnJson($data,-1,'form_id not exist');
		}

		$result_select = $db_client->select('model_mobile_service_form','*',' form_id='.$arrInput["form_id"],NULL,NULL);
		if ($result_select!==false and count($result_select)>0)
		{
			$mobile_service = $result_select[0];
			$form_id = $mobile_service['form_id'];
			$user_id = $mobile_service['user_id'];
			$plate_number = $mobile_service['plate_number'];
			$time = $mobile_service['time'];
			$settlement = $mobile_service['settlement'];
			$person = $mobile_service['person'];

			$result_select_detail = $db_client->select('model_mobile_service_form_info','*',' form_id='.$form_id.' and type=1',NULL,NULL);
			if ($result_select_detail!==false and count($result_select_detail)>0)
			{
				//var_dump($result_select_detail);
				foreach($result_select_detail as $goods_info)
				{
					$goods_id = $goods_info['service_id'];
					$price = $goods_info['price'];
					$count = $goods_info['count'];
				}
			}
		}
		*/

		return Tool_Util::returnJson($data,0,'succ');

	}

}
