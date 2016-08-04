<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_OutstockDemand extends Ap_Action_Abstract {

	public function execute() {

		//return Tool_Util::returnJson($data,-1,'form_type not exist');
		//$admin_info = Tool_Const::$adminInfo;
		//$admin_name = $admin_info["name"];


		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$store_id = $arrRequest['request_param']['storeId'];
		$arrInput = $arrRequest['get'];

		$start_idx = $arrInput["start"];
		$end_idx = $arrInput["end"];


		$offset = $end_idx - $start_idx;
		$limit_str = 'limit '.$start_idx.','.$offset;


		//use new table
		/*
		$result_select = $db_client->select('model_mobile_service_outstock_demand','*',NULL,NULL,'ORDER BY status, update_time DESC '.$limit_str);
		if ($result_select!==false and count($result_select)>0)
		{
			$id_list = array();
			foreach($result_select as $service_outstock_demand)
			{
				array_push($id_list,$service_outstock_demand['form_id']);
			}
			$id_list_str = implode(',',$id_list);

			$result_select = $db_client->select('model_mobile_service_form','*',' form_id in ('.$id_list_str.')',NULL,'ORDER BY status,time DESC ');
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
		}
		*/

		$record_count = 0;
		$select_count = $db_client->selectCount('model_mobile_service_form','quick_outstock!=1',NULL,NULL);
		$record_count += $select_count;

		$result_select = $db_client->select('model_mobile_service_form_info','form_id','quick_outstock!=1 and type=1',NULL, 'GROUP BY form_id');
		$record_count = count($result_select);
		$goods_form_list = array();
		foreach($result_select as $form_id)
		{
			array_push($goods_form_list,$form_id['form_id']);
		}
		$goods_form_str = implode(',',$goods_form_list);
		

		//$result_select = $db_client->select('model_mobile_service_form','*',' quick_outstock!=1',NULL,'ORDER BY verify_status,time DESC '.$limit_str);
		//$result_select = $db_client->select('model_mobile_service_form','*',' form_id in ('.$goods_form_str.') and quick_outstock!=1 ',NULL,'ORDER BY time DESC '.$limit_str);
		$result_select = $db_client->select('model_mobile_service_form','*',' form_id in ('.$goods_form_str.') and quick_outstock!=1 ',NULL,'ORDER BY time DESC limit 0,100');
		//var_dump($result_select);
		if ($result_select!==false and count($result_select)>0)
		{
			$service_form_list = $result_select;

			$form_id_list = array();

			$plate_number_list = array();
			foreach($result_select as $service_form)
			{
				$plate_number = $service_form['plate_number'];
				array_push($plate_number_list,'"'.$plate_number.'"');

				$form_id =  $service_form['form_id'];
				array_push($form_id_list,$form_id);
			}
			$plate_number_str = implode(',',$plate_number_list);
			$car_info_dict = array();
			$car_result_select = $db_client->select('model_car_info','*',' plate_number in ('.$plate_number_str.')',NULL,NULL);

			$series_id_list = array();
			foreach($car_result_select as $car_info)
			{
				$plate_number = $car_info['plate_number'];
				$series = $car_info['series'];
				if (!isset($car_info_dict[$plate_number]))
				{
					$car_info_dict[$plate_number] = $car_info;
				}
				array_push($series_id_list,$series);
			}
			$series_id_str = implode(',',$series_id_list);

			$series_result_select = $db_client->select('car_series','*',' series_id in ('.$series_id_str.')',NULL,NULL);
			$series_dict = array();
			foreach($series_result_select as $series_info)
			{
				$series_id = $series_info['series_id'];
				if (!isset($series_dict[$series_id]))
				{
					$series_dict[$series_id] = $series_info;
				}
			}

			$form_id_str = implode(',',$form_id_list);
			$form_info_result_select = $db_client->select('model_mobile_service_form_info','*',' form_id in ('.$form_id_str.') and type=1',NULL,NULL);
			//var_dump($form_info_result_select);
			$form_info_dict = array();
			$goods_sale_dict = array();
			foreach($form_info_result_select as $form_info)
			{
				$goods_id = $form_info['service_id'];
				$goods_select_result = $db_client->select('model_goods_info','*',' goods_id='.$goods_id,NULL,NULL);
				if($goods_select_result!==false and count($goods_select_result)>0)
				{
					$goods_info = $goods_select_result[0];

					//TODO to lock goods if need by service

					$demand_purchased_count = 0;
					$demand_select_result = $db_client->select('model_goods_demand','*',' service_id='.$form_info['form_id'].' and goods_id='.$goods_id,NULL,NULL);
					if($demand_select_result!==false and count($demand_select_result)>0)
					{
						$demand_purchased_count = (int)$demand_select_result[0]['purchased_count'];
					}
					//$instock_count = $form_info['instock_count'];
					if($demand_purchased_count>0)
					{
						$instock_count = $demand_purchased_count;
					}
					else
					{
						$instock_count = $goods_info['instock_count'];
					}
					$demand_count = $form_info['count'];
					if($instock_count > $demand_count)
					{
						//continue;
						$form_info['purchase_count'] = 0;
					}
					else
					{
						$form_info['purchase_count'] = $demand_count-$instock_count;
					}
					$form_info['instock_count'] = $instock_count;
				}

				$form_id = $form_info['form_id'];
				if(!isset($form_info_dict[$form_id]))
				{
					$form_info_dict[$form_id] = array();	
				}
				array_push($form_info_dict[$form_id],$form_info);
				if(!isset($goods_sale_dict[$form_id]))
				{
					$goods_sale_dict[$form_id] = array();
				}
				$service_name = $form_info['service_name'];
				array_push($goods_sale_dict[$form_id],$service_name);
			}


			$service_with_goods_list = array();
			$service_count = count($service_form_list);
			for ($i=0;$i<$service_count;$i++)
			{	
				$plate_number = $service_form_list[$i]['plate_number'];
				$service_form_list[$i]['car_info'] = array();
				if(isset($car_info_dict[$plate_number]))
				{
					$service_form_list[$i]['car_info'] = $car_info_dict[$plate_number];
					$series = $service_form_list[$i]['car_info']['series'];
					$service_form_list[$i]['car_info']['car_series'] = '';
					$service_form_list[$i]['car_info']['car_brand'] = '';
					if(isset($series_dict[$series]))
					{
						$service_form_list[$i]['car_info']['car_series'] = $series_dict[$series]['series'];
						$service_form_list[$i]['car_info']['car_brand'] = $series_dict[$series]['brand'];
					}
				}

				$form_id = $service_form_list[$i]['form_id'];
				$service_form_list[$i]['service_detail'] = array();
				$service_form_list[$i]['goods_sale_detail'] = '';
				if(isset($form_info_dict[$form_id]))
				{
					$service_form_list[$i]['service_detail'] = $form_info_dict[$form_id];
					//$service_form_list[$i]['service_detail'] = array();
				}
				if(isset($goods_sale_dict[$form_id]) and count($goods_sale_dict[$form_id])>0 )
				{
					$service_form_list[$i]['goods_sale_detail'] = implode(',',$goods_sale_dict[$form_id]);
					array_push($service_with_goods_list,$service_form_list[$i]);
				}
			}

			/*
			foreach($service_form_list as $service_form_info)
			{
				$plate_number = $service_form_info['plate_number'];
				$service_form_info['car_info'] = array();
				if(isset($car_info_dict[$plate_number]))
				{
					$service_form_info['car_info'] = $car_info_dict[$plate_number];
				}
			}
			 */

			$sub_service_with_goods_list = array();
			$max_end_idx = count($service_with_goods_list);
			if($end_idx<$max_end_idx)
			{
				$max_end_idx = $end_idx;
			}
			for($i=$start_idx;$i<$max_end_idx;$i++)
			{
				array_push($sub_service_with_goods_list,$service_with_goods_list[$i]);
			}

			$return_arr = array(
				//'result' => $service_form_list,
				//'result' => $service_with_goods_list,
				'result' => $sub_service_with_goods_list,
				'count' => count($service_with_goods_list),
				//'count' => $record_count,
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
