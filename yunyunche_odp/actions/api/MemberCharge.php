<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MemberCharge extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];

		/*
		$arrInput['uid'] = '66';
		$arrInput['recharge_amount'] = 10.23;
		$arrInput['saleman'] = 'abc'; // no use, replaced by saleman_id
		$arrInput['saleman_id'] = '123';
		$arrInput['recharge_datetime'] = '2016-04-09 12:00:00';
		$arrInput['recharge_way'] = 0;
		$arrInput['remark'] = 'no';
		$arrInput['is_invoice'] = 0;
		$arrInput['invoice_num'] = '123';
		*/

		if(!isset($arrInput["wu"]) or $arrInput["wu"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'name not exist');
		}
		if(empty($arrInput['recharge_amount']))
		{
			$arrInput['recharge_amount'] = 0;
		}
		if(empty($arrInput['gift_amount']))
		{
			$arrInput['gift_amount'] = 0;
		}

		$param = array(
			'recharge_amount' => $arrInput['recharge_amount'] ,
			'gift_amount' => $arrInput['gift_amount'],
			'actual_amount' => $arrInput['recharge_amount'] + $arrInput['gift_amount'],
			'recharge_datetime' => date('Y-m-d H:i:s',time()),
			'uid' => $arrInput['uid'],
			'recharge_way' => $arrInput['recharge_way'],
			'saleman_id' => $arrInput['saleman_id'],
			'saleman' => $arrInput['saleman'],
			'remark' => $arrInput['remark'],
			'is_invoice' => $arrInput['is_invoice'],
			'invoice_num' => $arrInput['invoice_num'],
			);


		$result_select = $db_client->select(Tool_Util::getStoreTable('user_info'),'member_card_balance','user_id='.$arrInput['uid'],NULL,NULL);
		if($result_select!==false and count($result_select)>0)
		{
			$user_info = $result_select[0];
			$member_card_balance = $user_info['member_card_balance'];
			//$new_balance = $member_card_balance +  $arrInput['recharge_amount'];
			$new_balance = $member_card_balance +  $arrInput['recharge_amount'] + $arrInput['gift_amount'];

			$result_insert = $db_client->insert(Tool_Util::getStoreTable('member_recharge'),$param);
			if ($result_insert!==false and count($result_insert)>0)
			{
				$update_param = array(
						'member_card_balance' => $new_balance
					);
				$result_update = $db_client->update(Tool_Util::getStoreTable('user_info'),$update_param,'user_id='.$arrInput['uid'],NULL,NULL);
				if ($result_update!==false and count($result_update)>0)
				{
					return Tool_Util::returnJson($data,0,'succ');
				}
				else
				{
					return Tool_Util::returnJson($data,-2,'balance update error');
				}
			}
			else
			{
				return Tool_Util::returnJson($data,-3,'recharge error');
			}
		}
		else
		{
			return  Tool_Util::returnJson($data, -1, 'user not exist');
		}

		return Tool_Util::returnJson($data,0,'succ');
	}

}
