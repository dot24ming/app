<?php 
class Action_AddGoodsInfo extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$name = Tool_Util::filter($arrInput['name']);	
		$category = Tool_Util::filter($arrInput['category']);
		$info = Tool_Util::filter($arrInput['info']);
		$spec = Tool_Util::filter($arrInput['spec']);
		$unit = Tool_Util::filter($arrInput['unit']);
		$serNum = Tool_Util::filter($arrInput['ser_num']);
		$superId = Tool_Util::filter($arrInput['super_id']);

		if (empty($name)) {
			return Tool_Util::returnJson('', '参数错误', 1);
		}
		$goodsInfoDao = new Dao_GoodsInfo();
		$obj = $goodsInfoDao->getInfoBySerNum($serNum);	
		if ($obj) {
			return Tool_Util::returnJson('', 1, "serNum已存在");
		}

		$newGoodsId = $goodsInfoDao->addGoodsInfo($name, $category, $info, $spec, $unit, $serNum, $superId);
		if (is_array($newGoodsId) || $newGoodsId <= 0) {
			return Tool_Util::returnJson('', $ret['errno'], '新增失败');
		} else {
			return Tool_Util::returnJson();
		}
	}
}
