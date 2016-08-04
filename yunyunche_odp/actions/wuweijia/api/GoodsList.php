<?php 
class Action_GoodsList extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		$category = Tool_Util::filter($arrInput['category']);
		$start = Tool_Util::filter($arrInput['start']);
		$end = Tool_Util::filter($arrInput['end']);

		$goodsInfoDao = new Dao_GoodsInfo();
		//$list = $goodsInfoDao->getList($category);
		//$start = 0;
		//$end = 10;
		$list = $goodsInfoDao->getListPart($category,$start,$end);
		//var_dump($list);

		//return Tool_Util::returnJson(array('list' => $list));
		return Tool_Util::returnJsonEx($list['list'],0,'succ',$list['size']);
	}
}
