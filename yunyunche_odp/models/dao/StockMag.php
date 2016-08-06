<?php
/**
 * @name Dao_Logcheck
 * @desc sample dao, 可以访问数据库，文件，其它系统等
 * @author 吴伟佳
 */
//Bd_Init::init();

class Dao_StockMag extends Dao_Base{

	private $db_client;

    public function __construct(){
		parent::__construct();
		$this->db_client = new Dao_DBbase();
    }   

	public function doInsInstock($arrInput){
		
		/*
		 *  param header
		 */  
		//$instock_id = $arrInput["instock_id"];
		$author = $arrInput["author"];
		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$instock_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		$admin_info = Tool_Const::$adminInfo;
		$instock_status = Tool_Const::$Instock_status["reviewing"];
		$author = $admin_info["name"];
		$warehouse_id = $admin_info["store_id"];
		$datetime = date('Y-m-d H:i:s');
		*/

		/*
		 *  param body
		 */ 
		$instock_plan = (int)$arrInput["instock_plan"]; //TODO
		$instock_type = $arrInput["instock_type"];
		$instock_warehouse = $arrInput["instock_warehouse"];
		$instock_department = $arrInput["instock_department"];
		$warehouse_auditor = $arrInput["warehouse_auditor"];
		$purchaser = $arrInput["purchaser"];
		$remarks = $arrInput["remarks"];
		$plate_number = $arrInput["plate_number"];

		$sum_price = (float)$arrInput["sum_price"];
		$sum_count = $arrInput["sum_count"];



		/*
		 * insert form msg
		 */ 
		$param = array(
				'storage_type' => $instock_type,
				'storehouse_id' => $warehouse_id,
				'storehouse_name' => $instock_warehouse,
				'department' => $instock_department,
				//'censor' => $warehouse_auditor,  // reviewed by current user  TODO
				'purchaser' => $purchaser,
				'remarks' => $remarks,
				'maintance_id' => $instock_plan,
				'service_form_id' => $instock_plan,
				'plate_number' => $plate_number,

				'total_num' => $sum_count,
				'total_price' => $sum_price,
				'time' => $datetime,

				'storage_status' => $instock_status,
				'author' => $author,
			);
		/*
		if(isset($arrInput['stock_flow_state']) and (int)$arrInput['stock_flow_state']>=0 )
		{
			$param['stock_flow_state'] = (int)$arrInput['stock_flow_state'];
		}
		*/
		
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_storage'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_storage'),$param);
		$result_select = $this->db_client->getInsertID();

		$instock_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		$storage_abs = array();
		if($result_insert!=false && $instock_id!=false)
		{
			if($instock_id>0)
			{
				$instock_items = $arrInput["instock_items"];
				//var_dump($instock_items);
				$instock_items_dict = json_decode($instock_items,true);
				$succ_flag = false;
				foreach($instock_items_dict as $item)
				{
					/*
					 * add new item
					 */ 
					$item_id = $item["goods_id"];
					if(isset($item['name']) and $item['name']!="")
					{
						$item_name = $item['name'];
						//var_dump($item_name);
						//var_dump(urlencode($item_name));

						//$storage_abs[$item_name] = 0;
					}

					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							// 新item自动插入
							// 插入的方式需要review TODO

							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/

					$count = $item["count"];
					$unit_price = $item["unit_price"];
					//$sum = $item["sum"];
					$sum = $count*$unit_price;
					$settlement = $item["settlement"];
					$remarks = $item["remarks"];

					/*
					 * add new supplier
					 */ 
					//$supplier_name = $item["supplier_name"];
					$sales_quote = $item['sales_quote'];
					$supplier_id = $item["supplier_id"];
					/*
					if ($supplier_id!=NULL and $supplier_id!="")
					{
					}
					else{
						$supplier_result_select = $this->db_client->select('supplier_info','supplier_id',' supplier_name="'.$supplier_name.'"',NULL,NULL);
						if ( count($supplier_result_select)!=0 )
						{
							$supplier_id = $supplier_result_select[0]['supplier_id'];
						}
						else
						{
							$supplier_param = array(
									'supplier_name' => $supplier_name,
								);
							$supplier_result_insert = $this->db_client->insert('supplier_info',$supplier_param);
							$supplier_result_insert_id = $this->db_client->getInsertID();
							if ($supplier_result_insert_id!=NULL and $supplier_result_insert_id!=false)
							{
								$supplier_update_param = array(
									'super_id' => $supplier_result_insert_id,
								);
								$supplier_result_update = $this->db_client->update('supplier_info',$supplier_update_param,' supplier_id='.$supplier_result_insert_id,NULL,NULL);
								$supplier_id = $supplier_result_insert_id;
							}
							else{
								$supplier_id = NULL;
							}
						}
					}	
					*/
					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,
							'supplier_id' => $supplier_id,

							'unit_price' => $unit_price,
							'number' => $count,
							'price' => $sum,

							'settlement' => $settlement,
							'info' => $remarks,
							'storage_id' => $instock_id,
							'sales_quote' => $sales_quote,

							'time' => $datetime,
							'stat' => $count,
						);
					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_storage_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							/*
							 * update goods_info with num and price
							 */

							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] + $count;
									$new_price = $curr_item['instock_price'] + $count*$unit_price;
									$new_avg = $new_price*1.0/$new_count;

									$storage_abs[$curr_item['name']] = 0;

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_storage_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
								
									/* do it on review action TODO
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update('goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
									}
									*/

									$succ_flag = true;
									break;
								}
								else{
									//log
									Bd_Log::warning("no goods");
									break;
								}
							}	
							//$succ_flag = true;
							break;
						}
						else
						{
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error,item id error",
						'form_id' => $instock_id,
						'codeMsg' => 'error, insert item error',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "instock_id error",
					'form_id' => $instock_id,
					'codeMsg' => 'error, instock_id error',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert instock error",
				'form_id' => NULL,
				'codeMsg' => 'error, insert instock error',
				'code' => 1
			);
			return $return_arr;
		}

		$storage_abs_array = array_keys($storage_abs);
		$storage_abs_str = implode(',',$storage_abs_array);
		$item_update_param = array(
				'storage_abs' => $storage_abs_str,
			);
		$result_update = $this->db_client->update(Tool_Util::getStoreTable('goods_storage'),$item_update_param,' storage_id='.$instock_id,NULL,NULL);



		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $instock_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}


	public function doInsOutstock_quick($arrInput){
		
		/*
		 *  param header
		 */  
		//$outstock_id = $arrInput["outstock_id"];
		$author = $arrInput["author"];
		$admin_name = $arrInput["admin_name"];

		//$admin_info = Tool_Const::$adminInfo;
		//$author = $admin_info['name'];
		//var_dump($author);


		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$outstock_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		$admin_info = Tool_Const::$adminInfo;
		$outstock_status = Tool_Const::$Outstock_status["reviewing"];
		$author = $admin_info["name"];
		$warehouse_id = $admin_info["store_id"];
		$datetime = date('Y-m-d H:i:s');
		*/

		/*
		 *  param body
		 */ 
		$outstock_plan = (int)$arrInput["outstock_plan"];
		$outstock_type = $arrInput["outstock_type"];
		$outstock_warehouse = $arrInput["outstock_warehouse"];
		$outstock_department = $arrInput["outstock_department"];
		$warehouse_auditor = $arrInput["warehouse_auditor"];
		$operator = $arrInput["delivery_man"];
		$plate_number = $arrInput["plate_number"];

		$remarks = $arrInput["remarks"];

		$sum_price = (float)$arrInput["total_price"];
		$sum_net_price = (float)$arrInput["total_net_price"];
		$sum_count = $arrInput["total_count"];

		/*
		 * insert
		 */ 
		$param = array(
				'shipment_type' => $outstock_type,
				'storehouse_id' => $warehouse_id,
				'storehouse_name' => $outstock_warehouse,
				'department' => $outstock_department,
				//'censor' => $warehouse_auditor,  // reviewed by current user TODO
				'operator' => $operator,
				'remarks' => $remarks,
				'maintance_id' => $outstock_plan,
				'plate_number' => $plate_number,

				'total_num' => $sum_count,
				'total_net_price' => $sum_net_price,
				'total_price' => $sum_price, //TODO who computes? 
				'time' => $datetime,
				'review_time' => $datetime,
				'censor' => $admin_name,

				'shipment_status' => $outstock_status,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment'),$param);
		$result_select = $this->db_client->getInsertID();

		$outstock_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		$storage_abs = array();
		if($result_insert!=false && $outstock_id!=false)
		{
			if($outstock_id>0)
			{
				$outstock_items = $arrInput["outstock_items"];
				//var_dump($outstock_items);
				$outstock_items_dict = json_decode($outstock_items,true);
				$succ_flag = false;
				foreach($outstock_items_dict as $item)
				{
					/*
					 * add new item
					 */ 
					$item_id = $item["goods_id"];
					if(isset($item['name']) and $item['name']!="")
					{
						$item_name = $item['name'];
						$storage_abs[$item_name] = 0;
					}



					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							// 插入的方式需要review TODO
							
							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/

					$count = $item["count"];
					$storage_price = $item["storage_price"]; // get from goods_info
					$shipment_price = $item["shipment_price"];
					//$sum = $item["sum"]; //TODO sum what?
					//$sum = ( $shipment_price - $storage_price ) * $count;
					$sum = $item["price"];
					$sum_net = $item["net_price"];
					//$settlement = $item["settlement"];
					$remarks = $item["remarks"];

					/*
					 * add new supplier
					 */ 
					/*
					$supplier_name = $item["supplier_name"];
					$supplier_id = $item["supplier_id"];
					if ($supplier_id!=NULL and $supplier_id!="")
					{
					}
					else{
						$supplier_result_select = $this->db_client->select('supplier_info','supplier_id',' supplier_name="'.$supplier_name.'"',NULL,NULL);
						if ( count($supplier_result_select)!=0 )
						{
							$supplier_id = $supplier_result_select[0]['supplier_id'];
						}
						else
						{
							$supplier_param = array(
									'supplier_name' => $supplier_name,
								);
							$supplier_result_insert = $this->db_client->insert('supplier_info',$supplier_param);
							$supplier_result_insert_id = $this->db_client->getInsertID();
							if ($supplier_result_insert_id!=NULL and $supplier_result_insert_id!=false)
							{
								$supplier_update_param = array(
									'super_id' => $supplier_result_insert_id,
								);
								$supplier_result_update = $this->db_client->update('supplier_info',$supplier_update_param,' supplier_id='.$supplier_result_insert_id,NULL,NULL);
								$supplier_id = $supplier_result_insert_id;
							}
							else{
								$supplier_id = NULL;
							}
						}
					}	
					*/

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,

							'storage_price' => $storage_price,
							'shipment_price' => $shipment_price,
							'number' => $count,
							'price' => $sum,
							'net_price' => $sum_net,

							'info' => $remarks,
							'shipment_id' => $outstock_id,

							'time' => $datetime,
						);
					$succ_flag = false;
					//var_dump($param);
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							/*
							 * update goods_info with num and price
							 */
							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'*',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] - $count;
									$new_price = $curr_item['instock_price'] - $count*$curr_item['instock_avg'];
									$new_avg = $new_price*1.0/$new_count;

									$new_service_demand = $curr_item['service_demand'] - $count;

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_shipment_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);

									///* do it on review action 
									// quick ins instock, do not need to review
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
											'service_demand' =>  $new_service_demand,
										);
									$result_update = $this->db_client->update('model_goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
										Bd_Log::warning('update goods count and price error');
									}
									//*/
									$succ_flag = true;
									break;
								}
								else{
									//log
									Bd_Log::warning("no goods");
									break;
								}
							}	
							//$succ_flag = true;
							//Bd_Log::warning("3wu");
							break;
						}
					}
					if ($succ_flag == false)
					{
						//Bd_Log::warning("2wu");
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $outstock_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "outstock_id error",
					'form_id' => $outstock_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert outstock error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}


		$storage_abs_array = array_keys($storage_abs);
		$storage_abs_str = implode(',',$storage_abs_array);
		$item_update_param = array(
				'storage_abs' => $storage_abs_str,
			);
		$result_update = $this->db_client->update(Tool_Util::getStoreTable('goods_shipment'),$item_update_param,' shipment_id='.$outstock_id,NULL,NULL);
		//var_dump($item_update_param);
		//var_dump($result_update);

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $outstock_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}


	public function doInsOutstock($arrInput){
		
		/*
		 *  param header
		 */  
		//$outstock_id = $arrInput["outstock_id"];
		$author = $arrInput["author"];

		//$admin_info = Tool_Const::$adminInfo;
		//$author = $admin_info['name'];
		//var_dump($author);


		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$outstock_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		$admin_info = Tool_Const::$adminInfo;
		$outstock_status = Tool_Const::$Outstock_status["reviewing"];
		$author = $admin_info["name"];
		$warehouse_id = $admin_info["store_id"];
		$datetime = date('Y-m-d H:i:s');
		*/

		/*
		 *  param body
		 */ 
		$outstock_plan = (int)$arrInput["outstock_plan"];
		$outstock_type = $arrInput["outstock_type"];
		$outstock_warehouse = $arrInput["outstock_warehouse"];
		$outstock_department = $arrInput["outstock_department"];
		$warehouse_auditor = $arrInput["warehouse_auditor"];
		$operator = $arrInput["delivery_man"];
		$plate_number = $arrInput["plate_number"];

		$remarks = $arrInput["remarks"];

		$sum_price = (float)$arrInput["total_price"];
		$sum_net_price = (float)$arrInput["total_net_price"];
		$sum_count = $arrInput["total_count"];

		/*
		 * insert
		 */ 
		$param = array(
				'shipment_type' => $outstock_type,
				'storehouse_id' => $warehouse_id,
				'storehouse_name' => $outstock_warehouse,
				'department' => $outstock_department,
				//'censor' => $warehouse_auditor,  // reviewed by current user TODO
				'operator' => $operator,
				'remarks' => $remarks,
				'maintance_id' => $outstock_plan,
				'plate_number' => $plate_number,

				'total_num' => $sum_count,
				'total_net_price' => $sum_net_price,
				'total_price' => $sum_price, //TODO who computes? 
				'time' => $datetime,

				'shipment_status' => $outstock_status,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment'),$param);
		$result_select = $this->db_client->getInsertID();

		$outstock_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		$storage_abs = array();
		if($result_insert!=false && $outstock_id!=false)
		{
			if($outstock_id>0)
			{
				$outstock_items = $arrInput["outstock_items"];
				//var_dump($outstock_items);
				$outstock_items_dict = json_decode($outstock_items,true);
				$succ_flag = false;
				foreach($outstock_items_dict as $item)
				{
					/*
					 * add new item
					 */ 
					$item_id = $item["goods_id"];
					if(isset($item['name']) and $item['name']!="")
					{
						$item_name = $item['name'];
						$storage_abs[$item_name] = 0;
					}

					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							// 插入的方式需要review TODO
							
							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/

					$count = $item["count"];
					$storage_price = $item["storage_price"]; // get from goods_info
					$shipment_price = $item["shipment_price"];
					//$sum = $item["sum"]; //TODO sum what?
					//$sum = ( $shipment_price - $storage_price ) * $count;
					$sum = $item["price"];
					$sum_net = $item["net_price"];
					//$settlement = $item["settlement"];
					$remarks = $item["remarks"];

					/*
					 * add new supplier
					 */ 
					/*
					$supplier_name = $item["supplier_name"];
					$supplier_id = $item["supplier_id"];
					if ($supplier_id!=NULL and $supplier_id!="")
					{
					}
					else{
						$supplier_result_select = $this->db_client->select('supplier_info','supplier_id',' supplier_name="'.$supplier_name.'"',NULL,NULL);
						if ( count($supplier_result_select)!=0 )
						{
							$supplier_id = $supplier_result_select[0]['supplier_id'];
						}
						else
						{
							$supplier_param = array(
									'supplier_name' => $supplier_name,
								);
							$supplier_result_insert = $this->db_client->insert('supplier_info',$supplier_param);
							$supplier_result_insert_id = $this->db_client->getInsertID();
							if ($supplier_result_insert_id!=NULL and $supplier_result_insert_id!=false)
							{
								$supplier_update_param = array(
									'super_id' => $supplier_result_insert_id,
								);
								$supplier_result_update = $this->db_client->update('supplier_info',$supplier_update_param,' supplier_id='.$supplier_result_insert_id,NULL,NULL);
								$supplier_id = $supplier_result_insert_id;
							}
							else{
								$supplier_id = NULL;
							}
						}
					}	
					*/

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,

							'storage_price' => $storage_price,
							'shipment_price' => $shipment_price,
							'number' => $count,
							'price' => $sum,
							'net_price' => $sum_net,

							'info' => $remarks,
							'shipment_id' => $outstock_id,

							'time' => $datetime,
						);
					$succ_flag = false;
					//var_dump($param);
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_shipment_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							/*
							 * update goods_info with num and price
							 */
							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price,instock_avg',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] - $count;
									$new_price = $curr_item['instock_price'] - $count*$curr_item['instock_avg'];
									$new_avg = $new_price*1.0/$new_count;


									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_shipment_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
									/* do it on review action TODO
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update('goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
									}
									*/
									$succ_flag = true;
									break;
								}
								else{
									//log
									Bd_Log::warning("no goods");
									break;
								}
							}	
							//$succ_flag = true;
							//Bd_Log::warning("3wu");
							break;
						}
					}
					if ($succ_flag == false)
					{
						Bd_Log::warning("2wu");
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $outstock_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "outstock_id error",
					'form_id' => $outstock_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert outstock error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$storage_abs_array = array_keys($storage_abs);
		$storage_abs_str = implode(',',$storage_abs_array);
		$item_update_param = array(
				'storage_abs' => $storage_abs_str,
			);
		$result_update = $this->db_client->update(Tool_Util::getStoreTable('goods_shipment'),$item_update_param,' shipment_id='.$outstock_id,NULL,NULL);

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $outstock_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}
	public function doInsTransfer($arrInput){
		/*
		 *  param header
		 */  
		//$instock_id = $arrInput["instock_id"];
		$author = $arrInput["author"];
		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$transfer_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		 *  param body
		 */ 
		$in_warehouse_id = $arrInput['in_warehouse_id'];
		$in_warehouse_name = $arrInput['in_warehouse_name'];
		$out_warehouse_id = $arrInput['out_warehouse_id'];
		$out_warehouse_name = $arrInput['out_warehouse_name'];
		$warehouse_auditor = $arrInput["warehouse_auditor"];
		$transfer_man = $arrInput['transfer_man'];
		$remarks = $arrInput['remarks'];
		$sum_price = (float)$arrInput["sum_price"];
		$sum_count = $arrInput["sum_count"];

		/*
		 * insert form msg
		 */ 
		$param = array(
				'in_warehouse_id' => $in_warehouse_id,
				'in_warehouse_name' => $in_warehouse_name,
				'out_warehouse_id' => $out_warehouse_id,
				'out_warehouse_name' => $out_warehouse_name,

				//'warehouse_auditor' => $warehouse_auditor,	// reviewed by current user TODO
				'transfer_man' => $transfer_man,

				'time' => $datetime,
				'sum_count' => $sum_count,
				'sum_price' => $sum_price,
				'author' => $author,
				'transfer_status' => $transfer_status,
				'remarks' => $remarks,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_transfer'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_transfer'),$param);
		$result_select = $this->db_client->getInsertID();

		$transfer_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		if($result_insert!=false && $transfer_id!=false)
		{
			if($transfer_id>0)
			{
				$transfer_items = $arrInput["transfer_items"];
				$transfer_items_dict = json_decode($transfer_items,true);	//TODO
				//$transfer_items_dict = $transfer_items;
				$succ_flag = false;
				foreach($transfer_items_dict as $item)
				{
					/*
					 * add new item
					 */ 
					//$item_id = $item["item_id"];
					//TODO
					$item_id = $item["goods_id"];
					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							// 新item自动插入
							// 插入的方式需要review TODO

							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/

					$count = $item["count"];
					$unit_price = $item["unit_price"];
					//$sum = $item["sum"];
					$sum = $count*$unit_price;
					$remarks = $item["remarks"];

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,
							'unit_price' => $unit_price,
							'sum_count' => $count,
							'sum_price' => $sum,
							'remarks' => $remarks,
							'transfer_id' => $transfer_id,
						);
					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_transfer_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							/*
							 * update goods_info with num and price
							 */
							//TODO

							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] + $count;
									$new_price = $curr_item['instock_price'] + $count*$unit_price;
									$new_avg = $new_price*1.0/$new_count;
								

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_transfer_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
									/* do it on review action TODO
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update('goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
										break;
									}
									*/
									$succ_flag = true;
									break;
								}
								else{
									//log
									Bd_Log::warning("no goods");
									break;
								}
							}	
							//$succ_flag = true;
							break;
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $transfer_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "transfer_id error",
					'form_id' => $transfer_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert transfer error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $transfer_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;


	}


	public function doInsInventory($arrInput){
		/*
		 *  param header
		 */  
		$author = $arrInput["author"];
		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$inventory_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		 *  param body
		 */ 
		//$goods_category = $arrInput["goods_category"];
		//$filter = $arrInput["filter"];
		$warehouse_name = $arrInput["storehouse_name"];
		$warehouse_auditor = $arrInput["censor"];
		$inventory_man = $arrInput["storer"];
		$remarks = $arrInput["remarks"];
		$category = $arrInput["category"];

		$sum_instock_count = $arrInput["sum_instock_count"];
		$sum_inventory_count = $arrInput["sum_inventory_count"];
		$sum_count = $arrInput["sum_count"];
		$sum_price = (float)$arrInput["sum_price"];

		/*
		 * insert
		 */ 
		$param = array(
				//'storehouse_id' => $warehouse_id, //instock_warehouse
				'storehouse_name' => $warehouse_name,
				//'censor' => $warehouse_auditor, // reviewed by current user TODO
				'storer' => $inventory_man,
				'remarks' => $remarks,
				'category' => $category,

				'sum_instock_count' => $sum_instock_count,
				'sum_inventory_count' => $sum_inventory_count,
				'sum_count' => $sum_count,
				'sum_price' => $sum_price,

				'time' => $datetime,

				'inventory_status' => $inventory_status,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory'),$param);
		$result_select = $this->db_client->getInsertID();

		$inventory_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		if($result_insert!=false && $inventory_id!=false)
		{
			if($inventory_id>0)
			{
				$inventory_items = $arrInput["inventory_items"];
				$inventory_items_dict = json_decode($inventory_items,true);
				$succ_flag = false;
				
				foreach($inventory_items_dict as $item)
				{
					
					// add new item
					$item_id = $item["goods_id"];
					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/
					

					$instock_count = $item["due_num"];
					$inventory_count = $item["actual_num"];
					$sum_count = $item["sum_count"];
					$sum_price = $item["sum_price"];
					$remarks = $item["remarks"];

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,
							'inventory_id' => $inventory_id,

							'due_num' => $instock_count,
							'actual_num' => $inventory_count,
							'sum_count' => $sum_count,
							'sum_price' => $sum_price,
							'remarks' => $remarks
						);

					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							//$succ_flag = true;
							//break;
							/*
							 * update goods_info with num and price.
							 * if inventory win!! TODO
							 */
							
							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] + $count;
									$new_price = $curr_item['instock_price'] + $count*$unit_price;
									$new_avg = $new_price*1.0/$new_count;
								

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_inventory_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
									/* do it on review action TODO
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update('goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
									}
									*/
									$succ_flag = true;
									break;
								}
								else{
									//log
									break;
								}
							}	
							//$succ_flag = true;
							break;	
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $inventory_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "inventory_id error",
					'form_id' => $inventory_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert inventory error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $inventory_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}

	public function doInsInventory2($arrInput){
		/*
		 *  param header
		 */  
		$author = $arrInput["author"];



		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$inventory_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		 *  param body
		 */ 
		//$goods_category = $arrInput["goods_category"];
		//$filter = $arrInput["filter"];

		$warehouse_auditor = $arrInput["warehouse_auditor"];
		$inventory_man = $arrInput["inventory_man"];
		$remarks = $arrInput["remarks"];

		$sum_instock_count = $arrInput["sum_instock_count"];
		$sum_inventory_count = $arrInput["sum_inventory_count"];
		$sum_count = $arrInput["sum_count"];
		$sum_price = (float)$arrInput["sum_price"];

		/*
		 * insert
		 */ 
		$param = array(
				'storehouse_id' => $warehouse_id, //instock_warehouse
				//'censor' => $warehouse_auditor, // reviewed by current user TODO
				'storer' => $inventory_man,
				'remarks' => $remarks,

				'sum_instock_count' => $sum_instock_count,
				'sum_inventory_count' => $sum_inventory_count,
				'sum_count' => $sum_count,
				'sum_price' => $sum_price,

				'time' => $datetime,

				'inventory_status' => $inventory_status,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory'),$param);
		$result_select = $this->db_client->getInsertID();

		$inventory_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		if($result_insert!=false && $inventory_id!=false)
		{
			if($inventory_id>0)
			{
				$inventory_items = $arrInput["inventory_items"];
				$succ_flag = false;
				
				foreach($inventory_items as $item)
				{
					/*
					// add new item
					$item_id = $item["item_id"];
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/
					

					$instock_count = $item["instock_count"];
					$inventory_count = $item["inventory_count"];
					$sum_count = $item["sum_count"];
					$sum_price = $item["sum_price"];
					$remarks = $item["remarks"];

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,
							'inventory_id' => $inventory_id,

							'due_num' => $instock_count,
							'actual_num' => $inventory_count,
							'sum_count' => $sum_count,
							'sum_price' => $sum_price,
							'remarks' => $remarks
						);

					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_inventory_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							//$succ_flag = true;
							//break;
							/*
							 * update goods_info with num and price.
							 * inventory win!! TODO
							 */
							
							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] + $count;
									$new_price = $curr_item['instock_price'] + $count*$unit_price;
									$new_avg = $new_price*1.0/$new_count;

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_inventory_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
								
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update(Tool_Util::getStoreTable('goods_info'),$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
									}
								}
								else{
									//log
								}
							}	
							//$succ_flag = true;
							break;	
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $inventory_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "inventory_id error",
					'form_id' => $inventory_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert inventory error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $inventory_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}

	public function doInsPurchase($arrInput){
		/*
		 *  param header
		 */  
		$author = $arrInput["author"];
		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$purchase_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		$admin_info = Tool_Const::$adminInfo;
		$purchase_status = Tool_Const::$Purchase_status["reviewing"];
		$author = $admin_info["name"];
		$warehouse_id = $admin_info["store_id"];
		$datetime = date('Y-m-d H:i:s');
		*/

		/*
		 *  param body
		 */ 
		$purchase_type = $arrInput["purchase_type"];
		$censor = $arrInput["censor"];
		$purchase_man = $arrInput["purchase_man"];
		$remarks = $arrInput["remarks"];

		$total_price = (float)$arrInput["sum_price"];
		$total_count = $arrInput["sum_count"];

		$maintenance_id = $arrInput["maintenance_id"];
		$car_id = $arrInput["car_id"];

		/*
		 * insert form msg
		 */ 
		$param = array(
				'purchase_type' => $purchase_type,
				'purchase_man' => $purchase_man,
				'remarks' => $remarks,
				'total_price' => $total_price,
				'total_count' => $total_count,
				'maintenance_id' => $maintenance_id,
				'car_id' => $car_id,
				'time' => $datetime,

				'purchase_status' => $purchase_status,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_purchase'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_purchase'),$param);
		$result_select = $this->db_client->getInsertID();

		$purchase_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		if($result_insert!=false && $purchase_id!=false)
		{
			if($purchase_id>0)
			{
				$purchase_items = $arrInput["purchase_items"];
				$purchase_items_dict = json_decode($purchase_items,true);	//TODO
				//$purchase_items_dict = $purchase_items;
				$succ_flag = false;
				foreach($purchase_items_dict as $item)
				{
					/*
					 * add new item
					 */ 
					$item_id = $item["goods_id"];
					/*
					$name = $item["name"];
					$number = $item["number"];
					$unit = $item["unit"];
					$spec = $item["spec"];
					if ($item_id!=NULL and $item_id!="")
					{
					}
					else{
						$conds_array = array();
						$item_param = array();
						if ($name!=NULL and $name!='')
						{
							array_push($conds_array,'name="'.$name.'"');
							$item_param['name'] = $name;
						}
						if ($number!=NULL and $number!='')
						{
							array_push($conds_array,'ser_num="'.$number.'"');
							$item_param['ser_num'] = $number;
						}
						if ($unit!=NULL and $unit!='')
						{
							array_push($conds_array,'unit="'.$unit.'"');
							$item_param['unit'] = $unit;
						}
						if ($spec!=NULL and $spec!='')
						{
							array_push($conds_array,'spec="'.$spec.'"');
							$item_param['spec'] = $spec;
						}
						$conds_str = implode(' and ',$conds_array);
						//var_dump($conds_str);
						//var_dump($item);
						//var_dump($item_param);
						$item_result_select = $this->db_client->select('goods_info','goods_id',$conds_str,NULL,NULL);
						if (count($item_result_select)!=0)
						{
							$item_id = $item_result_select[0]['goods_id'];
						}
						else
						{
							// 新item自动插入
							// 插入的方式需要review TODO

							//var_dump($item_param);
							$item_result_insert = $this->db_client->insert('goods_info',$item_param);
							//var_dump($item_result_insert);
							$item_result_insert_id = $this->db_client->getInsertID();
							if ($item_result_insert_id!=NULL and $item_result_insert_id!=false)
							{
								$item_update_param = array(
										'super_id' => $item_result_insert_id,
									);
								$item_result_update = $this->db_client->update('goods_info',$item_update_param,' goods_id='.$item_result_insert_id,NULL,NULL);
								$item_id = $item_result_insert_id;
							}
							else{
								$item_id = NULL;
							}
						}
					}
					*/

					$count = $item["count"];
					$unit_price = $item["unit_price"];
					//$sum = $item["sum"];
					$price = $count*$unit_price;
					$settlement = $item["settlement"];
					$remarks = $item["remarks"];
					$maintenance_item = $item["maintenance_item"];

					/*
					 * add new supplier
					 */ 
					//$supplier_name = $item["supplier_name"];
					$supplier_id = $item["supplier_id"];
					/*
					if ($supplier_id!=NULL and $supplier_id!="")
					{
					}
					else{
						$supplier_result_select = $this->db_client->select('supplier_info','supplier_id',' supplier_name="'.$supplier_name.'"',NULL,NULL);
						if ( count($supplier_result_select)!=0 )
						{
							$supplier_id = $supplier_result_select[0]['supplier_id'];
						}
						else
						{
							$supplier_param = array(
									'supplier_name' => $supplier_name,
								);
							$supplier_result_insert = $this->db_client->insert('supplier_info',$supplier_param);
							$supplier_result_insert_id = $this->db_client->getInsertID();
							if ($supplier_result_insert_id!=NULL and $supplier_result_insert_id!=false)
							{
								$supplier_update_param = array(
									'super_id' => $supplier_result_insert_id,
								);
								$supplier_result_update = $this->db_client->update('supplier_info',$supplier_update_param,' supplier_id='.$supplier_result_insert_id,NULL,NULL);
								$supplier_id = $supplier_result_insert_id;
							}
							else{
								$supplier_id = NULL;
							}
						}
					}	
					*/


					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $item_id,
							'supplier_id' => $supplier_id,

							'unit_price' => $unit_price,
							'count' => $count,
							'price' => $price,

							'settlement' => $settlement,
							'remarks' => $remarks,
							'purchase_id' => $purchase_id,

							'time' => $datetime,
							'maintenance_item' => $maintenance_item,
						);
					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_purchase_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{
							/*
							 * update goods_info with num and price
							 */

							$succ_flag = false;
							for($j=0; $j<5; $j++)
							{
								$result_select = $this->db_client->select(Tool_Util::getStoreTable('goods_info'),'name,instock_count,instock_price',' goods_id='.$item_id,NULL,NULL);
								if(count($result_select)>0)
								{
									$curr_item = $result_select[0];
									$new_count = $curr_item['instock_count'] + $count;
									$new_price = $curr_item['instock_price'] + $count*$unit_price;
									$new_avg = $new_price*1.0/$new_count;
								

									$name_update_param = array(
											'name' => $curr_item['name'],
										);
									$this->db_client->update(Tool_Util::getStoreTable('goods_purchase_info'),$name_update_param,' id='.$result_insert_id,NULL,NULL);
									/* do it on review action TODO
									$update_item_param = array(
											'instock_count' => $new_count,
											'instock_price' => $new_price,
											'instock_avg' => $new_avg,
										);
									$result_update = $this->db_client->update('goods_info',$update_item_param,' goods_id='.$item_id,NULL,NULL);
									if ($result_update != false)
									{
										$succ_flag = true;
										break;
									}
									else{
										//log
									}
									*/

									$succ_flag = true;
									break;
								}
								else{
									//log
									break;
								}
							}	
							//$succ_flag = true;
							break;
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $purchase_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "purchase_id error",
					'form_id' => $purchase_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert purchase error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $purchase_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}
	public function doInsQuote($arrInput){
		/*
		 *  param header
		 */  
		//$instock_id = $arrInput["instock_id"];
		$author = $arrInput["author"];
		//$datetime = date('Y-m-d H:i:s');
		$datetime = $arrInput['time'];
		$quote_status = $arrInput["form_status"];
		$warehouse_id = $arrInput["warehouse_id"];
		
		/*
		 *  param body
		 */ 
		$sum_count = $arrInput["sum_count"];
		$remarks = $arrInput["remarks"];
		$quote_man = $arrInput["quote_man"];
		//$censor = $arrInput["censor"];

		/*
		 * insert form msg
		 */ 
		$param = array(
				'time' => $datetime,
				'sum_count' => $sum_count,
				'remarks' => $remarks,
				'quote_man' => $quote_man,
				'author' => $author,
			);
		//TODO store level table
		#$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_storage'),$param);
		$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_quote'),$param);
		$result_select = $this->db_client->getInsertID();

		$quote_id = $result_select;
		//var_dump($result_insert);
		//var_dump($result_select);

		/*
		 * get id and insert item
		 */ 
		if($result_insert!=false && $quote_id!=false)
		{
			if($quote_id>0)
			{
				$quote_items = $arrInput["quote_items"];
				$quote_items_dict = json_decode($quote_items,true);
				//$quote_items_dict = $quote_items;
				$succ_flag = false;
				foreach($quote_items_dict as $item)
				{
					$goods_id = $item["goods_id"];
					$count = $item["count"];
					$remarks = $item["remarks"];
					$purchase_id = $item["purchase_id"];

					$quote_details = $item["quote_details"];
					//$quote_details_dict = json_decode($quote_details,true);
					$quote_details_dict = $quote_details;
					foreach($quote_details_dict as $detail_item)
					{
						$supplier_id = $detail_item['supplier_id'];
						$guide_price = $detail_item['guide_price'];

						$detail_param = array(
								'goods_id' => $goods_id,
								'quote_id' => $quote_id,
								'supplier_id' => $supplier_id,
								'guide_price' => $guide_price,
							);
						$detail_succ_flag = false;
						for($i=0;$i<5;$i++)
						{
							$detail_result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_quote_detail'),$detail_param);
							if ($detail_result_insert != false)
							{
								$detail_succ_flag = true;
								break;
							}
						}
						if ($detail_succ_flag == false)
						{
							break;
						}
					}
					if ($detail_succ_flag == false)
					{
						$return_arr = array(
							'result' => "insert detail error",
							'form_id' => $quote_id,
							'codeMsg' => 'false',
							'code' => 1
						);
						return $return_arr;
					}

					/*
					 * insert new item
					 */ 
					$param = array(
							'goods_id' => $goods_id,
							'time' => $datetime,
							'purchase_id' => $purchase_id,
							'count' => $count,
							'remarks' => $remarks,
							'quote_id' => $quote_id,
						);
					$succ_flag = false;
					for ($i=0; $i<5;$i++) //retry 5 times
					{
						$result_insert = $this->db_client->insert(Tool_Util::getStoreTable('goods_quote_info'),$param);
						$result_insert_id = $this->db_client->getInsertID();
						if ($result_insert != false)
						{	
							$succ_flag = true;
							break;
						}
					}
					if ($succ_flag == false)
					{
						break;
					}
				}
				if ( $succ_flag == false )
				{
					$return_arr = array(
						'result' => "insert item error",
						'form_id' => $quote_id,
						'codeMsg' => 'false',
						'code' => 1
					);
					return $return_arr;
				}
			}
			else
			{
				$return_arr = array(
					'result' => "quote_id error",
					'form_id' => $quote_id,
					'codeMsg' => 'false',
					'code' => 1
				);
				return $return_arr;
			}
		}
		else
		{
			$return_arr = array(
				'result' => "insert quote error",
				'form_id' => NULL,
				'codeMsg' => 'false',
				'code' => 1
			);
			return $return_arr;
		}

		$return_arr = array(
				'result' => "insert succ",
				'form_id' => $quote_id,
				'codeMsg' => 'succ',
				'code' => 0
			);
		return $return_arr;
	}

    public function getSampleById($intId, $arrFields = null){
        return 'GoodBye World!';
    }

    public function addSample($arrFields)
    {
        return true;
    }
    
    public function updateSampleById($intId, $arrFields)
    {
        return true;
    }
    
    public function deleteSampleById($intId)
    {
        return true;
    }
    
    public function getSampleListByConds($arrConds, $arrFields, $intLimit, $intOffset, $arrOrderBy)
    {
        return true;
    }
}
