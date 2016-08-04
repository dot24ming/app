<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Verify extends Ap_Action_Abstract {
	//private $objServicePageLogcheck;

	public function execute() {
		//1. check if user is login as needed
	    //2. get and validate input params

	    //3. call PageService
		$admin_info = Tool_Const::$adminInfo;
		$admin_name = $admin_info["name"];
		

		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		if(!isset($arrInput["form_type"]) or $arrInput["form_type"]=="")
		{
			return Tool_Util::returnJson($data,-1,'form_type not exist');
		}
		if(!isset($arrInput["form_id"]) or $arrInput["form_id"]=="")
		{
			return Tool_Util::returnJson($data,-1,'form_id not exist');
		}
		if(!isset($arrInput["type"]) or $arrInput["type"]=="")
		{
			return Tool_Util::returnJson($data,-1,'type not exist');
		}

		$form_type = $arrInput["form_type"];
		$form_id = $arrInput["form_id"];
		$form_status = $arrInput["type"];
		$form_type_dict = Tool_Const::$Verify_form_type;
		
		//if (isset($form_type_dict[$form_type])
		if (array_key_exists($form_type,$form_type_dict))
		{
			$table_name = Tool_Const::$Verify_form_info_type[$form_type];	
			// update goods info

			if($form_type=='instock')
			{

				$select_result = $db_client->select(Tool_Util::getStoreTable($table_name),'*',Tool_Const::$Verify_id_const[$form_type].'='.intval($form_id),NULL,NULL);
				if($select_result!==false and count($select_result)>0)
				{
				//if($form_type=='instock')
				//{
					$tmp_goods_dict = array();
					$goods_id_list = array();
					foreach($select_result as $item)
					{

						$goods_id = $item['goods_id'];
						$number = $item['number'];
						$unit_price = $item['unit_price'];

						$tmp_goods_dict[$goods_id] = $item;
						array_push($goods_id_list,$goods_id);

					}
					$goods_id_str = implode(',',$goods_id_list);

					$goods_select_result = $db_client->select(Tool_Util::getStoreTable('model_goods_info'),'*',' goods_id in ('.$goods_id_str.')',NULL,NULL);
					foreach($goods_select_result as $goods_info)
					{
						$goods_id = $goods_info['goods_id'];
						if(isset($tmp_goods_dict[$goods_id]))
						{
							$new_count = $goods_info['instock_count'] + $tmp_goods_dict[$goods_id]['number'];
							$new_price = $goods_info['instock_price'] + $tmp_goods_dict[$goods_id]['number']*  $tmp_goods_dict[$goods_id]['unit_price'];
							$new_avg = $new_price*1.0/$new_count;

							$update_item_param = array(
								'instock_count' => $new_count,
								'instock_price' => $new_price,
								'instock_avg' => $new_avg,
							);
							//var_dump($goods_info);
							$result_update = $db_client->update(Tool_Util::getStoreTable('model_goods_info'),$update_item_param,' goods_id='.$goods_id,NULL,NULL);
							//var_dump($result_update);
							if ($result_update !== false)
							{
							}
							else
							{
							}
						}
					}
				}
			}
			if($form_type=='outstock')
			{
				$select_result = $db_client->select(Tool_Util::getStoreTable($table_name),'*',Tool_Const::$Verify_id_const[$form_type].'='.intval($form_id),NULL,NULL);
				if($select_result!==false and count($select_result)>0)
				{
				//if($form_type=='instock')
				//{
					$tmp_goods_dict = array();
					$goods_id_list = array();
					foreach($select_result as $item)
					{

						$goods_id = $item['goods_id'];
						$number = $item['number'];
						$unit_price = $item['unit_price'];

						$tmp_goods_dict[$goods_id] = $item;
						array_push($goods_id_list,$goods_id);

					}
					$goods_id_str = implode(',',$goods_id_list);

					$goods_select_result = $db_client->select(Tool_Util::getStoreTable('model_goods_info'),'*',' goods_id in ('.$goods_id_str.')',NULL,NULL);
					foreach($goods_select_result as $goods_info)
					{
						$goods_id = $goods_info['goods_id'];
						if(isset($tmp_goods_dict[$goods_id]))
						{
							$new_count = $goods_info['instock_count'] - $tmp_goods_dict[$goods_id]['number'];
							$new_price = $goods_info['instock_price'] - $tmp_goods_dict[$goods_id]['number']*  $tmp_goods_dict[$goods_id]['unit_price'];
							$new_avg = $new_price*1.0/$new_count;

							$update_item_param = array(
								'instock_count' => $new_count,
								'instock_price' => $new_price,
								'instock_avg' => $new_avg,
							);
							//var_dump($goods_info);
							$result_update = $db_client->update(Tool_Util::getStoreTable('model_goods_info'),$update_item_param,' goods_id='.$goods_id,NULL,NULL);
							//var_dump($result_update);
							if ($result_update !== false)
							{
							}
							else
							{
							}
						}
					}
				}
			}
			// 20160618
			if ($form_type=='inventory')
			{
				$inventoryId = $form_id;
				$goodsInventoryInfoDao = new Dao_GoodsInventoryInfo();
				$goodsList = $goodsInventoryInfoDao->getInfo($inventoryId);
				if (empty($goodsList) || !is_array($goodsList)) {
					return Tool_Util::returnJson();
				}
				$goodsIds = array();
				foreach ($goodsList as $item) {
					$goodsIds[] = $item['goods_id'];
				}
				$goodsInfoDao = new Dao_GoodsInfo();
				$goodsInfos = $goodsInfoDao->getInfos($goodsIds);
				if (is_array($goodsInfos) && $goodsInfos) {
					foreach ($goodsInfos as $item) {
						$goodsInfoSorted[$item['goods_id']] = $item;
					}
				}
				foreach ($goodsList as $goods) {
					$goodsItems[] = array_merge($goods, $goodsInfoSorted[$goods['goods_id']]);
				}
				//var_dump($goodsItems);

				foreach ($goodsItems as $goods)
				{
					$goods_id = $goods['goods_id'];
					$instock_count = $goods['instock_count'];
					$sum_count = $goods['sum_count'];
					$new_count = $instock_count + $sum_count;

					//$new_price = $goods['instock_price'] + $tmp_goods_dict[$goods_id]['number'] * $tmp_goods_dict[$goods_id]['unit_price'];
					$new_avg = $goods['instock_price']*1.0/$new_count;

					$update_item_param = array(
						'instock_count' => $new_count,
						//'instock_price' => $new_price,
						'instock_avg' => $new_avg,
					);
					$result_update = $db_client->update(Tool_Util::getStoreTable('model_goods_info'),$update_item_param,' goods_id='.$goods_id,NULL,NULL);
					if ($result_update !== false)
					{
					}
					else
					{
					}

					//todo
					// gen inventory instock/outstock result
				}
			}

			// update form status
			$update_param = array(
					Tool_Const::$Verify_status_const[$form_type] => $form_status,
					'review_time' => date('Y-m-d H:i:s'),
					'censor' => $admin_name,
				);
			$update_conds_param = array(
					Tool_Const::$Verify_id_const[$form_type].'=' => intval($form_id),
				);
			//var_dump($update_param);
			//var_dump($form_id);
			$result_select = $db_client->update(Tool_Util::getStoreTable(Tool_Const::$Verify_form_type[$form_type]),$update_param,$update_conds_param);

			//var_dump($result_select);
			return Tool_Util::returnJson($result_select,0,'succ');
		}
		else
		{
			return Tool_Util::returnJson($form_type,-1,'form_type not exists');
		}
		
		/*	
		else{
			$supplier_name = $arrInput["supplier_name"];
			$info = $arrInput["info"];
			$address = $arrInput["address"];
			$phone = $arrInput["phone"];
			$linkman = $arrInput["linkman"];

			$result_select = $db_client->select('supplier_info','*','supplier_name="'.$supplier_name.'"',NULL,NULL);
			if ($result_select!=false and count($result_select)>0){
				$data = false;
				return Tool_Util::returnJson($data,-1,'supplier name exit');
			}
			else{
				$item_param = array(
						'supplier_name' => $supplier_name,
						'info' => $info,
						'address' => $address,
						'phone' => $phone,
						'linkman' => $linkman
					);
				$result_insert = $db_client->insert('supplier_info',$item_param);
				if ($result_insert!=false and count($result_insert)>0){
					$data = true;
					return Tool_Util::returnJson($data,0,'succ');
				}
				else{
					$data = false;
					return Tool_Util::returnJson($data,-1,'insert error');
				}
			}
		}
		*/


		return Tool_Util::returnJson($data,0,'succ');
		//return var_dump($logCheckResult);
		///*
		if ($logCheckResult == 1){
			echo "true";
			return true;
		}else{
			echo "false";
			return false;
		}
		//*/
		//echo $logCheckResult;
		//return $logCheckResult;





		//4. chage data to out format
		//$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		//$tpl = Bd_TplFactory::getInstance();
	    //$tpl->assign('arrOutput',$arrOutput['data']);
	    //$tpl->display('yunyunche_odp/index.tpl');
		
		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		//Bd_Log::addNotice('out', $arrOutput);

		//return $strOut; 

	}

}
