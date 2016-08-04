<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_PackageBuyBusiness extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		//$arrInput = $arrRequest['post'];
		$arrInput = $arrRequest['get'];

		//var_dump($arrInput);
		//$arrInput['uid'] = '140';
		$arrInput['start_idx'] = 0;
		$arrInput['end_idx'] = 10;
		$offset = $arrInput['end_idx'] - $arrInput['start_idx'];
		$limit_str = 'limit '.$arrInput['start_idx'].','.$offset;

		if(!isset($arrInput["wu"]) or $arrInput["wu"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'name not exist');
		}

		//$result_member_recharge = $db_client->select(Tool_Util::getStoreTable('member_recharge'),'*','uid='.$arrInput['uid'],NULL,'ORDER BY recharge_datetime DESC '.$limit_str);

		$result_package_buy = $db_client->select(Tool_Util::getStoreTable('package_card'),'*','user_id='.$arrInput['uid'],NULL,'ORDER BY active_date DESC '.$limit_str);
		$business_ret = array();
		$business_item = array();
		if($result_package_buy!==false)
		{
			foreach($result_package_buy as $package_buy)
			{
				$business_item = array();
				$business_item['bid'] = '1';
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

		/*
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
		*/
		//return Tool_Util::returnJson($result_member_recharge,0,'succ');
		return Tool_Util::returnJson($business_ret,0,'succ');
		//return Tool_Util::returnJson($data,0,'succ');
	}

}
