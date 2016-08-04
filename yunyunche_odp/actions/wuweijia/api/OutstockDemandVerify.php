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

		//$user_openid = Tool_WeiXin::getOpenid();
		$form_id = $arrInput["form_id"];

		if(!isset($arrInput["form_id"]) or $arrInput["form_id"]=="")
		{
			return Tool_Util::returnJson($data,-1,'form_id not exist');
		}

		$instock_param = array();
		//TODO
		$admin_name = '新时速';
		$instock_param['author'] = $admin_name;
		$admin_store_id = '新时速-美车汇';
		$instock_param['warehouse_id'] = $admin_store_id;
		$form_status = 'reviewing';
		$instock_param['_status'] = $form_status;
		$instock_param['form_status'] = $form_status;
		$time = date('Y-m-d H:i:s');
		$instock_param['time'] = $time;
		$instock_param['instock_type'] = 'maintenance';
		$instock_param["instock_warehouse"] = $admin_store_id;
		$instock_param["instock_department"] = $admin_store_id;
		$instock_param['department'] = $admin_store_id;
		$instock_param['purchaser'] = '';
		$instock_param['remarks'] = '';
		$instock_param['warehouse_auditor'] = $admin_name;

		$form_result_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_service_form'),'*',' form_id='.$arrInput["form_id"],NULL,NULL);
		if($form_result_select!==false and count($form_result_select)>0)
		{
			$form_detail = $form_result_select[0];
			$plateNumber = $form_detail['plate_number'];
		}
		else
		{
			return Tool_Util::returnJson($formId, -1, '需求单不存在'.$arrInput["form_id"]);
		}

		$instock_param['plate_number'] = $plateNumber;
		$instock_param['instock_plan'] = $arrInput["form_id"];

		$total_num = 0;
		$total_price = 0;

		$instock_param['instock_items'] = '';
		$tmp_instock_items = array();

		$result_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_service_form_info'),'*',' form_id='.$arrInput["form_id"].' and type=1',NULL,NULL);
		if ($result_select!==false and count($result_select)>0)
		{
			foreach($result_select as $service_goods)
			{
				$s_item = array();
				$s_item['goods_id'] = $service_goods['service_id'];

				$goods_instock_count = 0;
				$goods_info_list = $db_client->select(Tool_Util::getStoreTable('model_goods_info'),'*',' goods_id = '.$s_item['goods_id'],NULL,NULL);
				if($goods_info_list!==false and count($goods_info_list)>0)
				{
					$goods_instock_count = $goods_info_list[0]['instock_count'];
				}

				$total_num += ($service_goods['count'] - $goods_instock_count);
				$total_price += $service_goods['price'] * ($service_goods['count']-$goods_instock_count);


				$s_item['count'] = ($service_goods['count'] - $goods_instock_count);
				$s_item['unit_price'] = $service_goods['price'];
				$s_item['settlement'] = '';
				$s_item['remarks'] = '';
				$s_item['supplier_id'] = '';
				$s_item['sales_quote'] = $service_goods['cost'];

				array_push($tmp_instock_items,$s_item);
			}
		}

		$instock_items_str = json_encode($tmp_instock_items);
		$instock_param['instock_items'] = $instock_items_str;
		$instock_param['sum_count'] = $total_num;
		$instock_param['sum_price'] = $total_price;

		$objServicePageLogcheck = new Service_Page_StockMag();
		$arrPageInfo = $objServicePageLogcheck->execute_ins_instock($instock_param);
		if($arrPageInfo['errno']!==0)
		{
			return Tool_Util::returnJson($formId, 0, '入库单生成失败');
		}
		else
		{
			//return Tool_Util::returnJson($formId, 0, '提交成功');
		}



		//var_dump($arrPageInfo);
		$data = array();
		$data['form_id'] = $arrPageInfo['form_id'];






		$param = array(
				'verify_status' => 1,
				'verify_time' => date('Y-m-d H:i:s',time())
			);

		//Tool_Const::$storeId
		//$result_update = $db_client->update("model_mobile_service_outstock_demand",$param,' form_id='.$arrInput['form_id'],NULL,NULL);
		$result_update = $db_client->update(Tool_Util::getStoreTable("model_mobile_service_form"),$param,' form_id='.$arrInput['form_id'],NULL,NULL);
		if ($result_update!==false and count($result_update)>0)
		{
			return Tool_Util::returnJson($data,0,'succ');
			// instock sheet
			// outstock sheet
			// ==============================================
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
