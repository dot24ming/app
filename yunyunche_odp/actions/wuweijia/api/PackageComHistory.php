<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_PackageComHistory extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		//$arrInput = $arrRequest['post'];
		$arrInput = $arrRequest['get'];

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

		$offset = $arrInput['end'] - $arrInput['start'];
		$limit_str = 'limit '.$arrInput['start'].','.$offset;

		if(!isset($arrInput["wu"]) or $arrInput["wu"]=="")
		{
			//return Tool_Util::returnJson($data,-1,'name not exist');
		}

		$ret_list = array();
		$service_detail_select = array();
		$record_count = 0;

		/*
		$all_form_id_select = $db_client->select(Tool_Util::getStoreTable('mobile_service_form'),'form_id','user_id='.$arrInput['uid'].' and status=1',NULL);
		if($all_form_id_select!==false)
		{
			$all_form_id_str = implode(',',$all_form_id_select);
			$select_count = $db_client->selectCount(Tool_Util::getStoreTable('mobile_service_form_info'),'form_id in ('.$all_form_id_str.')',NULL,NULL);
			$record_count += $select_count;
		}
		 */

		$member_com_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_service_form'),'form_id,settlement_time,person,picker,plate_number','user_id='.$arrInput['uid'].' and status=1',NULL,'ORDER BY settlement_time DESC ');
		if($member_com_select!==false)
		{
			$form_id_2_idx = array();
			$form_id_list = array();
			$idx = 0;
			foreach($member_com_select as $member_com_info)
			{
				$form_id = $member_com_info['form_id'];
				array_push($form_id_list,$form_id);
				$form_id_2_idx[$form_id] = $idx;
				$idx += 1;
			}
			if(count($form_id_list)>0)
			{
				$form_id_str = implode(',',$form_id_list);
				$select_count = $db_client->selectCount(Tool_Util::getStoreTable('model_mobile_service_form_info'),'form_id in ('.$form_id_str.')',NULL,NULL);
				$record_count += $select_count;
				$service_detail_select = $db_client->select(Tool_Util::getStoreTable('model_mobile_service_form_info'),'*','form_id in ('.$form_id_str.') and package_card_id>0',NULL,$limit_str);
				if($service_detail_select!==false)
				{
					$service_detail_select_count = count($service_detail_select);
					for($i=0;$i<$service_detail_select_count;$i++)
					{
						$form_id = $service_detail_select[$i]['form_id'];
						//$service_name = $service_info['service_name'];
						//$package_card_id =  $service_info['package_card_id'];

						$service_detail_select[$i]['settlement_time'] = $member_com_select[$form_id_2_idx[$form_id]]['settlement_time'];
						$service_detail_select[$i]['person'] = $member_com_select[$form_id_2_idx[$form_id]]['person'];
						$service_detail_select[$i]['picker'] = $member_com_select[$form_id_2_idx[$form_id]]['picker'];
						$service_detail_select[$i]['plate_number'] = $member_com_select[$form_id_2_idx[$form_id]]['plate_number'];
					}
					/*
					foreach($service_detail_select as $service_info)
					{
						$form_id = $service_info['form_id'];
						$service_name = $service_info['service_name'];
						$package_card_id =  $service_info['package_card_id'];

						$service_info['settlement_time'] = $member_com_select[$form_id_2_idx[$form_id]]['settlement_time'];
						$service_info['person'] = $member_com_select[$form_id_2_idx[$form_id]]['person'];
						$service_info['picker'] = $member_com_select[$form_id_2_idx[$form_id]]['picker'];
						$service_info['plate_number'] = $member_com_select[$form_id_2_idx[$form_id]]['plate_number'];
					}
					*/
				}
			}
			$ret_list = $service_detail_select;
		}
		else{
			return Tool_Util::returnJson($member_com_select,-1,'fail');
		}


		/*
		$score = array();
		foreach($business_ret as $k => $v)
		{
			$score[$k] = $v['datetime'];
		}
		array_multisort($score,SORT_DESC,$business_ret);
		*/


		//return Tool_Util::returnJson($result_member_recharge,0,'succ');
		//return Tool_Util::returnJson($ret_list,0,'succ');
		return Tool_Util::returnJsonEx($ret_list,0,'succ',$record_count);
		//return Tool_Util::returnJson($data,0,'succ');
	}

}
