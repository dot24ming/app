<?php 
class Action_MobileSubmitService extends Ap_Action_Abstract {
	public function execute() {

		//$admin_info = Tool_Const::$adminInfo;


		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		//var_dump($arrInput);
		//参数解析
		$formId = $arrInput['form_id'];
		if (empty($formId)){
			$formId = -1;
		}
		$plateNumber = $arrInput['plate_number'];
		$done = $arrInput['done'];
		if (empty($done)){
			$done = 0;
		}
		$person = $arrInput['person'];
		if (empty($person)){
			$person = "";
		}
		$settlement = $arrInput['settlement'];
		if (empty($settlement)){
			$settlement = "";
		}
		$price = $arrInput['price'];
		if (empty($price)){
			$price = "";
		}
		$picker = $arrInput['picker'];
		if (empty($picker)){
			$picker = "";
		}
		$cost = $arrInput['cost'];
		if (empty($cost)){
			$cost = 0;
		}

		$discount = $arrInput['discount'];
		if(empty($discount)){
			$discount = 0.0;
		}

		$settlementAmount = $arrInput['settlement_amount'];
		if(empty($settlementAmount)){
			$settlementAmount = 0.0;
		}

		$settlementTime = $arrInput['settlement_time'];
		if(empty($settlementTime)){
			$settlementTime = time();
		}

		$bill = $arrInput['bill'];
		if(empty($bill)){
			$bill = 0.0;
		}

		$billSettlement = $arrInput['bill_settlement'];
		if (empty($billSettlement)){
			$billSettlement = "";
		}

		$comment = $arrInput['comment'];
		if (empty($comment)){
			$comment = "";
		}

		$quick_outstock = 0;
		$quick_outstock = $arrInput['quick_outstock'];
		

		$services = $arrInput['services'];
		$servicesInfos = json_decode($services, true); //服务单详情

		$settlementEx = $arrInput['settlement_ex'];
		if (empty($settlementEx)){
			$settlementEx = "";
		}
		$settlementAmountEx = $arrInput['settlement_amount_ex'];
		if (empty($settlementAmountEx)){
			$settlementAmountEx = 0.0;
		}

		////end////
		//var_dump($arrInput);

		//edited by wuweijia
		$is_new_form = true;

		////服务单状态块
		$mobileServiceFormDao = new Dao_MobileServiceForm(Tool_Const::$storeId);
		$mobileServiceFormDao->startTransaction();
		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo(Tool_Const::$storeId);
		$userInfoDao = new Dao_UserInfo();
		$carInfoDao = new Dao_CarInfo();
		//会员卡扣除
		if ($done == 1 && $settlement == "会员卡支付"){
			$ret = self::handleMemCardInfo($plateNumber, $settlementAmount);
			if ($ret == -2){
				return Tool_Util::returnJson('', 2, "会员卡余额不足,请充值");
			}
		}
		if ($done == 1 && $settlement == "联合结算"){
			$ret = self::handleMemCardInfoEx($plateNumber);
		}
		$carInfo = $carInfoDao->getInfoByPlateNumber($plateNumber);
		if (empty($carInfo)){
			return Tool_Util::returnJson('', 2, "无法获取到车辆信息");
		}
		$userId = $carInfo['owner_id'];
		if ($formId < 0){ //新服务单
			if ($done == 0){ //挂单
				$arr = array(
					'user_id' => $userId,
					'plate_number' => $plateNumber,
					'picker' => $picker,
					'person' => $person,
			   		'time' => date('y-m-d H:i:s',time()),
					'price' => $price,
					'bill' => $bill,
					'bill_settlement' => $billSettlement,
					'comment' => $comment,
					'status' => $done,
				);
			}
			else {//直接结单
				$arr = array(
					'user_id' => $userId,
					'plate_number' => $plateNumber,
					'picker' => $picker,
					'comment' => $comment,
					'settlement' => $settlement,
					'settlement_ex' => $settlementEx,
					'settlement_amount_ex' => $settlementAmountEx,
					'person' => $person,
					'price' => $price,
					'discount' => $discount,
			   		'time' => date('y-m-d H:i:s',time()),
					'settlement_amount' => $settlementAmount,
			   		'settlement_time' => date('y-m-d H:i:s',time()),
					'bill' => $bill,
					'bill_settlement' => $billSettlement,
					'status' => $done,
				);
				
			}
			$formId = $mobileServiceFormDao->setServiceForm($arr);
			if ($formId == 0){
				$mobileServiceFormDao->rollback();
				if ($settlement == "会员卡支付"){
					//结单失败,需把扣除款补回
					//$ret = self::handleMemCardInfo($plateNumber, -$settlementAmount);
				}
				return Tool_Util::returnJson('', 3, "服务单生成失败");
			}
		}
		else { //已存在的服务单
			$is_new_form = false;
			$arr = array(
				'settlement' => $settlement,
				'settlement_ex' => $settlementEx,
				'settlement_amount_ex' => $settlementAmountEx,
				'person' => $person,
				'price' => $price,
				'picker' => $picker,
				'discount' => $discount,
				'settlement_amount' => $settlementAmount,
			   	'settlement_time' => date('y-m-d H:i:s',time()),
				'bill' => $bill,
				'bill_settlement' => $billSettlement,
				'status' => $done,
			);
			//废弃
			if ($done == 2){
				$serviceForm = $mobileServiceFormDao->getServiceFormById($formId);
				if ($serviceForm['status'] == 1){
					return Tool_Util::returnJson('', 1, "结算单无法废弃");
				}
			}
			$ret = $mobileServiceFormDao->updateServiceForm($arr, $formId);
			if (!empty($servicesInfos) ){
				$mobileServiceFormInfoDao->deleteServiceFormInfo($formId);
			}
		}

		////详情更新////
		$ret = self::handleServiceInfo($servicesInfos, $formId, $done);
		if ($ret < 0){
			//$mobileServiceFormInfoDao->deleteServiceFormInfo($formId);
			//$mobileServiceFormDao->deleteServiceForm($formId);
			$mobileServiceFormDao->rollback();
			if ($ret == -3){
				return Tool_Util::returnJson('套餐卡无额度', 1);
			}
			return Tool_Util::returnJson('插入详细信息失败', 1);
		}
		$mobileServiceFormDao->commit();
		//结算时发送消息
		Bd_Log::warning("done:".$done);
		if ($done == 1){
			//云云车模板
			//$tempId = 'HcwsV1zkXB_HTuHVmoI2odseV3_9ofhcRlGLDocs1V8';
			//新时速模板
			$tempId = '4ZwNObUzHMZETvg2cqLohrtzkBGCI0BjkzKr_q5Yya4';
			$res = self::sendSettlementMsg($userId, $tempId, $settlement, $bill, $settlementAmount, $formId, $plateNumber);
			Bd_Log::warning("sendmsg:".$res);
		}
		//挂单或者单据更新时,发送微信消息
		else {
			//云云车模板
			//$tempId = '6_dJ16wyczvdUrJzLSD41z4AupL2zXd4Rq8nmbe56y8';
			//新时速模板
			$tempId = 'Vdcq_C9NiCLM7Ln-h_TiBa9RAUnIWM5mRq6r6lHXhQ4';
			$res = self::sendProcessMsg($userId, $tempId, $formId, $plateNumber, $servicesInfos);
			Bd_Log::warning("sendmsg:".$res);
		}

		// added by wuweijia
		
		// /*
		//if ($done == 1){
		$service_goods_id_list = array();
		$service_type = 0;//0 service, 1 goods or goods+service

		if ($done ==0 )
		{
			//demand counter ++
			$quick_outstock = 1;
			$db_client  = new Dao_DBbase();


			foreach($servicesInfos as $s_detail)
			{
				$s_item = array();
				$type = $s_detail['type'];
				if ($type==1)
				{
					$service_type = 1;
					array_push($service_goods_id_list,$s_detail['service_id']);

					$demand_count = $s_detail['count'];

					$goods_select = $db_client->select('model_goods_info','*',' goods_id = '.$s_detail['service_id'],NULL,NULL);
					$instock_count = 0;
					if($goods_select!==false and count($goods_select)>0)
					{
						if($is_new_form)
						{
							/*
							$new_demand_count = $goods_select[0]['service_demand'] + $demand_count;
							$demand_update_param = array(
								'service_demand' => $new_demand_count,
							);
							$demand_update_result = $db_client->update('model_goods_info',$demand_update_param,' goods_id = '.$s_detail['service_id'],NULL,NULL);
							*/
							
							$demand_insert_param = array(
								'goods_id' => $s_detail['service_id'],
								'service_id' => $formId,
								'demand_count' => $demand_count,
								'plate_number' => $plateNumber,
								'update_time' => date('Y-m-d H:i:s',time()),
							);
							$demand_insert_result = $db_client->insert('model_goods_demand',$demand_insert_param);
							

						}
						else
						{
							//func2 TODO
							//new item or new count
							$demand_select_result = $db_client->select('model_goods_demand','*',' service_id ='.$formId.' and goods_id ='.$s_detail['service_id'],NULL,NULL);
							//var_dump($demand_select_result);
							if($demand_select_result!==false)
							{
								if(count($demand_select_result)>0)
								{
									if ($demand_count!=$demand_select_result[0]['demand_count'])
									{
										$demand_update_param = array(
											'demand_count' => $demand_count,
											'update_time' => date('Y-m-d H:i:s',time()),
										);
										$demand_update_result = $db_client->update('model_goods_demand',$demand_update_param,' service_id ='.$formId.' and goods_id='.$s_detail['service_id'],NULL,NULL);
									}
								}
								else
								{
									$demand_insert_param = array(
										'goods_id' => $s_detail['service_id'],
										'service_id' => $formId,
										'demand_count' => $demand_count,
										'plate_number' => $plateNumber,
										'update_time' => date('Y-m-d H:i:s',time()),
									);
									$demand_insert_result = $db_client->insert('model_goods_demand',$demand_insert_param);
									//var_dump($demand_insert_result);
								}
							}
						}

						$instock_count = $goods_select[0]['instock_count'];
						if($instock_count < $demand_count)
						{
							$quick_outstock = 0;
							//break;
						}
					}
					else
					{
						// goods select empty
					}
				}
				else
				{
					//type != 1
					//$form_type
				}
			}



			//func1 TODO
			$service_goods_id_str = implode(',',$service_goods_id_list);
			$delete_id_result = $db_client->select('model_goods_demand','goods_id',' service_id ='.$formId.' and goods_id not in ('.$service_goods_id_str.')',NULL,NULL);
			if($delete_id_result!==false and count($delete_id_result)>0)
			{
				$delete_id_list = array();
				foreach($delete_id_result as $delete_id)
				{
					array_push($delete_id_list,$delete_id['goods_id']);
				}
				$delete_id_str = implode(',',$delete_id_list);
				$delete_result = $db_client->delete('model_goods_demand','goods_id in ('.$delete_id_str.')');
				if($delete_result!==false and count($delete_result)>0)
				{
					//succ
				}
			}



			$verify_status = 0;
			if($service_type==0)
			{
				$verify_status = -1;
				$quick_outstock = 0;
			}
			$service_form_status_params = array(
				'quick_outstock' => $quick_outstock,
				'verify_status' => $verify_status,
			);
			$quick_outstock_status_params = array(
				'quick_outstock' => $quick_outstock,
			);
			$result_update = $db_client->update('model_mobile_service_form',$service_form_status_params,' form_id="'.$formId.'"',NULL,NULL);
			$result_update = $db_client->update('model_mobile_service_form_info',$quick_outstock_status_params,' form_id="'.$formId.'"',NULL,NULL);
			$mobileServiceFormDao->commit();
			if($result_update!==false and $result_update>0)
			{
			//var_dump($formId);
			//var_dump($result_update);
			}

		}
		//if ($done == 1 and $quick_outstock == 1){
		if($done==1)
		{
			$db_client  = new Dao_DBbase();

			$outstock_param = array();

			$admin_info = Tool_Const::$adminInfo;
			$admin_name = $admin_info['name'];
			//$admin_name = '新时速';
			$outstock_param['author'] = $admin_name;

			$admin_store_id = $admin_info["store_id"];
			//$admin_store_id = '新时速-美车汇';
			$outstock_param['warehouse_id'] = $admin_store_id;

			//$form_status = Tool_Const::$Outstock_status["reviewed"];
			if($quick_outstock==1)
			{
				$form_status = 'reviewed';
			}
			else
			{
				$form_status = 'reviewing';
			}
			$outstock_param['shipment_status'] = $form_status;
			$outstock_param['form_status'] = $form_status;

			$time = date('Y-m-d H:i:s');
			$outstock_param['time'] = $time;

			$outstock_param['outstock_type'] = 'maintenance';
			//$outstock_param['storehouse_id'] = $admin_store_id;
			//$outstock_param['storehouse_name'] = $admin_store_id;
			$outstock_param["outstock_warehouse"] = $admin_store_id;
			$outstock_param["outstock_department"] = $admin_store_id;
			$outstock_param['department'] = $admin_store_id;
			$outstock_param['delivery_man'] = '';
			$outstock_param['remarks'] = '';
			$outstock_param['warehouse_auditor'] = $admin_name;

			$outstock_param['plate_number'] = $plateNumber;

			$total_num = 0;
			$total_price = 0;
			$total_net_price = 0;

			$outstock_param['outstock_items'] = '';
			$tmp_outstock_items = array();
			
			//var_dump($servicesInfos);
			foreach($servicesInfos as $s_detail)
			{
				$s_item = array();
				$type = $s_detail['type'];
				if ($type==1)
				{
					$service_type = 1;
					array_push($service_goods_id_list,$s_detail['service_id']);
					$total_num += $s_detail['count'];
					$total_price += (float)$s_detail['cost'];

					$s_item['goods_id'] = $s_detail['service_id'];
					$goods_select = $db_client->select('model_goods_info','*',' goods_id = '.$s_detail['service_id'],NULL,NULL);
					$storage_price = 0;
					if($goods_select!==false and count($goods_select)>0)
					{
						$storage_price = $goods_select[0]['instock_avg'];
						$goods_select_name = $goods_select[0]['name'];
					}
					//var_dump($goods_select);
					//cost 单价
					$s_item['count'] = $s_detail['count'];
					$s_item['storage_price'] = $storage_price;
					$s_item['shipment_price'] = (float)$s_detail['cost'];
					$s_item['price'] = $s_item['shipment_price'] * $s_item['count'];
					$s_item['net_price'] = ($s_item['shipment_price'] - $s_item['storage_price'] ) * $s_item['count'];
					$s_item['remarks'] = '';
					$s_item['name'] = $goods_select_name;

					$total_net_price += $s_item['net_price'];

					array_push($tmp_outstock_items,$s_item);


					//func2 TODO
					$demand_count = $s_detail['count'];

					//new item or new count
					$demand_select_result = $db_client->select('model_goods_demand','*',' service_id ='.$formId.' and goods_id ='.$s_detail['service_id'],NULL,NULL);
					if($demand_select_result!==false)
					{
						if(count($demand_select_result)>0)
						{
							if ($demand_count!=$demand_select_result[0]['demand_count'])
							{
								$demand_update_param = array(
									'demand_count' => $demand_count,
									'update_time' => date('Y-m-d H:i:s',time()),
								);
								$demand_update_result = $db_client->update('model_goods_demand',$demand_update_param,' service_id ='.$formId.' and goods_id='.$s_detail['service_id'],NULL,NULL);
							}
						}
						else
						{
							$demand_insert_param = array(
								'goods_id' => $s_detail['service_id'],
								'service_id' => $formId,
								'demand_count' => $demand_count,
								'plate_number' => $plateNumber,
								'update_time' => date('Y-m-d H:i:s',time()),
							);
							$demand_insert_result = $db_client->insert('model_goods_demand',$demand_insert_param);
						}
					}
					else
					{
								//
					}
				}
				else
				{
					//type!=1
				}

			}//end foreach

			//func1  TODO
			$service_goods_id_str = implode(',',$service_goods_id_list);
			$delete_id_result = $db_client->select('model_goods_demand','goods_id',' service_id ='.$formId.' and goods_id not in ('.$service_goods_id_str.')',NULL,NULL);
			if($delete_id_result!==false and count($delete_id_result)>0)
			{
				$delete_id_list = array();
				foreach($delete_id_result as $delete_id)
				{
					array_push($delete_id_list,$delete_id['goods_id']);
				}
				$delete_id_str = implode(',',$delete_id_list);
				$delete_result = $db_client->delete('model_goods_demand','goods_id in ('.$delete_id_str.')');
				if($delete_result!==false and count($delete_result)>0)
				{
					//succ
				}
			}


			if (count($tmp_outstock_items)>0)
			{

				$outstock_items_str = json_encode($tmp_outstock_items);
				$outstock_param['outstock_items'] = $outstock_items_str;

				$outstock_param['total_count'] = $total_num;
				$outstock_param['total_net_price'] = $total_net_price;
				$outstock_param['total_price'] = $total_price;
				$outstock_param['outstock_plan'] = $formId;
				$outstock_param['admin_name'] = $admin_info['name'];

				//var_dump($outstock_param);

				$objServicePageLogcheck = new Service_Page_StockMag();
				if($quick_outstock==1)
				{
					/*
					$demand_update_param = array(
							'service_demand' => $demand_count,
						);
					$demand_update_result = $db_client->update('model_goods_info',$demand_update_param,' goods_id = '.$s_detail['service_id'],NULL,NULL);
					*/
					$arrPageInfo = $objServicePageLogcheck->execute_ins_outstock_quick($outstock_param);
				}
				else
				{
					$arrPageInfo = array();
					$arrPageInfo['errno'] = 0;
					//$arrPageInfo = $objServicePageLogcheck->execute_ins_outstock($outstock_param);
				}
				//var_dump($arrPageInfo);
				//var_dump($formId);
				if($arrPageInfo['errno']!==0)
				{
					return Tool_Util::returnJson($formId, -1, '提交成功 但 商品自动出库失败');
				}
				else
				{
					
					if($quick_outstock==1)
					{
						$verify_status = 0;
						if($service_type==0)
						{
							$verify_status = -1;
						}
						$service_form_status_params = array(
							'quick_outstock' => 1,
						);
						$quick_outstock_status_params = array(
							'quick_outstock' => 1,
						);
						$result_update = $db_client->update('model_mobile_service_form',$service_form_status_params,' form_id="'.$formId.'"',NULL,NULL);
						$result_update = $db_client->update('model_mobile_service_form_info',$quick_outstock_status_params,' form_id="'.$formId.'"',NULL,NULL);
						$mobileServiceFormDao->commit();
						//$result_update = $db_client->update('model_mobile_service_form',$tmp_params,$tmp_condition,NULL,NULL);
						//var_dump($result_update);
						if($result_update!==false and $result_update>0)
						{
							//var_dump($formId);
							//var_dump($result_update);
						}
					}
				}
				$logCheckResult = $arrPageInfo['data'];
			}
			else
			{
				$verify_status = 0;
				if($service_type==0)
				{
					$verify_status = -1;
				}
				$service_form_status_params = array(
					'verify_status' => $verify_status,
				);
				$result_update = $db_client->update('model_mobile_service_form',$service_form_status_params,' form_id="'.$formId.'"',NULL,NULL);
				$mobileServiceFormDao->commit();
				if($result_update!==false and $result_update>0)
				{
					//var_dump($formId);
				}
			}
		}//end done=1
		
		//参数返回
		return Tool_Util::returnJson($formId, 0, '提交成功');
	}

