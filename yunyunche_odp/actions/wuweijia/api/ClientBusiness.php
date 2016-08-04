<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_ClientBusiness extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		//$arrInput = $arrRequest['post'];
		$arrInput = $arrRequest['get'];

		//var_dump($arrInput);
		//$arrInput['uid'] = '140';


		if(!isset($arrInput["start"])){
			$arrInput['start'] = 0;
		}
		else{
			 if ($arrInput['start']<0){
				  return Tool_Util::returnJson(NULL,101,'params error');
			 }
		}
		if(!isset($arrInput["end"])){
			$arrInput['end'] = 10;
		}

		$start = $arrInput['start'];

		$offset = $arrInput['end'] - $arrInput['start'];
		$limit_str = 'limit '.$arrInput['start'].','.$offset;

		$all_offset = $arrInput['end'] - 0;
		$all_limit_str = 'limit 0,'.$all_offset;

		if(!isset($arrInput["wu"]) or $arrInput["wu"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'name not exist');
		}

		$member_select = true;
		$package_select = true;
		if(isset($arrInput['type']))
		{
			switch ($arrInput['type'])
			{
			case 1:
				$package_select = false;
				break;
			case 2:
				$member_select = false;
				break;
			default:
				break;
			}
		}

		$business_ret = array();
		$business_item = array();
		$record_count = 0;

		if ($package_select)
		{
		$select_count = $db_client->selectCount(Tool_Util::getStoreTable('model_package_card'),'user_id='.$arrInput['uid'],NULL,NULL);
		$record_count += $select_count;
		if($member_select)
		{
			$result_package_buy = $db_client->select(Tool_Util::getStoreTable('model_package_card'),'*','user_id='.$arrInput['uid'],NULL,'ORDER BY active_date DESC '.$all_limit_str);
		}
		else
		{
			$result_package_buy = $db_client->select(Tool_Util::getStoreTable('model_package_card'),'*','user_id='.$arrInput['uid'],NULL,'ORDER BY active_date DESC '.$limit_str);
		}

		if($result_package_buy!==false)
		{
			foreach($result_package_buy as $package_buy)
			{
				$business_item = array();
				$business_item['bid'] = $package_buy['package_card_id'].'(套餐卡号)';
				$business_item['btype'] = '套餐卡购买';
				$business_item['datetime'] = $package_buy['active_date'];
				$business_item['detail'] = $package_buy['package_name'];
				$business_item['transaction_mode'] = $package_buy['settlement'];
				$business_item['transaction_amount'] = $package_buy['package_cost'];
				array_push($business_ret,$business_item);
			}
		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'package business error');
		}
		}

		if($member_select)
		{
		$select_count = $db_client->selectCount(Tool_Util::getStoreTable('model_member_recharge'),'uid='.$arrInput['uid'],NULL,NULL);
		$record_count += $select_count;
		if($package_select)
		{
			$result_member_recharge = $db_client->select(Tool_Util::getStoreTable('model_member_recharge'),'*','uid='.$arrInput['uid'],NULL,'ORDER BY recharge_datetime DESC '.$all_limit_str);
		}
		else
		{
			$result_member_recharge = $db_client->select(Tool_Util::getStoreTable('model_member_recharge'),'*','uid='.$arrInput['uid'],NULL,'ORDER BY recharge_datetime DESC '.$limit_str);
		}
		if ($result_member_recharge!==false)
		{
			foreach($result_member_recharge as $member_recharge)
			{
				$business_item = array();
				$business_item['bid'] = $member_recharge['recharge_id'];
				$business_item['btype'] = '会员卡充值';
				$business_item['datetime'] = $member_recharge['recharge_datetime'];
				$business_item['detail'] = $member_recharge['remark'];
				$business_item['transaction_mode'] = $member_recharge['recharge_way'];
				//$business_item['transaction_mode'] = '/';
				$business_item['transaction_amount'] = $member_recharge['recharge_amount'];
				array_push($business_ret,$business_item);
			}

		}
		else
		{
			return Tool_Util::returnJson($data, -1, 'member business error');
		}
		}
		$score = array();
		foreach($business_ret as $k => $v)
		{
			$score[$k] = $v['datetime'];
		}
		array_multisort($score,SORT_DESC,$business_ret);

		if($package_select and $member_select)
		{
			//var_dump($start);
			//var_dump($offset);
			//var_dump($business_ret);
			$limit_ret = array_slice($business_ret,$start,$offset);
			//$limit_ret = $business_ret;
		}
		else
		{
			//$limit_ret = array_slice($business_ret,$start,$offset);
			$limit_ret = $business_ret;
		}

		//return Tool_Util::returnJson($result_member_recharge,0,'succ');
		//return Tool_Util::returnJson($business_ret,0,'succ');
		return Tool_Util::returnJsonEx($limit_ret,0,'succ',$record_count);
		//return Tool_Util::returnJson($data,0,'succ');
	}

}
