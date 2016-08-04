<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_GetGoodsDetail extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		
		$db_client  = new Dao_DBbase();

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];

		//test
		/*
		$arrInput["goods_id"] = 6;
		*/
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
		
		if(!isset($arrInput["goods_id"]) or $arrInput["goods_id"]=="")
		{
			return Tool_Util::returnJson($data,0,'');
		}

		$goods_id_list = $arrInput["goods_id"];
		$goods_id_list_str = implode(',',$goods_id_list);
		$select_result = $db_client->select(Tool_Util::getStoreTable('model_goods_info'),'*',' goods_id in ('.$goods_id_list_str.')',NULL,'order by field(goods_id,'.$goods_id_list_str.')');
		if ($select_result!=false){
			if (count($select_result)>0){

				return Tool_Util::returnJson($select_result,0,'succ');
			}else{
				return Tool_Util::returnJson($select_result,0,'');
			}
		}else{
			return Tool_Util::returnJson($select_result,0,'');
		}


	}

}