	//更新详情
	public static function handleServiceInfo($servicesInfos, $formId, $done){
		////详情更新////
		$mobileServiceFormInfoDao = new Dao_MobileServiceFormInfo(Tool_Const::$storeId);
		foreach($servicesInfos as $servicesInfo){
			$serviceId = $servicesInfo['service_id'];
			if (empty($serviceId)){
				$serviceId = "";
			}
			$serviceName = $servicesInfo['type3_name'];
			if (empty($serviceName)){
				$serviceName = "";
			}
			$price = $servicesInfo['price'];
			if (empty($price)){
				$price = 0;
			}
			$cost = $servicesInfo['cost'];
			if(empty($cost)){
				$cost = 0;
			}
			$constructer = $servicesInfo['constructer'];
			if (empty($constructer)){
				$constructers = "";
			}
			else{
				$con = json_decode($constructer, true);
				if (empty($con)){
					$constructers = "";
				}
				else{
					$constructers = implode(",", $con);
				}
			}
			$count = $servicesInfo['count'];
			if (empty($count)){
				$count = 0;
			}
			$remarks = $servicesInfo['remarks'];
			if (empty($remarks)){
				$remarks = "";
			}
			$type = $servicesInfo['type'];
			if (empty($type)){
				$type = 0;
			}
			$packageCardId = $servicesInfo['package_card_id'];
			if (empty($packageCardId)){
				$packageCardId = -1;
			}
			$form = array(
				'form_id' => $formId,
				'service_id' => $serviceId,
				'service_name' => $serviceName,
				'price' => $price,
				'cost' => $cost,
				'constructer' => $constructers,
				'count' => $count,
				'remarks' => $remarks,
				'type' => $type,
				'package_card_id' => $packageCardId,
				'status' => $done,
			);
			Bd_Log::warning("info:".json_encode($form));
			//结单时,套餐卡项目扣除
			if ($done == 1 && $packageCardId > -1){
				$ret = self::handlePackageCardInfo($packageCardId, $serviceName);
				if ($ret < 0){
					return -3;
				}
			}
			$ret = $mobileServiceFormInfoDao->setServiceFormInfo($form);
			if (!$ret){
				return -1;
			}
		}
		return 0;
	}

	//扣除会员卡
	public static function handleMemCardInfo($plateNumber, $settlementAmount){
	
		$userInfoDao = new Dao_UserInfo();
		$carInfoDao = new Dao_CarInfo();
		$carInfo = $carInfoDao->getInfoByPlateNumber($plateNumber);
		if (empty($carInfo)){
			return -1;
			//return Tool_Util::returnJson('', 2, "无法获取到车辆信息");
		}
		$userId = $carInfo['owner_id'];
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
			//return Tool_Util::returnJson('', 2, "无法获取到用户信息");
		}
		$memberCardBalance = $userInfo['member_card_balance'];
		if ($memberCardBalance < $settlementAmount){
			return -2;
			//return Tool_Util::returnJson('', 2, "会员卡余额不足,请充值");
		}
		$memberCardBalance = $memberCardBalance - $settlementAmount;
		$fileds = array('member_card_balance' => $memberCardBalance);
		$ret = $userInfoDao->updateUserInfo($fileds, $userId);
		return 0;
	}

	//清空会员卡金额
	public static function handleMemCardInfoEx($plateNumber, $settlementAmount){
		$userInfoDao = new Dao_UserInfo();
		$carInfoDao = new Dao_CarInfo();
		$carInfo = $carInfoDao->getInfoByPlateNumber($plateNumber);
		if (empty($carInfo)){
			return -1;
			//return Tool_Util::returnJson('', 2, "无法获取到车辆信息");
		}
		$userId = $carInfo['owner_id'];
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
			//return Tool_Util::returnJson('', 2, "无法获取到用户信息");
		}
		$memberCardBalance = $userInfo['member_card_balance'];
		if ($memberCardBalance < $settlementAmount){
			return -2;
			//return Tool_Util::returnJson('', 2, "会员卡余额不足,请充值");
		}
		$memberCardBalance = $memberCardBalance - $settlementAmount;
		$fileds = array('member_card_balance' => $memberCardBalance);
		//$fileds = array('member_card_balance' => 0);
		$ret = $userInfoDao->updateUserInfo($fileds, $userId);
		return 0;
	}

	//扣除套餐卡
	public static function handlePackageCardInfo($packageCardId, $serviceName){
		$packageCardInfoDao = new Dao_PackageCardInfo();
		$packageCardInfo = $packageCardInfoDao->getPackageInfoByIdName($packageCardId, $serviceName);
		$itemLeftCounts = $packageCardInfo['item_left_counts'];
		$itemLeftCounts = $itemLeftCounts - 1;
		if ($itemLeftCounts < 0){
			return -1;
			//return Tool_Util::returnJson("", 1, "套餐扣除失败");//扣除异常
		}
		$ret = $packageCardInfoDao->setPackageInfoCounts($packageCardId, $serviceName, $itemLeftCounts);
		if (!$ret){
			return -1;
			//return Tool_Util::returnJson($serviceName, $itemLeftCounts, $packageCardId);
		}
		return 0;
	}

	//结算消息发送
	public static function sendSettlementMsg($userId, $tempId, $settlement, $bill, $settlementAmount, $formId, $plateNumber){
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
		}
		$openId = $userInfo['wechat_num'];
		if (empty($openId)){
			return -2;
		}

		$template = array(
				'touser' => $openId,
				'template_id' => $tempId,
				'url' => 'http://yunyunche.cn/mobileservicedetail/storeId/56?form_id='.$formId,
				'topcolor' => '#7B68EE',
				'data' => array(
					'first' => array('value' => '敬爱的'.$plateNumber.'车主,您的车辆已经结算',
					'color' => '#173177',
					),
				"keyword1" => array(
					"value" => $plateNumber,
					"color" => "#173177"
					),
				"keyword2" => array(
					"value" => "MT00000".$formId,
					"color" =>"#173177"
					),
				"keyword3" => array(
					"value" => date('y-m-d H:i:s',time()),
					"color" => "#173177"
					),
				"keyword4" => array(
					"value" => $settlement.":".$settlementAmount.","."预付".":".$bill,
					"color" => "#173177"
					),
				"keyword5" => array(
					"value" => $settlementAmount + $bill,
					"color" => "#173177"
					),
				"remark" => array(
					"value" =>"谢谢您的惠顾",
					"color" => "#173177"
					)

				)
			);

		Bd_Log::warning("template:".json_encode($template));
		$result = Tool_WeiXin::sendTempMsg_X($template);
		Bd_Log::warning("template:".json_encode($result));
		return $result;
	}

	public static function sendProcessMsg($userId, $tempId, $formId, $plateNumber, $servicesInfos){
		if (empty($servicesInfos)){
			return -1;
		}
		$serviceNames = "";
		foreach($servicesInfos as $servicesInfo){
			$serviceName = $servicesInfo['type3_name'];
			if (!empty($serviceName)){
				if ($serviceNames == ""){
					$serviceNames = $serviceName;
				}
				else {
					$serviceNames = $serviceNames.",".$serviceName;
				}
			}
		}
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
		}
		$openId = $userInfo['wechat_num'];
		if (empty($openId)){
			return -2;
		}
		$template = array(
				'touser' => $openId,
				'template_id' => $tempId,
				'url' => '',
				'topcolor' => '#7B68EE',
				'data' => array(
					'first' => array('value' => '敬爱的'.$plateNumber.'车主,您的维修单已更新',
					'color' => '#173177',
					),
				"keyword1" => array(
					"value" => "MT00000".$formId,
					"color" =>"#173177"
					),
				"keyword2" => array(
					"value" => $serviceNames."=>"."进行中",
					"color" => "#173177"
					),
				"remark" => array(
					"value" =>"谢谢您的惠顾,有任何疑问请咨询相关服务人员",
					"color" => "#173177"
					)

				)
			);
		Bd_Log::warning("template:".json_encode($template));
		$result = Tool_WeiXin::sendTempMsg_X($template);
		Bd_Log::warning("template:".json_encode($result));
		return $result;
	}
}
